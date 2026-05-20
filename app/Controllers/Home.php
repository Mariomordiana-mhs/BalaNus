<?php

namespace App\Controllers;

use App\Models\ModelPeminjaman; 

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

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

        // TAMBAHAN: Mengambil data pengajuan yang berstatus 'Menunggu ACC' secara live dari database
        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.status', 'Menunggu ACC'); // Ambil yang butuh tindakan ACC saja
        $builder->orderBy('peminjaman.tgl_pengajuan', 'ASC');
        $peminjaman_baru = $builder->get()->getResultArray();

        // Mengirimkan variabel $peminjaman_baru ke view dashboard admin
        $data = [
            'title' => 'Dashboard Admin - BalaNus',
            'peminjaman_baru' => $peminjaman_baru
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
        
        // Update status menjadi 'Dipinjam' dan set tenggat waktu pengembalian (+7 hari)
        $modelPeminjaman->update($id_peminjaman, [
            'status'        => 'Dipinjam',
            'tenggat_waktu' => date('Y-m-d', strtotime('+7 days'))
        ]);

        return redirect()->to('/admin')->with('success', 'Peminjaman berhasil disetujui (ACC)!');
    }

    // Fungsi Menolak Pengajuan
    public function tolak_peminjaman($id_peminjaman)
    {
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku = new \App\Models\ModelBuku();

        $peminjaman = $modelPeminjaman->find($id_peminjaman);
        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];
            $buku = $modelBuku->find($id_buku);
            
            if ($buku) {
                // Kembalikan stok fisik buku (+1) karena pengajuan tidak jadi diproses (ditolak)
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }
            
            // Hapus pengajuan dari list peminjaman
            $modelPeminjaman->delete($id_peminjaman);
        }

        return redirect()->to('/admin')->with('error', 'Pengajuan peminjaman telah ditolak.');
    }
}