<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin-bottom: 30px; }
    .table-container h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
    
    table { width: 100%; border-collapse: collapse; }
    th { padding: 16px 15px; background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    td { padding: 16px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; vertical-align: middle; transition: background-color 0.2s; }
    tr:hover td { background-color: #fcf4f4; } /* Efek hover kemerahan khusus halaman denda */
    
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .fw-bold { font-weight: 600; color: #0f172a; }

    .badge-late { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #fee2e2; color: #dc2626; display: inline-block; }
    .text-fine { color: #dc2626; font-weight: 700; font-size: 15px; }

    .btn-action { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
    .btn-pay { background: #10b981; color: white; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
    .btn-pay:hover { background: #059669; transform: translateY(-1px); }
    
    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 40px; margin-bottom: 15px; color: #cbd5e1; }

    /* Custom SweetAlert2 Style */
    div:where(.swal2-container) { font-family: 'Poppins', sans-serif !important; }
    .custom-swal-popup { border-radius: 16px !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; padding: 2em !important; }
    .custom-swal-title { font-size: 22px !important; font-weight: 600 !important; color: #1e293b !important; margin-bottom: 5px !important; }
    .custom-swal-button { border-radius: 8px !important; font-weight: 500 !important; padding: 12px 24px !important; font-size: 14px !important; transition: all 0.3s ease !important; border: none !important; }
</style>

<div class="table-container">
    <h2>Data Keterlambatan & Denda</h2>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Peminjam</th>
                    <th width="25%">Judul Buku</th>
                    <th width="15%">Tenggat Waktu</th>
                    <th width="10%" class="text-center">Terlambat</th>
                    <th width="15%" class="text-right">Total Denda</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($denda)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-face-smile"></i>
                                <p>Bagus! Tidak ada anggota yang terlambat mengembalikan buku.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                        $i = 1; 
                        $tarif_denda_per_hari = 1000; // Rp 1.000 per hari
                        foreach($denda as $d): 
                            // Hitung selisih hari
                            $tenggat = strtotime($d['tenggat_waktu']);
                            $sekarang = strtotime(date('Y-m-d'));
                            $selisih_detik = $sekarang - $tenggat;
                            $hari_terlambat = floor($selisih_detik / (60 * 60 * 24));
                            $total_denda = $hari_terlambat * $tarif_denda_per_hari;
                    ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><span class="fw-bold"><?= esc(ucwords($d['username'])) ?></span></td>
                        <td><?= esc($d['judul_buku']) ?></td>
                        <td>
                            <span style="color: #64748b;">
                                <i class="fa-regular fa-calendar" style="margin-right: 5px;"></i> <?= date('d M Y', $tenggat) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge-late"><?= $hari_terlambat ?> Hari</span>
                        </td>
                        <td class="text-right">
                            <span class="text-fine">Rp <?= number_format($total_denda, 0, ',', '.') ?></span>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/denda/lunas/' . $d['id_peminjaman']) ?>" class="btn-action btn-pay" onclick="konfirmasiPelunasan(event, this.href, '<?= esc($d['username']) ?>', '<?= number_format($total_denda, 0, ',', '.') ?>')">
                                <i class="fa-solid fa-hand-holding-dollar"></i> Lunasi
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('success') ?>',
        confirmButtonColor: '#10b981',
        customClass: { popup: 'custom-swal-popup', title: 'custom-swal-title', confirmButton: 'custom-swal-button' }
    });
<?php endif; ?>

function konfirmasiPelunasan(event, urlTujuan, nama, nominal) {
    event.preventDefault();

    Swal.fire({
        title: 'Terima Pembayaran?',
        html: `<p style="color: #64748b; font-size: 14px;">Konfirmasi pembayaran denda atas nama <b>${nama}</b> sebesar <b style="color:#dc2626;">Rp ${nominal}</b>.<br><br>Buku juga akan otomatis ditandai selesai/dikembalikan.</p>`,
        icon: 'warning',
        iconColor: '#10b981',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#f1f5f9',
        confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Lunasi',
        cancelButtonText: '<span style="color: #475569;">Batal</span>',
        reverseButtons: true,
        backdrop: `rgba(15, 23, 42, 0.4) backdrop-filter: blur(4px)`,
        customClass: { popup: 'custom-swal-popup', title: 'custom-swal-title', confirmButton: 'custom-swal-button', cancelButton: 'custom-swal-button' }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', text: 'Mencatat pembayaran...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            window.location.href = urlTujuan;
        }
    });
}
</script>
<?= $this->endSection(); ?>