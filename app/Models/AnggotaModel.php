<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table            = 'users'; // Sesuaikan dengan nama tabel anggota Anda
    protected $primaryKey       = 'id_user';
    protected $allowedFields    = ['username', 'email', 'role', 'created_at']; 
}