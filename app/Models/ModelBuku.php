<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBuku extends Model
{
    // --- PENGATURAN TABEL ---
    protected $table            = 'buku';      
    protected $primaryKey       = 'id_buku';   
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Kolom yang ada di tabel buku (Sudah ditambah 'cover' dan 'penerbit' untuk kebutuhan API & Upload)
    protected $allowedFields    = ['judul_buku', 'penulis', 'isbn', 'kategori', 'stok', 'cover', 'penerbit'];

    // Konfigurasi standar
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps          = false;
    protected $dateFormat             = 'datetime';


    // --- FUNGSI CUSTOM UNTUK PENCARIAN KATALOG ---
    
    // Method untuk menampilkan semua buku sekaligus filter pencarian di halaman katalog member
    public function getKatalog($keyword = null)
    {
        if ($keyword) {
            return $this->groupStart()
                        ->like('judul_buku', $keyword)
                        ->orLike('penulis', $keyword)
                        ->orLike('isbn', $keyword)
                        ->orLike('kategori', $keyword)
                        ->groupEnd()
                        ->findAll();
        }
        
        // Jika tidak ada kata kunci, tampilkan semua buku
        return $this->findAll();
    }
}