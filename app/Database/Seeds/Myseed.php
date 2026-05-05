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
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'username' => 'user1',
                'email'    => 'user1@gmail.com',
                'password' => password_hash('123', PASSWORD_DEFAULT),
                'role'     => 'member',
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}