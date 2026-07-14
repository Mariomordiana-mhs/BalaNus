<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Myseed extends Seeder
{
    public function run()
    {
        // 1. Matikan pengecekan relasi sementara agar tidak error saat reset
        $this->db->disableForeignKeyChecks();

        // 2. Kosongkan semua tabel dari data lama agar tidak duplikat
        // Urutan truncate sebaiknya dari tabel yang bergantung (child) ke tabel utama (parent)
        $this->db->table('laporan')->truncate();
        $this->db->table('peminjaman')->truncate();
        $this->db->table('eksemplar')->truncate();
        $this->db->table('buku')->truncate();
        $this->db->table('users')->truncate();

        // 3. Panggil Seeder secara berurutan (CUKUP SATU KALI SAJA)
        $this->call('UserSeeder');       // Buat user dulu
        $this->call('BukuSeeder');       // Buat buku umum
        $this->call('EksemplarSeeder');  // Buat fisik/eksemplar buku (Butuh ID Buku)
        $this->call('PeminjamanSeeder'); // Buat transaksi (Butuh ID User & ID Eksemplar)
        $this->call('LaporanSeeder');    // Buat laporan (Jika ada)
        $this->call('NotificationSeeder'); // Buat notifikasi (Jika ada)

        // 4. Hidupkan kembali pengecekan relasi
        $this->db->enableForeignKeyChecks();
    }
}