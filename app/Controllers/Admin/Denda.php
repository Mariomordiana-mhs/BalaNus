<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Denda extends BaseController
{
    // Menampilkan halaman tabel Data Keterlambatan & Denda (Untuk Admin)
    public function index()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('peminjaman');
        $builder->select('peminjaman.*, buku.judul_buku, users.username');
        $builder->join('buku', 'buku.id_buku = peminjaman.id_buku');
        $builder->join('users', 'users.id_user = peminjaman.id_user');
        
        // Admin melihat SEMUA member yang terlambat (bukan berdasarkan session user)
        $builder->where('peminjaman.status', 'Dipinjam');
        $builder->where('peminjaman.tenggat_waktu <', date('Y-m-d'));
        $builder->orderBy('peminjaman.tenggat_waktu', 'ASC');
        
        $data = [
            'title' => 'Data Keterlambatan & Denda',
            'denda' => $builder->get()->getResultArray()
        ];

        // Pastikan ini mengarah ke view admin yang ada di screenshot kamu
        return view('admin/denda/index', $data); 
    }

    // Fungsi saat Admin klik tombol "Ya, Lunasi" di popup SweetAlert
    public function lunas($id_peminjaman)
    {
        $db = \Config\Database::connect();
        
        // 1. Cari data peminjamannya
        $peminjaman = $db->table('peminjaman')->where('id_peminjaman', $id_peminjaman)->get()->getRowArray();

        if ($peminjaman) {
            $db->transStart();

            // 2. Ubah status menjadi Selesai (Dianggap lunas dibayar tunai ke admin)
            $db->table('peminjaman')->where('id_peminjaman', $id_peminjaman)->update(['status' => 'Selesai']);

            // 3. Kembalikan stok fisik buku (+1)
            $id_buku = $peminjaman['id_buku'];
            $buku    = $db->table('buku')->where('id_buku', $id_buku)->get()->getRowArray();
            
            if ($buku) {
                $db->table('buku')->where('id_buku', $id_buku)->update(['stok' => $buku['stok'] + 1]);
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to(base_url('admin/denda'))->with('error', 'Gagal memproses pelunasan.');
            }
        }

        // Kembali ke halaman denda admin dengan pesan sukses
        return redirect()->to(base_url('admin/denda'))->with('success', 'Pembayaran denda berhasil diterima dan buku telah dikembalikan.');
    }

    public function bayarToken()
{
    // Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = 'MIDTRANS_SERVER_KEY';
    \Midtrans\Config::$isProduction = false; // false = mode sandbox/testing
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Ambil id_user dan hitung total denda (sama seperti logika sebelumnya)
    $total_denda = 37000; // Contoh hasil perhitungan

    $params = [
        'transaction_details' => [
            'order_id' => 'DENDA-' . time(), // ID Transaksi Unik
            'gross_amount' => $total_denda,
        ],
        'customer_details' => [
            'first_name' => session()->get('username'),
        ]
    ];

    // Minta token ke Midtrans
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    // Kembalikan token dalam format JSON ke View
    return $this->response->setJSON(['snapToken' => $snapToken]);
}
}