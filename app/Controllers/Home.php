<?php

namespace App\Controllers;

use App\Models\ModelPeminjaman; 
use App\Libraries\WahaWhatsapp; //Panggil library WAHA

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }                  

    // --- FUNGSI UNTUK HALAMAN ADMIN ---
    // --- FUNGSI UNTUK HALAMAN ADMIN ---
    public function admin()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/member')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }

        $db = \Config\Database::connect();

        // 1. Hitung Total Buku
        $total_buku = $db->table('buku')->countAllResults();

        // 2. Hitung Total Eksemplar 
        // (Asumsi menggunakan tabel 'eksemplar'. Jika menggunakan field stok di tabel buku, 
        // ganti menjadi: $db->table('buku')->selectSum('stok')->get()->getRow()->stok ?? 0;)
        $total_eksemplar = $db->table('eksemplar')->countAllResults();

        // 3. Hitung Peminjaman Aktif (Status 'Dipinjam')
        $peminjaman_aktif = $db->table('peminjaman')->where('status', 'Dipinjam')->countAllResults();

        // 4. Hitung Total Denda Belum Dibayar secara dinamis
        $builderDenda = $db->table('peminjaman');
        $builderDenda->select('tenggat_waktu');
        $builderDenda->where('status', 'Dipinjam');
        $builderDenda->where('tenggat_waktu <', date('Y-m-d')); // Melewati hari ini
        $data_terlambat = $builderDenda->get()->getResultArray();

        $total_denda = 0;
        $tarif_denda_per_hari = 1000; // Sesuai dengan tarif sebelumnya

        foreach ($data_terlambat as $row) {
            $tenggat = strtotime($row['tenggat_waktu']);
            $sekarang = strtotime(date('Y-m-d'));
            $selisih_detik = $sekarang - $tenggat;
            $hari_terlambat = floor($selisih_detik / (60 * 60 * 24));

            if ($hari_terlambat > 0) {
                $total_denda += ($hari_terlambat * $tarif_denda_per_hari);
            }
        }

        // 5. Mengambil data pengajuan yang berstatus 'Menunggu ACC' secara live
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.status', 'Menunggu ACC');
        $builder->orderBy('peminjaman.tgl_pengajuan', 'ASC');
        $peminjaman_baru = $builder->get()->getResultArray();

        // Kirim semua variabel ke view dashboard admin
        $data = [
            'title'            => 'Dashboard Admin - BalaNus',
            'peminjaman_baru'  => $peminjaman_baru,
            'total_buku'       => $total_buku,
            'total_eksemplar'  => $total_eksemplar,
            'peminjaman_aktif' => $peminjaman_aktif,
            'total_denda'      => $total_denda
        ];

        return view('home_admin', $data); 
    }

    // --- API ENDPOINT UNTUK LIVE UPDATE DASHBOARD TANPA RELOAD ---
    public function live_peminjaman()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.status', 'Menunggu ACC');
        $builder->orderBy('peminjaman.tgl_pengajuan', 'ASC');
        $peminjaman_baru = $builder->get()->getResultArray();

        // Mengembalikan data mentah JSON agar dibaca JavaScript secara real-time
        return $this->response->setJSON($peminjaman_baru);
    }

    // --- FUNGSI UNTUK HALAMAN MEMBER ---
    public function member()
    {
        $session = session();
        
        // Proteksi Halaman
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if ($session->get('role') !== 'member') {
            return redirect()->to('/admin')->with('error', 'Halaman ini khusus pengguna biasa.');
        }

        // Inisialisasi Model
        $modelPeminjaman = new ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku(); // TAMBAHAN: Panggil Model Buku
        $id_user = $session->get('id_user'); 

        // Mengambil data dari Model
        $rekap = $modelPeminjaman->getRekapStatus($id_user);
        $data_tabel = $modelPeminjaman->getRiwayatPeminjaman($id_user);
        
        // TAMBAHAN: Mengambil 3 Buku Terbaru
        $buku_terbaru = $modelBuku->orderBy('id_buku', 'DESC')->findAll(3);

        // Menyiapkan data untuk dikirim ke View
        $data = [
            'username'        => $session->get('username'),
            'menunggu_acc'    => $rekap['total_menunggu'] ?? 0,
            'sedang_dipinjam' => $rekap['total_dipinjam'] ?? 0,
            'total_dibaca'    => $rekap['total_dibaca'] ?? 0,
            'riwayat_pinjam'  => $data_tabel,
            'buku_terbaru'    => $buku_terbaru // TAMBAHAN: Masukkan data buku ke array
        ];

        return view('home_member', $data);
    }

    // --- FUNGSI UNTUK MEMBATALKAN PENGAJUAN (DROP DATA & KEMBALIKAN STOK) ---
    public function batalkan($id_peminjaman)
    {
        $session = session();
        
        // Pastikan yang mengakses sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Panggil kedua model
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();

        // 1. Cari data peminjaman yang mau dibatalkan terlebih dahulu
        $peminjaman = $modelPeminjaman->find($id_peminjaman);

        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];

            // 2. Cari data buku yang dipinjam tersebut
            $buku = $modelBuku->find($id_buku);

            if ($buku) {
                // 3. Kembalikan stok buku (+1)
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }

            // 4. Setelah stok aman, baru hapus data pengajuan peminjamannya
            $modelPeminjaman->delete($id_peminjaman);
        }

        return redirect()->to('/member')->with('success', 'Pengajuan berhasil dibatalkan dan stok buku telah dikembalikan.');
    }

    // --- FUNGSI UNTUK MENAMPILKAN HALAMAN DETAIL ---
    public function detail($id_peminjaman)
    {
        $session = session();
        
        // Pastikan yang mengakses sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Panggil model
        $modelPeminjaman = new ModelPeminjaman();
        $id_user = $session->get('id_user');

        // Ambil data detail dari Model berdasarkan ID Peminjaman dan ID User
        $detail = $modelPeminjaman->getDetailPeminjaman($id_peminjaman, $id_user);

        // Jika data tidak ditemukan atau bukan milik user yang sedang login
        if (!$detail) {
            return redirect()->to('/member')->with('error', 'Data tidak ditemukan.');
        }

        // Siapkan data untuk dikirim ke halaman view detail
        $data = [
            'title'    => 'Detail Peminjaman - BalaNus',
            'username' => $session->get('username'),
            'detail'   => $detail 
        ];

        return view('detail_peminjaman', $data);
    }

    // --- FUNGSI UNTUK HALAMAN KATALOG BUKU ---
    public function katalog()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $modelBuku = new \App\Models\ModelBuku();
        
        // Tangkap inputan dari form pencarian (jika ada)
        $keyword = $this->request->getVar('keyword');
        
        // Ambil data buku berdasarkan pencarian
        $buku = $modelBuku->getKatalog($keyword);

        $data = [
            'title'    => 'Katalog Buku - BalaNus',
            'username' => $session->get('username'),
            'buku'     => $buku,
            'keyword'  => $keyword // Untuk menampilkan ulang kata kunci di kotak pencarian
        ];

        return view('katalog_buku', $data);
    }

    // --- FUNGSI UNTUK MEMPROSES PENGAJUAN PINJAM ---
    public function ajukan($id_buku)
    {
        $session = session();
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();

        // 1. Cek apakah buku tersedia (stok > 0)
        $buku = $modelBuku->find($id_buku);
        if (!$buku || $buku['stok'] <= 0) {
            return redirect()->to('/katalog')->with('error', 'Maaf, stok buku sedang habis.');
        }

        // 2. Masukkan data ke tabel peminjaman
        $data = [
            'id_user'       => $session->get('id_user'),
            'id_buku'       => $id_buku,
            'tgl_pengajuan' => date('Y-m-d'), // Tanggal hari ini
            'status'        => 'Menunggu ACC'  // Status awal
        ];

        $modelPeminjaman->save($data);

        // 3. Kurangi stok buku secara otomatis
        $modelBuku->update($id_buku, ['stok' => $buku['stok'] - 1]);

        return redirect()->to('/member')->with('success', 'Berhasil! Pengajuan pinjam "' . $buku['judul_buku'] . '" telah dikirim.');
    }

    // --- FUNGSI UNTUK HALAMAN DAFTAR PEMINJAMAN LENGKAP ---
    public function riwayat_saya()
    {
        $session = session();
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        
        $id_user = $session->get('id_user');
        
        // Mengambil semua data peminjaman user ini (tanpa limit 5)
        $riwayat = $modelPeminjaman->select('peminjaman.*, buku.judul_buku')
                    ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                    ->where('id_user', $id_user)
                    ->orderBy('tgl_pengajuan', 'DESC')
                    ->findAll();

        $data = [
            'title'    => 'Daftar Peminjaman Saya - BalaNus',
            'username' => $session->get('username'),
            'riwayat'  => $riwayat
        ];

        return view('peminjaman_saya', $data);
    }

    // ====================================================================
    // TAMBAHAN BARU: FUNGSI UNTUK ADMIN MENYETUJUI ATAU MENOLAK PINJAMAN
    // ====================================================================

    // Fungsi Meng-ACC Pengajuan
    public function acc_peminjaman($id_peminjaman)
    {
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $db = \Config\Database::connect(); 
        
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username, users.no_telp'); 
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.id_peminjaman', $id_peminjaman); 
        $dataPinjam = $builder->get()->getRowArray();

        $tenggat = date('Y-m-d', strtotime('+7 days'));

        $modelPeminjaman->update($id_peminjaman, [
            'status'        => 'Dipinjam',
            'tenggat_waktu' => $tenggat
        ]);

        // PROSES KIRIM WHATSAPP (ACC) & PENCATATAN AUDIT TRAIL
        if ($dataPinjam && !empty($dataPinjam['no_telp'])) {
            $wa = new \App\Libraries\WahaWhatsapp();
            
            $nomorWA   = $dataPinjam['no_telp'];
            $namaUser  = $dataPinjam['username'];
            $judulBuku = $dataPinjam['judul_buku'];

            $pesan = "Halo *$namaUser*,\n\n";
            $pesan .= "Pengajuan peminjaman buku kamu telah *DISETUJUI* oleh Admin Perpustakaan BalaNus. ✅\n\n";
            $pesan .= "📖 *Detail Peminjaman:*\n";
            $pesan .= "• Judul Buku: $judulBuku\n";
            $pesan .= "• Batas Waktu: " . date('d M Y', strtotime($tenggat)) . "\n\n";
            $pesan .= "Silakan ambil buku fisik kamu di meja petugas perpustakaan ya. Terima kasih!";

            // Kirim pesan sekaligus catat ke tabel notifications
            $wa->sendText($nomorWA, $pesan, 'Peminjaman Disetujui');
        }

        return redirect()->to('/admin')->with('success', 'Peminjaman berhasil disetujui & Notifikasi WA Terkirim!');
    }
        
    // Fungsi Menolak Pengajuan
    public function tolak_peminjaman($id_peminjaman)
    {
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();
        $db = \Config\Database::connect(); 

        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username, users.no_telp');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.id_peminjaman', $id_peminjaman);
        $dataPinjam = $builder->get()->getRowArray();

        $peminjaman = $modelPeminjaman->find($id_peminjaman);
        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];
            $buku = $modelBuku->find($id_buku);
            
            if ($buku) {
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }
            $modelPeminjaman->delete($id_peminjaman);
        }

        // PROSES KIRIM WHATSAPP (TOLAK)
        if ($dataPinjam && !empty($dataPinjam['no_telp'])) {
            $wa = new WahaWhatsapp();
            
            // --- LOGIKA UBAH 0 MENJADI 62 ---
            $nomorWA = $dataPinjam['no_telp'];
            if (substr($nomorWA, 0, 1) === '0') {
                $nomorWA = '62' . substr($nomorWA, 1);
            }
            if (substr($nomorWA, 0, 1) === '+') {
                $nomorWA = substr($nomorWA, 1);
            }
            // ---------------------------------

            $namaUser  = $dataPinjam['username'];
            $judulBuku = $dataPinjam['judul_buku'];

            $pesan = "Halo *$namaUser*,\n\n";
            $pesan .= "Mohon maaf, pengajuan peminjaman buku untuk judul *$judulBuku* telah *DITOLAK* oleh Admin. ❌\n\n";
            $pesan .= "Hal ini bisa terjadi karena ketersediaan stok buku sedang kosong atau ada hal lainnya. Silakan hubungi petugas perpustakaan untuk info lebih lanjut.";

            $wa->sendText($nomorWA, $pesan);
        }

        return redirect()->to('/admin')->with('error', 'Pengajuan peminjaman ditolak & Notifikasi WA Terkirim.');
    }

    // --- FUNGSI UNTUK HALAMAN DETAIL BUKU ---
    public function detail_buku($id_buku)
    {
        $session = session();
        
        // Proteksi Halaman
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $modelBuku = new \App\Models\ModelBuku();
        $buku = $modelBuku->find($id_buku);

        // Jika buku tidak ditemukan di database
        if (!$buku) {
            return redirect()->to('/katalog')->with('error', 'Buku tidak ditemukan.');
        }

        // Siapkan data untuk dikirim ke halaman detail
        $data = [
            'title'        => 'Detail Buku - ' . $buku['judul_buku'] . ' - BalaNus',
            'username'     => $session->get('username'),
            'buku'         => $buku,
            'active_menu'  => 'katalog' // Menandakan bahwa halaman katalog yang aktif di sidebar
        ];

        return view('detail_buku', $data);
    }

    // --- FUNGSI UNTUK HALAMAN DENDA & TANGGUNGAN (MEMBER) ---
    public function denda()
    {
        $session = session();
        
        // 1. Proteksi Halaman Member
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $id_user = $session->get('id_user');

        // 2. Ambil data peminjaman milik user ini yang berstatus 'Dipinjam' dan sudah melewati tenggat waktu
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->where('peminjaman.id_user', $id_user);
        $builder->where('peminjaman.status', 'Dipinjam');
        $builder->where('peminjaman.tenggat_waktu <', date('Y-m-d')); // Melebihi hari ini = Terlambat
        $data_terlambat = $builder->get()->getResultArray();

        $list_denda = [];
        $total_denda = 0;
        $tarif_denda_per_hari = 1000; // Tarif Rp 1.000 / hari sesuai display admin

        // 3. Looping untuk menghitung selisih hari dan nominal denda secara real-time
        foreach ($data_terlambat as $row) {
            $tenggat = strtotime($row['tenggat_waktu']);
            $sekarang = strtotime(date('Y-m-d'));
            
            // Hitung selisih hari
            $selisih_detik = $sekarang - $tenggat;
            $hari_terlambat = floor($selisih_detik / (60 * 60 * 24));

            if ($hari_terlambat > 0) {
                $nominal = $hari_terlambat * $tarif_denda_per_hari;
                $total_denda += $nominal;

                // Masukkan ke dalam array untuk dirender ke tabel View
                $list_denda[] = [
                    'judul_buku'     => $row['judul_buku'],
                    'tenggat_waktu'  => $row['tenggat_waktu'],
                    'hari_terlambat' => $hari_terlambat,
                    'nominal'        => $nominal,
                    'status_bayar'   => 'Belum Lunas'
                ];
            }
        }

        // 4. Kirim semua variabel ke view denda.php
        $data = [
            'title'       => 'Denda & Tanggungan - BalaNus',
            'username'    => $session->get('username'),
            'total_denda' => $total_denda,
            'total_lunas' => 0, 
            'list_denda'  => $list_denda
        ];

        return view('denda', $data); 
    }

    // ====================================================================
    // FUNGSI UNTUK ADMIN MELUNASI DENDA & MENGEMBALIKAN BUKU AUTOMATICALLY
    // ====================================================================
    public function lunas_denda($id_peminjaman)
    {
        $session = session();
        
        // Proteksi khusus Admin
        if (!$session->get('logged_in') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();

        // 1. Cari data peminjaman berdasarkan ID
        $peminjaman = $modelPeminjaman->find($id_peminjaman);

        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];

            // 2. Ubah status peminjaman menjadi 'Dikembalikan'
            $modelPeminjaman->update($id_peminjaman, [
                'status' => 'Dikembalikan'
            ]);

            // 3. Kembalikan stok buku (+1) karena buku fisik diserahkan kembali
            $buku = $modelBuku->find($id_buku);
            if ($buku) {
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }

            // 4. Redirect kembali ke dashboard admin denda dengan membawa flashdata sukses
            return redirect()->back()->with('success', 'Pembayaran denda berhasil diterima dan buku otomatis ditandai sebagai dikembalikan.');
        }

        return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
    }

    public function api_docs()
    {
        return view('admin/api_docs', ['title' => 'Dokumentasi API Server']);
    }

}