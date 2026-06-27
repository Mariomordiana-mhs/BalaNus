<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Eksemplar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_eksemplar' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kode_eksemplar' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tersedia', 'dipinjam', 'hilang', 'rusak'],
                'default'    => 'tersedia',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id_eksemplar', true);
        // Ganti baris yang error sebelumnya dengan baris ini:
        $this->forge->addForeignKey('id_buku', 'buku', 'id_buku', 'CASCADE', 'CASCADE');        
        $this->forge->createTable('eksemplar');
    }

    public function down()
    {
        $this->forge->dropTable('eksemplar');
    }
}