<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .header-laporan { background: #ffffff; padding: 25px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px; }
    .header-laporan h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 5px; }
    .header-laporan p { color: #64748b; font-size: 14px; }

    .report-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    
    .report-card { background: white; padding: 30px 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); text-align: center; transition: all 0.3s ease; border: 1px solid #f1f5f9; position: relative; overflow: hidden;}
    .report-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #005ce6; transform: scaleX(0); transition: 0.3s; transform-origin: left; }
    .report-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #e2e8f0; }
    .report-card:hover::before { transform: scaleX(1); }
    
    .report-icon { font-size: 45px; margin-bottom: 20px; color: #005ce6; }
    .report-icon.buku { color: #3b82f6; }
    .report-icon.anggota { color: #10b981; }
    .report-icon.transaksi { color: #f59e0b; }
    .report-icon.denda { color: #ef4444; }

    .report-title { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 10px; }
    .report-desc { font-size: 13px; color: #64748b; margin-bottom: 25px; line-height: 1.5; }
    
    .btn-print { background: #f8fafc; color: #0f172a; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 13px;}
    .btn-print:hover { background: #005ce6; color: white; border-color: #005ce6; }

    /* Custom SweetAlert2 Style */
    div:where(.swal2-container) { font-family: 'Poppins', sans-serif !important; }
    .custom-swal-popup { border-radius: 16px !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; padding: 2em !important; }
    .custom-swal-title { font-size: 22px !important; font-weight: 600 !important; color: #1e293b !important; margin-bottom: 5px !important; }
    .custom-swal-button { border-radius: 8px !important; font-weight: 500 !important; padding: 12px 24px !important; font-size: 14px !important; transition: all 0.3s ease !important; border: none !important; }
</style>

<div class="header-laporan">
    <h2>Pusat Laporan</h2>
    <p>Cetak dan unduh rekapitulasi data perpustakaan BalaNus.</p>
</div>

<div class="report-grid">
    <div class="report-card">
        <i class="fa-solid fa-book report-icon buku"></i>
        <h3 class="report-title">Katalog & Stok Buku</h3>
        <p class="report-desc">Rekap seluruh data buku perpustakaan beserta jumlah ketersediaan stok fisiknya.</p>
        <a href="<?= base_url('admin/laporan/cetak/buku') ?>" class="btn-print" onclick="notifikasiCetak(event, this.href)">
            <i class="fa-solid fa-print"></i> Cetak PDF
        </a>
    </div>

    <div class="report-card">
        <i class="fa-solid fa-users report-icon anggota"></i>
        <h3 class="report-title">Data Anggota</h3>
        <p class="report-desc">Daftar lengkap anggota yang terdaftar di sistem beserta informasi kontak dan tanggal bergabung.</p>
        <a href="<?= base_url('admin/laporan/cetak/anggota') ?>" class="btn-print" onclick="notifikasiCetak(event, this.href)">
            <i class="fa-solid fa-print"></i> Cetak PDF
        </a>
    </div>

    <div class="report-card">
        <i class="fa-solid fa-right-left report-icon transaksi"></i>
        <h3 class="report-title">Riwayat Transaksi</h3>
        <p class="report-desc">Data historis aktivitas peminjaman dan pengembalian buku dari waktu ke waktu.</p>
        <a href="<?= base_url('admin/laporan/cetak/transaksi') ?>" class="btn-print" onclick="notifikasiCetak(event, this.href)">
            <i class="fa-solid fa-print"></i> Cetak PDF
        </a>
    </div>

    <div class="report-card">
        <i class="fa-solid fa-money-bill-wave report-icon denda"></i>
        <h3 class="report-title">Rekapitulasi Denda</h3>
        <p class="report-desc">Laporan total penerimaan denda keterlambatan pengembalian buku dari anggota.</p>
        <a href="<?= base_url('admin/laporan/cetak/denda') ?>" class="btn-print" onclick="notifikasiCetak(event, this.href)">
            <i class="fa-solid fa-print"></i> Cetak PDF
        </a>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session()->getFlashdata('info')): ?>
    Swal.fire({
        icon: 'info',
        title: 'Fitur Segera Hadir!',
        text: '<?= session()->getFlashdata('info') ?>',
        confirmButtonColor: '#005ce6',
        customClass: { popup: 'custom-swal-popup', title: 'custom-swal-title', confirmButton: 'custom-swal-button' }
    });
<?php endif; ?>

function notifikasiCetak(event, urlTujuan) {
    event.preventDefault();
    // Membiarkan controller menangani flashdata info, atau arahkan ke library PDF Anda nanti.
    window.location.href = urlTujuan;
}
</script>
<?= $this->endSection(); ?>