<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_user' => 3,
                'riwayat' => 'Peminjaman buku Algoritma Dasar',
                'statistik' => '1 buku dipinjam',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_user' => 5,
                'riwayat' => 'Peminjaman buku Basis Data',
                'statistik' => '2 buku dipinjam',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id_user' => 6,
                'riwayat' => 'Peminjaman buku Pemrograman Web',
                'statistik' => '3 buku dipinjam',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('laporan')->insertBatch($data);
    }
}