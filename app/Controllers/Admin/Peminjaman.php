<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelPeminjaman;

class Peminjaman extends BaseController
{
    public function index()
    {
        // Menggunakan Query Builder untuk Join 3 Tabel (Peminjaman, Buku, Users)
        $db      = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        $builder->orderBy('peminjaman.tgl_pengajuan', 'DESC'); // Urutkan dari yang terbaru
        
        $data = [
            'title'      => 'Data Peminjaman - BalaNus',
            'peminjaman' => $builder->get()->getResultArray()
        ];

        return view('admin/peminjaman/index', $data);
    }
}