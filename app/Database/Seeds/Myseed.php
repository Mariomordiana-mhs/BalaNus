<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Myseed extends Seeder
{
    public function run()
    {
        // Matikan pengecekan relasi sementara agar tidak error saat reset
        $this->db->disableForeignKeyChecks();

        // Kosongkan data lama agar tidak duplikat
        $this->db->table('peminjaman')->truncate();
        $this->db->table('buku')->truncate();
        $this->db->table('users')->truncate();

        // Panggil ketiga Seeder secara berurutan
        $this->call('UserSeeder');
        $this->call('BukuSeeder');
        $this->call('PeminjamanSeeder');

        // Hidupkan kembali pengecekan relasi
        $this->db->enableForeignKeyChecks();
    }
}