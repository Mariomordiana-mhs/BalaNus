<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $allowedFields    = ['username', 'email', 'password', 'role'];
    
    // Aktifkan ini karena di migration kamu ada created_at & updated_at
    protected $useTimestamps    = true; 
}