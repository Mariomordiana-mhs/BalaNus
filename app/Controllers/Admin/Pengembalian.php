<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Pengembalian extends BaseController
{
    public function index()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        
        // Hanya ambil data yang statusnya 'Dipinjam' atau sudah 'Dikembalikan'/'Selesai'
        $builder->groupStart()
                ->where('peminjaman.status', 'Dipinjam')
                ->orWhere('peminjaman.status', 'Selesai')
                ->orWhere('peminjaman.status', 'Dikembalikan')
                ->groupEnd();
                
        $builder->orderBy('peminjaman.tgl_pengajuan', 'DESC');
        
        $data = [
            'title'        => 'Data Pengembalian - BalaNus',
            'pengembalian' => $builder->get()->getResultArray()
        ];

        return view('admin/pengembalian/index', $data);
    }

    // Fungsi untuk memproses pengembalian buku
    public function proses($id_peminjaman)
    {
        $db = \Config\Database::connect();
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku       = new \App\Models\ModelBuku();

        // 1. Cari data peminjamannya
        $peminjaman = $modelPeminjaman->find($id_peminjaman);

        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];

            // 2. Ubah status menjadi 'Dikembalikan' (Selesai)
            // Jika Anda punya kolom tgl_dikembalikan di database, Anda bisa menambahkannya di sini
            $modelPeminjaman->update($id_peminjaman, [
                'status' => 'Dikembalikan'
            ]);

            // 3. Kembalikan stok fisik buku (+1)
            $buku = $modelBuku->find($id_buku);
            if ($buku) {
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }

            return redirect()->to(base_url('admin/pengembalian'))->with('success', 'Buku berhasil dikembalikan dan stok telah bertambah otomatis.');
        }

        return redirect()->to(base_url('admin/pengembalian'))->with('error', 'Data peminjaman tidak ditemukan.');
    }
}