<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Data dummy untuk dimasukkan ke tabel notifications
        $data = [
            [
                'no_wa'            => '6281234567890',
                'pesan'            => 'Halo, ini adalah pesan percobaan (dummy) dari Seeder.',
                'jenis_notifikasi' => 'Registrasi',
                'status'           => 'Terkirim',
                'created_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'no_wa'            => '6289876543210',
                'pesan'            => 'Peringatan: Buku Harry Potter Anda sudah memasuki H-1 Jatuh Tempo.',
                'jenis_notifikasi' => 'H-1 Jatuh Tempo',
                'status'           => 'Gagal (Error cURL)', // Contoh jika simulasi gagal
                'created_at'       => date('Y-m-d H:i:s'),
            ]
        ];

        // Eksekusi insert data ke dalam tabel
        $this->db->table('notifications')->insertBatch($data);
    }
}