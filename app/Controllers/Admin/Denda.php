<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Denda extends BaseController
{
    public function index()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        
        // Hanya ambil yang statusnya 'Dipinjam' DAN tanggal hari ini sudah melewati tenggat waktu
        $builder->where('peminjaman.status', 'Dipinjam');
        $builder->where('peminjaman.tenggat_waktu <', date('Y-m-d'));
        $builder->orderBy('peminjaman.tenggat_waktu', 'ASC'); // Urutkan dari yang paling lama menunggak
        
        $data = [
            'title' => 'Data Denda & Keterlambatan - BalaNus',
            'denda' => $builder->get()->getResultArray()
        ];

        return view('admin/denda/index', $data);
    }

    // Fungsi untuk memproses pelunasan denda & pengembalian buku sekaligus
    public function lunas($id_peminjaman)
    {
        $db = \Config\Database::connect();
        $modelPeminjaman = new \App\Models\ModelPeminjaman();
        $modelBuku       = new \App\Models\ModelBuku();

        $peminjaman = $modelPeminjaman->find($id_peminjaman);

        if ($peminjaman) {
            $id_buku = $peminjaman['id_buku'];

            // Ubah status menjadi 'Selesai' karena denda sudah dibayar dan buku dikembalikan
            $modelPeminjaman->update($id_peminjaman, [
                'status' => 'Selesai'
            ]);

            // Kembalikan stok fisik buku (+1)
            $buku = $modelBuku->find($id_buku);
            if ($buku) {
                $modelBuku->update($id_buku, ['stok' => $buku['stok'] + 1]);
            }

            return redirect()->to(base_url('admin/denda'))->with('success', 'Pembayaran denda berhasil diproses dan buku telah dikembalikan.');
        }

        return redirect()->to(base_url('admin/denda'))->with('error', 'Data tidak ditemukan.');
    }
}