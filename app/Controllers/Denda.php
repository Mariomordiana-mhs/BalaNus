<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Denda extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id_user');
        $today = date('Y-m-d');

        // Mengambil data peminjaman terlambat (baik yang masih dipinjam maupun yang sudah dikembalikan tapi telat)
        $peminjaman_terlambat = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul_buku')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_user', $id_user)
            ->groupStart()
                // KONDISI 1: Masih dipinjam & lewat tenggat hari ini
                ->groupStart()
                    ->where('status', 'Dipinjam')
                    ->where('tenggat_waktu <', $today)
                ->groupEnd()
                // KONDISI 2: Sudah dikembalikan & tgl_dikembalikan melewati tenggat_waktu
                ->orGroupStart()
                    ->where('status', 'Dikembalikan')
                    ->where('tgl_dikembalikan > peminjaman.tenggat_waktu', null, false)
                ->groupEnd()
            ->groupEnd()
            ->get()->getResultArray();

        // Hitung rincian denda untuk masing-masing buku
        $rincian_denda = [];
        $total_denda_belum_bayar = 0;

        foreach ($peminjaman_terlambat as $row) {
            $tenggat = new \DateTime($row['tenggat_waktu']);
            
            if ($row['status'] === 'Dipinjam') {
                // Jika masih dipinjam, hitung keterlambatan sampai hari ini
                $sekarang = new \DateTime($today);
                $selisih = $sekarang->diff($tenggat);
                $hari_terlambat = $selisih->invert ? $selisih->days : 0;
            } else {
                // Jika sudah dikembalikan, hitung keterlambatan berdasarkan tgl_dikembalikan asli
                $kembali = new \DateTime($row['tgl_dikembalikan']);
                $selisih = $kembali->diff($tenggat);
                $hari_terlambat = $selisih->invert ? $selisih->days : 0;
            }

            // Tarif denda per hari (misal: Rp 1.000)
            $tarif_denda = 1000; 
            $nominal_denda = $hari_terlambat * $tarif_denda;

            if ($hari_terlambat > 0) {
                $rincian_denda[] = [
                    'judul_buku'    => $row['judul_buku'],
                    'tenggat_waktu' => $row['tenggat_waktu'],
                    'keterlambatan' => $hari_terlambat,
                    'nominal_denda' => $nominal_denda,
                    'status'        => $row['status'] == 'Dipinjam' ? 'Belum Dikembalikan' : 'Belum Dibayar'
                ];
                
                $total_denda_belum_bayar += $nominal_denda;
            }
        }

        $data = [
            'title' => 'Halaman Denda & Tanggungan',
            'username' => session()->get('username'),
            'rincian_denda' => $rincian_denda,
            'total_denda' => $total_denda_belum_bayar
        ];

        return view('denda', $data);
    }

    public function bayarToken()
    {
        // 1. Ambil ID user dari session (pastikan namanya sesuai dengan saat login, biasanya 'id_user')
        $id_user = session()->get('id_user'); 
        $today = date('Y-m-d');

        // 2. Hubungkan ke database secara langsung (Tanpa Model)
        $db = \Config\Database::connect();

        // 3. Ambil data buku yang terlambat (Logika sama persis dengan fungsi index)
        $peminjaman_terlambat = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul_buku')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_user', $id_user)
            ->groupStart()
                ->groupStart()
                    ->where('status', 'Dipinjam')
                    ->where('tenggat_waktu <', $today)
                ->groupEnd()
                ->orGroupStart()
                    ->where('status', 'Dikembalikan')
                    ->where('tgl_dikembalikan > peminjaman.tenggat_waktu', null, false)
                ->groupEnd()
            ->groupEnd()
            ->get()->getResultArray();

        // 4. Hitung ulang total denda untuk memastikan keakuratannya di backend
        $total_denda = 0;
        foreach ($peminjaman_terlambat as $row) {
            $tenggat = new \DateTime($row['tenggat_waktu']);
            
            if ($row['status'] === 'Dipinjam') {
                $sekarang = new \DateTime($today);
                $selisih = $sekarang->diff($tenggat);
                $hari_terlambat = $selisih->invert ? $selisih->days : 0;
            } else {
                $kembali = new \DateTime($row['tgl_dikembalikan']);
                $selisih = $kembali->diff($tenggat);
                $hari_terlambat = $selisih->invert ? $selisih->days : 0;
            }

            $tarif_denda = 1000;
            if ($hari_terlambat > 0) {
                $total_denda += ($hari_terlambat * $tarif_denda);
            }
        }

        // --- VALIDASI PENTING ---
        if ($total_denda <= 0) {
            return $this->response->setJSON(['error' => 'Tidak ada denda yang harus dibayar.']);
        }

        // 5. Konfigurasi Midtrans
        // JANGAN LUPA GANTI INI DENGAN SERVER KEY SANDBOX ANDA
        \Midtrans\Config::$serverKey = 'Mid-server-BSz7gEW_u5hU-jPQuokeUoA4'; 
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // 6. Susun parameter transaksi ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'DENDA-' . $id_user . '-' . time(), 
                'gross_amount' => $total_denda,
            ],
            'customer_details' => [
                'first_name' => session()->get('username') ?? 'Member BalaNus',
            ]
        ];

        try {
            // 7. Dapatkan token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $this->response->setJSON(['snapToken' => $snapToken]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}