<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run()
    {
        $peminjaman = [
            [
                'id_peminjaman'    => 2,
                'id_user'          => 3,
                'id_buku'          => 2,
                'tgl_pengajuan'    => null,
                'tenggat_waktu'    => '2026-05-08',
                'tgl_dikembalikan' => null,
                'status'           => 'Dipinjam'
            ],
            [
                'id_peminjaman'    => 3,
                'id_user'          => 3,
                'id_buku'          => 3,
                'tgl_pengajuan'    => null,
                'tenggat_waktu'    => null,
                'tgl_dikembalikan' => '2026-05-03',
                'status'           => 'Selesai'
            ],
            [
                'id_peminjaman'    => 13,
                'id_user'          => 3,
                'id_buku'          => 3,
                'tgl_pengajuan'    => '2026-05-11',
                'tenggat_waktu'    => null,
                'tgl_dikembalikan' => null,
                'status'           => 'Menunggu ACC'
            ],
            [
                'id_peminjaman'    => 14,
                'id_user'          => 3,
                'id_buku'          => 2,
                'tgl_pengajuan'    => '2026-05-11',
                'tenggat_waktu'    => null,
                'tgl_dikembalikan' => null,
                'status'           => 'Menunggu ACC'
            ],
        ];

        // Memasukkan data ke tabel peminjaman
        $this->db->table('peminjaman')->ignore(true)->insertBatch($peminjaman);
    }
}