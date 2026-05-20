<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBuku extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id_buku' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => true,
        ],
        'judul_buku' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
        ],
        'penulis' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true,
        ],
        'isbn' => [
            'type'       => 'VARCHAR',
            'constraint' => '20',
            'null'       => true,
        ],
        'kategori' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
            'null'       => true,
        ],
        // INI TAMBAHANNYA: Penerbit dan Cover
        'penerbit' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'null'       => true,
        ],
        'cover' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
            'default'    => 'default.png',
        ],
        // ==================================
        'stok' => [
            'type'       => 'INT',
            'constraint' => 11,
            'default'    => 1,
        ],
    ]);
    $this->forge->addKey('id_buku', true);
    $this->forge->createTable('buku');
    }

    public function down()
    {
        $this->forge->dropTable('buku');
    }
}