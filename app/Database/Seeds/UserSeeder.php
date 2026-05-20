<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id_user'    => 1,
                'username'   => 'admin',
                'email'      => 'admin@gmail.com',
                'no_telp'    => '081111111111',
                'password'   => '$2y$10$IeYJhrx3vXnKULqJLj98ROG1Z.VTFX5GpFdG8WkwsrYg7y3Rda6Za',
                'role'       => 'admin',
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id_user'    => 3,
                'username'   => 'yunus',
                'email'      => 'yunus@gmail.com',
                'no_telp'    => '',
                'password'   => '$2y$10$OSwsoPHHnzXbGhfr99G7Ye1Z.LnFMDU5EECWFADHi12rCXz8uaPqK',
                'role'       => 'member',
                'created_at' => '2026-05-07 00:19:29',
                'updated_at' => '2026-05-07 00:19:29'
            ],
            [
                'id_user'    => 5,
                'username'   => 'apis',
                'email'      => 'apis@gmail.com',
                'no_telp'    => '082313691389',
                'password'   => '$2y$10$/OODaEOrD.ZF.0XceJ.41e3gYZlhCVg40dtu4xf7HGoW/sS5NCcCO',
                'role'       => 'member',
                'created_at' => '2026-05-07 02:29:48',
                'updated_at' => '2026-05-07 02:29:48'
            ],
            [
                'id_user'    => 6,
                'username'   => 'amir',
                'email'      => 'amir@gmail.com',
                'no_telp'    => '0812196211212',
                'password'   => '$2y$10$Da4SBa38e46UWxTDjOJmLuILS3dacg34ysZAVVhxAqiziVleT6gTa',
                'role'       => 'member',
                'created_at' => '2026-05-07 02:30:32',
                'updated_at' => '2026-05-07 02:30:32'
            ],
        ];

        // Memasukkan data ke tabel users (ignore untuk menghindari error duplikat jika dijalankan 2x)
        $this->db->table('users')->ignore(true)->insertBatch($users);
    }
}