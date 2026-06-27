<?php

namespace App\Models;

use CodeIgniter\Model;

class EksemplarModel extends Model
{
    protected $table            = 'eksemplar';
    protected $primaryKey       = 'id_eksemplar'; 
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_buku', 'kode_eksemplar', 'status']; 

    public function getEksemplarDenganBuku()
    {
        return $this->db->table('eksemplar')
            ->join('buku', 'buku.id_buku = eksemplar.id_buku')
            ->orderBy('eksemplar.id_eksemplar', 'DESC')
            ->get()->getResultArray();
    }
}