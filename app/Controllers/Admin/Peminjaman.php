<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelPeminjaman; // Pastikan nama modelmu sesuai
use App\Libraries\WahaWhatsapp; 

class Peminjaman extends BaseController
{
    public function index()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->orderBy('peminjaman.tgl_pengajuan', 'DESC'); 
        
        $data = [
            'title'      => 'Data Peminjaman - BalaNus',
            'peminjaman' => $builder->get()->getResultArray()
        ];

        return view('admin/peminjaman/index', $data);
    }

    // --- FUNGSI ACC (KIRIM WHATSAPP) ---
    public function acc($id_peminjaman)
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil data peminjaman
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username, users.no_telp'); // Ambil no_telp
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.id_peminjaman', $id_peminjaman); 
        $dataPinjam = $builder->get()->getRowArray();

        if (!$dataPinjam) {
            return redirect()->to('/admin/peminjaman')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // 2. Set Tenggat Waktu (Misal 7 hari dari sekarang)
        $tenggat_waktu = date('Y-m-d', strtotime('+7 days'));

        // 3. Update status menjadi 'dipinjam' dan set tenggat waktu
        $db->table('peminjaman')
           ->where('id_peminjaman', $id_peminjaman)
           ->update([
               'status' => 'dipinjam',
               'tenggat_waktu' => $tenggat_waktu
            ]); 

        // 4. Kirim Notifikasi WhatsApp
        $wa = new WahaWhatsapp();
        
        $nomorWA   = $dataPinjam['no_telp'];
        $namaUser  = $dataPinjam['username'];
        $judulBuku = $dataPinjam['judul_buku'];

        $pesan = "Halo *$namaUser*,\n\n";
        $pesan .= "Pengajuan peminjaman buku kamu telah *DISETUJUI* oleh Admin Perpustakaan BalaNus. ✅\n\n";
        $pesan .= "📖 *Detail Peminjaman:*\n";
        $pesan .= "• Judul Buku: $judulBuku\n";
        $pesan .= "• Batas Waktu: " . date('d M Y', strtotime($tenggat_waktu)) . "\n\n";
        $pesan .= "Silakan ambil buku fisik kamu di meja petugas perpustakaan ya. Terima kasih!";

        // Eksekusi pengiriman pesan
        $wa->sendText($nomorWA, $pesan);

        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman disetujui & Notifikasi WhatsApp terkirim!');
    }

    // --- FUNGSI TOLAK (OPSIONAL: KIRIM WHATSAPP JUGA) ---
    public function tolak($id_peminjaman)
    {
        $db = \Config\Database::connect();
        
        // Ambil data untuk WA (Opsional)
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username, users.no_telp'); 
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->where('peminjaman.id_peminjaman', $id_peminjaman); 
        $dataPinjam = $builder->get()->getRowArray();

        // Update status menjadi 'ditolak'
        $db->table('peminjaman')
           ->where('id_peminjaman', $id_peminjaman)
           ->update(['status' => 'ditolak']); 

        // Kirim Notifikasi WhatsApp untuk penolakan
        if ($dataPinjam) {
            $wa = new WahaWhatsapp();
            $nomorWA   = $dataPinjam['no_telp'];
            $namaUser  = $dataPinjam['username'];
            $judulBuku = $dataPinjam['judul_buku'];

            $pesan = "Halo *$namaUser*,\n\n";
            $pesan .= "Mohon maaf, pengajuan peminjaman buku untuk judul *$judulBuku* telah *DITOLAK* oleh Admin. ❌\n\n";
            $pesan .= "Hal ini bisa terjadi karena ketersediaan stok buku sedang kosong atau ada hal lainnya. Silakan hubungi petugas perpustakaan untuk info lebih lanjut.";

            $wa->sendText($nomorWA, $pesan);
        }

        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman ditolak & Notifikasi WA terkirim!');
    }
}