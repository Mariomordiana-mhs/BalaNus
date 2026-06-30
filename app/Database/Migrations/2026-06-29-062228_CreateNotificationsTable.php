<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur kolom untuk tabel 'notifications'
        $this->forge->addField([
            'id_notifikasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'no_wa' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'pesan' => [
                'type' => 'TEXT',
            ],
            'jenis_notifikasi' => [
                'type'       => 'ENUM',
                'constraint' => ['Peminjaman Disetujui', 'H-3 Jatuh Tempo', 'H-1 Jatuh Tempo', 'Denda Terbentuk', 'Registrasi'],
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Terkirim',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Menjadikan id_notifikasi sebagai Primary Key
        $this->forge->addKey('id_notifikasi', true);
        
        // Membuat tabelnya di database
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        // Menghapus tabel jika kita melakukan perintah 'php spark migrate:rollback'
        $this->forge->dropTable('notifications');
    }
}