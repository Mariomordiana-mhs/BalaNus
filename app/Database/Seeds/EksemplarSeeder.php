<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EksemplarSeeder extends Seeder
{
    public function run()
    {

        $this->call('BukuSeeder');
        $this->call('EksemplarSeeder');

        $data = [
            [
                'id_buku'        => 1, // Pastikan id_buku 1 ada di tabel buku
                'kode_eksemplar' => 'B001-01',
                'status'         => 'tersedia',
            ],
            [
                'id_buku'        => 1, 
                'kode_eksemplar' => 'B001-02',
                'status'         => 'dipinjam',
            ],
            // Silakan tambahkan lebih banyak data sesuai kebutuhan
        ];

        $this->db->table('eksemplar')->insertBatch($data);
    }
}