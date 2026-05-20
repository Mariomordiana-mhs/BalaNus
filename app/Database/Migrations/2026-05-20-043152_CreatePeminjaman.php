<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peminjaman' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Harus sama dengan id_user di tabel users
            ],
            'id_buku' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tgl_pengajuan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tenggat_waktu' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tgl_dikembalikan' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu ACC', 'Dipinjam', 'Selesai'],
                'default'    => 'Menunggu ACC',
            ],
        ]);
        
        $this->forge->addKey('id_peminjaman', true);
        
        // Relasi (Foreign Keys)
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_buku', 'buku', 'id_buku', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('peminjaman');
    }
}