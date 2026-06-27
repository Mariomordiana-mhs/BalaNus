<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;

class Anggota extends BaseController
{
    public function index()
    {
        $anggotaModel = new AnggotaModel();
        
        // Mengambil hanya user dengan role 'member'
        $data['anggota'] = $anggotaModel->where('role', 'member')->findAll();
        $data['title'] = 'Kelola Anggota - BalaNus';
        
        return view('admin/anggota/index', $data);
    }

    public function delete($id)
    {
        $model = new AnggotaModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/anggota'))->with('success', 'Anggota berhasil dihapus!');
    }

    public function detail($id_user)
    {
        $db = \Config\Database::connect();
        
        // Sesuaikan 'id_user' dan nama tabel 'users' dengan struktur database Anda
        $anggota = $db->table('users')->where('id_user', $id_user)->get()->getRowArray();

        if (!$anggota) {
            return redirect()->to(base_url('admin/anggota'))->with('error', 'Data anggota tidak ditemukan.');
        }

        $data = [
            'title'   => 'Detail Anggota - BalaNus',
            'anggota' => $anggota
        ];

        return view('admin/anggota/detail', $data);
    }
}