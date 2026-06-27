<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <h2>Dokumentasi RESTful API BalaNus</h2>
    <p>Gunakan API Endpoint di bawah ini untuk mengakses data perpustakaan dari aplikasi luar.</p>
    
    <div style="background: #1e293b; color: #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-family: monospace;">
        <strong>Wajib Header Autentikasi:</strong><br>
        X-API-KEY: BalaNusSecretKey2026
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
            <th style="padding: 10px;">Metode</th>
            <th style="padding: 10px;">Endpoint</th>
            <th style="padding: 10px;">Fungsi</th>
        </tr>
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 10px; font-weight: bold; color: #3b82f6;">GET</td>
            <td style="padding: 10px; font-family: monospace;">/api/books</td>
            <td style="padding: 10px;">Menampilkan seluruh katalog buku.</td>
        </tr>
        <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 10px; font-weight: bold; color: #3b82f6;">GET</td>
            <td style="padding: 10px; font-family: monospace;">/api/books/{isbn}</td>
            <td style="padding: 10px;">Menampilkan detail spesifik satu buku berdasarkan ISBN.</td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; color: #3b82f6;">GET</td>
            <td style="padding: 10px; font-family: monospace;">/api/availability/{id}</td>
            <td style="padding: 10px;">Mengecek sisa stok eksemplar fisik yang berstatus "Tersedia".</td>
        </tr>
    </table>
</div>
<?= $this->endSection(); ?>