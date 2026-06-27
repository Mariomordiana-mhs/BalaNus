<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EksemplarModel; 
use App\Models\ModelBuku; // <-- SUDAH DIPERBAIKI: Menggunakan ModelBuku

class Eksemplar extends BaseController
{
    public function index()
    {
        $eksemplarModel = new EksemplarModel();
        
        // Mengambil data eksemplar dan menggabungkan dengan judul buku
        $data['eksemplar'] = $eksemplarModel->select('eksemplar.*, buku.judul_buku')
                                            ->join('buku', 'buku.id_buku = eksemplar.id_buku')
                                            ->findAll();
        
        return view('admin/eksemplar/index', $data);
    }

    public function create()
    {
        // <-- SUDAH DIPERBAIKI: Memanggil ModelBuku yang benar
        $bukuModel = new ModelBuku(); 
        $data['buku'] = $bukuModel->findAll();
        
        return view('admin/eksemplar/create', $data);
    }

    public function store()
    {
        $model = new EksemplarModel();
        
        $model->save([
            'id_buku'        => $this->request->getPost('id_buku'),
            'kode_eksemplar' => $this->request->getPost('kode_eksemplar'),
            'status'         => 'Tersedia',
            'kondisi'        => $this->request->getPost('kondisi')
        ]);

        return redirect()->to(base_url('admin/eksemplar'))->with('success', 'Eksemplar berhasil ditambahkan!');
    }

    public function delete($id)
    {
        $model = new EksemplarModel();
        $model->delete($id);
        
        return redirect()->to(base_url('admin/eksemplar'))->with('success', 'Eksemplar dihapus!');
    }

    // --- FUNGSI UNTUK MENAMPILKAN HALAMAN EDIT ---
    public function edit($id_eksemplar)
    {
        $db = \Config\Database::connect();
        
        // Ambil data eksemplar spesifik berdasarkan ID
        $eksemplar = $db->table('eksemplar')->where('id_eksemplar', $id_eksemplar)->get()->getRowArray();
        
        // Jika ID tidak ditemukan, kembalikan ke halaman index
        if (!$eksemplar) {
            return redirect()->to(base_url('admin/eksemplar'))->with('error', 'Data eksemplar tidak ditemukan.');
        }

        // Ambil semua data buku untuk dropdown
        $buku = $db->table('buku')->get()->getResultArray();

        $data = [
            'title'     => 'Edit Eksemplar - BalaNus',
            'eksemplar' => $eksemplar,
            'buku'      => $buku
        ];

        return view('admin/eksemplar/edit', $data);
    }

    // --- FUNGSI UNTUK MENYIMPAN PERUBAHAN KE DATABASE ---
    public function update($id_eksemplar)
    {
        $db = \Config\Database::connect();
        
        $data = [
            'id_buku'        => $this->request->getPost('id_buku'),
            'kode_eksemplar' => $this->request->getPost('kode_eksemplar'),
            'kondisi'        => $this->request->getPost('kondisi'),
            'status'         => $this->request->getPost('status') // Memungkinkan admin mengubah status
        ];

        $db->table('eksemplar')->where('id_eksemplar', $id_eksemplar)->update($data);

        // Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->to(base_url('admin/eksemplar'))->with('success', 'Data eksemplar berhasil diperbarui.');
    }
}