<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Peminjaman extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $id_user = session()->get('id_user');

        // Ambil data buku yang SEDANG DIPINJAM
        $dipinjam = $db->table('peminjaman')
                       ->select('peminjaman.*, buku.judul_buku')
                       ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                       ->where('id_user', $id_user)
                       ->where('status', 'Dipinjam')
                       ->get()->getResultArray();

        // Ambil data buku yang MASIH ANTRE (Menunggu ACC)
        $antrean = $db->table('peminjaman')
                      ->select('peminjaman.*, buku.judul_buku')
                      ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                      ->where('id_user', $id_user)
                      ->where('status', 'Menunggu ACC')
                      ->get()->getResultArray();

        $data = [
            'title'    => 'Peminjaman & Antrean - BalaNus',
            'dipinjam' => $dipinjam,
            'antrean'  => $antrean,
            // PENTING: Kirim username ke view agar nama di sidebar muncul (tidak tertulis Member)
            'username' => session()->get('username') 
        ];

        return view('peminjaman', $data);
    }

    // Fungsi untuk membatalkan antrean dari tombol SweetAlert di view
    public function batal($id_peminjaman)
    {
        $db = \Config\Database::connect();
        // Hapus pengajuan atau ubah status jadi dibatalkan
        $db->table('peminjaman')->where('id_peminjaman', $id_peminjaman)->delete();
        
        return redirect()->to(base_url('peminjaman'))->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}