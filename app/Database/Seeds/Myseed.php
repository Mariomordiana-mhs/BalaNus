<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Myseed extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email'    => 'admin@gmail.com',
                'no_telp'  => '081111111111',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'username' => '',
                'email'    => 'user1@gmail.com',
                'no_telp'  => '082222222222', 
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role'     => 'member',
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}