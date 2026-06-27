<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Laporan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan Perpustakaan - BalaNus'
        ];

        return view('admin/laporan/index', $data);
    }

    // Fungsi placeholder untuk proses cetak
    public function cetak($jenis)
    {
        // Nantinya di sini Anda bisa menambahkan library seperti Dompdf / TCPDF
        // Untuk sekarang, kita berikan notifikasi bahwa fitur sedang disiapkan
        return redirect()->to(base_url('admin/laporan'))->with('info', 'Fitur cetak laporan ' . strtoupper($jenis) . ' sedang dalam tahap pengembangan.');
    }
}