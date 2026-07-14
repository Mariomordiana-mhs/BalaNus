<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\WahaWhatsapp;

class ReminderController extends BaseController
{
    protected $db;
    protected $wa;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->wa = new WahaWhatsapp();
    }

    // Fungsi utama untuk memproses pengingat (bisa dipicu lewat URL atau Cron Job)
    public function proses()
    {
        $this->prosesH3();
        $this->prosesH1();
        $this->prosesDendaTerbentuk();

        return "Proses pengiriman pengingat selesai! Silakan cek tabel 'notifications' untuk melihat audit trail.";
    }

    // 1. Logika untuk H-3 Jatuh Tempo
    private function prosesH3()
    {
        // Cari peminjaman berstatus 'Dipinjam' yang jatuh temponya persis 3 hari lagi
        $listH3 = $this->db->table('peminjaman')
            ->select('peminjaman.*, users.username, users.no_telp, buku.judul_buku')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.status', 'Dipinjam')
            ->where('DATE(peminjaman.tenggat_waktu) = CURDATE() + INTERVAL 3 DAY')
            ->get()
            ->getResultArray();

        foreach ($listH3 as $row) {
            $pesan = "Halo *{$row['username']}* 👋\n\n";
            $pesan .= "Mengingatkan bahwa batas waktu peminjaman buku Anda tersisa *3 hari lagi*.\n\n";
            $pesan .= "📖 *Judul Buku:* {$row['judul_buku']}\n";
            $pesan .= "📅 *Batas Kembali:* " . date('d-m-Y', strtotime($row['tenggat_waktu'])) . "\n\n";
            $pesan .= "Harap mengembalikan buku tepat waktu untuk menghindari denda ya. Terima kasih! ✨";

            $this->wa->sendText($row['no_telp'], $pesan, 'H-3 Jatuh Tempo');
        }
    }

    // 2. Logika untuk H-1 Jatuh Tempo
    private function prosesH1()
    {
        // Cari peminjaman berstatus 'Dipinjam' yang jatuh temponya besok (1 hari lagi)
        $listH1 = $this->db->table('peminjaman')
            ->select('peminjaman.*, users.username, users.no_telp, buku.judul_buku')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.status', 'Dipinjam')
            ->where('DATE(peminjaman.tenggat_waktu) = CURDATE() + INTERVAL 1 DAY')
            ->get()
            ->getResultArray();

        foreach ($listH1 as $row) {
            $pesan = "⚠️ *PENGINGAT H-1 JATUH TEMPO* ⚠️\n\n";
            $pesan .= "Halo *{$row['username']}*, *BESOK* adalah batas akhir pengembalian buku Anda.\n\n";
            $pesan .= "📖 *Judul Buku:* {$row['judul_buku']}\n";
            $pesan .= "📅 *Batas Kembali:* " . date('d-m-Y', strtotime($row['tenggat_waktu'])) . "\n\n";
            $pesan .= "Mohon segera kembalikan ke perpustakaan sebelum terkena denda keterlambatan.";

            $this->wa->sendText($row['no_telp'], $pesan, 'H-1 Jatuh Tempo');
        }
    }

    // 3. Logika untuk Denda Terbentuk (Sudah Lewat Tanggal Kembali)
    private function prosesDendaTerbentuk()
    {
        // Cari peminjaman berstatus 'Dipinjam' yang tanggal kembalinya sudah lewat dari hari ini
        $listOverdue = $this->db->table('peminjaman')
            ->select('peminjaman.*, users.username, users.no_telp, buku.judul_buku')
            ->join('users', 'users.id_user = peminjaman.id_user')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.status', 'Dipinjam')
            ->where('DATE(peminjaman.tenggat_waktu) < CURDATE()')
            ->get()
            ->getResultArray();

        foreach ($listOverdue as $row) {
            // Hitung selisih hari terlambat
            $tglKembali = date_create($row['tenggat_waktu']);
            $tglSekarang = date_create(date('Y-m-d'));
            $selisih = date_diff($tglKembali, $tglSekarang)->days;

            // Simulasi tarif denda per hari, misalnya Rp 1.000 (Sesuaikan dengan aturan sistemmu)
            $tarifDenda = 1000; 
            $totalDenda = $selisih * $tarifDenda;

            $pesan = "🚨 *PERINGATAN: TERLAMBAT MENGEMBALIKAN BUKU* 🚨\n\n";
            $pesan .= "Halo *{$row['username']}*, Anda telah melewati batas waktu pengembalian buku!\n\n";
            $pesan .= "📖 *Judul Buku:* {$row['judul_buku']}\n";
            $pesan .= "📅 *Harusnya Kembali:* " . date('d-m-Y', strtotime($row['tenggat_waktu'])) . "\n";
            $pesan .= "⏱️ *Keterlambatan:* $selisih Hari\n";
            $pesan .= "💰 *Denda Akumulasi:* Rp " . number_format($totalDenda, 0, ',', '.') . "\n\n";
            $pesan .= "Harap segera mengembalikan buku ke perpustakaan dan menyelesaikan administrasi denda Anda.";

            $this->wa->sendText($row['no_telp'], $pesan, 'Denda Terbentuk');
        }
    }
    public function paymentDenda
}