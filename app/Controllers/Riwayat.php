<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Riwayat extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $id_user = session()->get('id_user');

        // Ambil data dengan status 'Dikembalikan'
        // Diurutkan berdasarkan kolom asli di database Anda: tgl_dikembalikan
        $riwayat = $db->table('peminjaman')
                      ->select('peminjaman.*, buku.judul_buku')
                      ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                      ->where('id_user', $id_user)
                      ->where('status', 'Dikembalikan') 
                      ->orderBy('tgl_dikembalikan', 'DESC') // Menggunakan tgl_dikembalikan
                      ->get()->getResultArray();

        $data = [
            'title'    => 'Riwayat Baca - BalaNus',
            'riwayat'  => $riwayat,
            'username' => session()->get('username') 
        ];

        return view('riwayat', $data);
    }
}