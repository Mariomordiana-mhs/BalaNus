<?php

namespace App\Libraries;

use CodeIgniter\Database\Config;

class WahaWhatsapp
{
    protected $client;
    protected $baseUrl;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
        $this->baseUrl = ''; // URL dari screenshot dashboard WAHA kamu
        $this->session = 'default';
        $this->db = \Config\Database::connect(); // Panggil koneksi database
    }

    // Tambahkan parameter $jenis untuk mencatat jenis notifikasi
    public function sendText($nomorWA, $message, $jenis = 'Registrasi')
    {
        // 1. Pastikan format nomor benar (628xxx)
        if (substr($nomorWA, 0, 1) === '0') {
            $nomorWA = '62' . substr($nomorWA, 1);
        }
        if (substr($nomorWA, 0, 1) === '+') {
            $nomorWA = substr($nomorWA, 1);
        }

        // 2. Format ChatId sesuai spesifikasi (tambah @c.us)
        $chatId = $nomorWA . '@c.us';

        // 3. Proses Kirim ke Server WAHA
        try {
            //endpoint &  metode postnya
            $response = $this->client->post($this->baseUrl . '/api/sendText', [
                'verify'  => false,
                'timeout' => 30,
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Api-Key'    => '' // Buka komentar ini jika pakai API Key
                ],
                'json' => [
                    'chatId'  => $chatId, // Sesuai spesifikasi payload
                    'text'    => $message,
                    'session' => $this->session
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $statusKirim = ($statusCode == 201 || $statusCode == 200) ? 'Berhasil' : 'Gagal (Kode: ' . $statusCode . ')';

        } catch (\Exception $e) {
            $statusKirim = 'Gagal (Error cURL)';
        }

        // 4. AUDIT TRAIL: Simpan ke tabel notifications
        $this->db->table('notifications')->insert([
            'no_wa'            => $nomorWA,
            'pesan'            => $message,
            'jenis_notifikasi' => $jenis,
            'status'           => $statusKirim,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        return true;
    }
}