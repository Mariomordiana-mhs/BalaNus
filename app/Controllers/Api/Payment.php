<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use Midtrans\Config;
use Midtrans\Notification;

class Payment extends BaseController
{
    public function callback()
    {
        // 1. Inisialisasi Kredensial Server Key
        Config::$serverKey    = getenv('MIDTRANS_SERVER_KEY'); //isi serverkey disini
        Config::$isProduction = false;

        try {
            // 2. Tangkap object notifikasi dari Midtrans
            $notif = new Notification();
            
            $transaction_status = $notif->transaction_status;
            $order_id           = $notif->order_id; // Contoh format: DENDA-18-171983021
            
            // Pecah string Order ID untuk mengambil id_peminjaman asli
            $parts         = explode('-', $order_id);
            $id_peminjaman = $parts[1]; // Mengambil angka 18

            // 3. Evaluasi Status Transaksi
            if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
                
                $db = \Config\Database::connect();
                
                // Cari data peminjaman terkait
                $peminjaman = $db->table('peminjaman')->where('id_peminjaman', $id_peminjaman)->get()->getRowArray();

                if ($peminjaman && $peminjaman['status'] !== 'Selesai') {
                    
                    // Transaksi Database (Gunakan Trans/Rollback demi keamanan data ganda)
                    $db->transStart();

                    // A. Update status peminjaman menjadi 'Selesai' (Buku kembali & denda lunas)
                    $db->table('peminjaman')
                       ->where('id_peminjaman', $id_peminjaman)
                       ->update(['status' => 'Selesai']);

                    // B. Kembalikan stok fisik buku (+1) ke tabel buku
                    $id_buku = $peminjaman['id_buku'];
                    $buku    = $db->table('buku')->where('id_buku', $id_buku)->get()->getRowArray();
                    
                    if ($buku) {
                        $db->table('buku')
                           ->where('id_buku', $id_buku)
                           ->update(['stok' => $buku['stok'] + 1]);
                    }

                    $db->transComplete();
                }
            }

            // Beri respon HTTP 200 OK agar Midtrans tahu data sudah diterima dengan baik
            return $this->response->setStatusCode(200)->setBody('Notification Handled Successfully');

        } catch (\Exception $e) {
            // Jika ada error internal, kirim status 500
            return $this->response->setStatusCode(500)->setBody('Webhook Error: ' . $e->getMessage());
        }
    }
}