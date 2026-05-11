<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPeminjaman extends Model
{
    // --- 1. PENGATURAN TABEL ---
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id_peminjaman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Kolom yang diizinkan untuk diolah
    protected $allowedFields    = ['id_user', 'id_buku', 'tgl_pengajuan', 'tenggat_waktu', 'tgl_dikembalikan', 'status']; 

    // --- 2. FUNGSI UNTUK DASHBOARD ---
    public function getRekapStatus($id_user)
    {
        return $this->select("
                SUM(CASE WHEN status = 'Menunggu ACC' THEN 1 ELSE 0 END) AS total_menunggu,
                SUM(CASE WHEN status = 'Dipinjam' THEN 1 ELSE 0 END) AS total_dipinjam,
                SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS total_dibaca
            ")
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();
    }

    public function getRiwayatPeminjaman($id_user, $limit = 5)
    {
        return $this->select('peminjaman.id_peminjaman, buku.judul_buku, peminjaman.tgl_pengajuan, peminjaman.tenggat_waktu, peminjaman.tgl_dikembalikan, peminjaman.status')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_user', $id_user)
            ->orderBy('peminjaman.tgl_pengajuan', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    // --- 3. FUNGSI UNTUK HALAMAN DETAIL (INI YANG TADI HILANG) ---
    public function getDetailPeminjaman($id_peminjaman, $id_user)
    {
        return $this->select('peminjaman.*, buku.judul_buku, buku.penulis')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_peminjaman', $id_peminjaman)
            ->where('peminjaman.id_user', $id_user)
            ->first(); 
    }
}