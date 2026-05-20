<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run()
    {
        $buku = [
            [
                'id_buku'    => 1,
                'judul_buku' => 'Harry Potter: Sorcerer\'s Stone',
                'penulis'    => 'J.K. Rowling',
                'isbn'       => '9786020304639',
                'kategori'   => 'Novel Fiksi',
                'stok'       => 7
            ],
            [
                'id_buku'    => 2,
                'judul_buku' => 'Filosofi Teras',
                'penulis'    => 'Henry Manampiring',
                'isbn'       => '9786024125189',
                'kategori'   => 'Pengembangan Diri',
                'stok'       => 8
            ],
            [
                'id_buku'    => 3,
                'judul_buku' => 'Negeri 5 Menara',
                'penulis'    => 'A. Fuadi',
                'isbn'       => '9789792248616',
                'kategori'   => 'Novel Fiksi',
                'stok'       => 14
            ],
        ];

        // Memasukkan data ke tabel buku
        $this->db->table('buku')->ignore(true)->insertBatch($buku);
    }
}