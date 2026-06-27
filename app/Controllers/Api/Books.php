<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Books extends ResourceController
{
    protected $format = 'json';

    // GET /api/books (Daftar semua buku)
    public function index()
    {
        $db = \Config\Database::connect();
        $buku = $db->table('buku')->get()->getResultArray();
        return $this->respond(['status' => true, 'data' => $buku], 200);
    }

    // GET /api/books/{isbn} (Detail buku berdasarkan ISBN)
    public function show($isbn = null)
    {
        $db = \Config\Database::connect();
        $buku = $db->table('buku')->where('isbn', $isbn)->get()->getRowArray();

        if ($buku) return $this->respond(['status' => true, 'data' => $buku], 200);
        return $this->failNotFound('Buku dengan ISBN tersebut tidak ditemukan.');
    }

    // GET /api/availability/{id} (Cek ketersediaan eksemplar)
    public function availability($id = null)
    {
        $db = \Config\Database::connect();
        $stok = $db->table('eksemplar')->where('id_buku', $id)->where('status', 'Tersedia')->countAllResults();

        return $this->respond(['status' => true, 'id_buku' => $id, 'stok_tersedia' => $stok], 200);
    }
}