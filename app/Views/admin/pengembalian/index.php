<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin-bottom: 30px; }
    .table-container h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
    
    table { width: 100%; border-collapse: collapse; }
    th { padding: 16px 15px; background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    td { padding: 16px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; vertical-align: middle; transition: background-color 0.2s; }
    tr:hover td { background-color: #f8fafc; }
    
    .text-center { text-align: center; }
    .fw-bold { font-weight: 600; color: #0f172a; }

    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .bg-primary { background: #dbeafe; color: #1e40af; }
    .bg-success { background: #d1fae5; color: #065f46; }
    .bg-danger { background: #fee2e2; color: #991b1b; }
    .bg-warning { background: #fef08a; color: #854d0e; }

    .action-group { display: flex; justify-content: center; gap: 8px; }
    .btn-action { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
    .btn-return { background: #005ce6; color: white; box-shadow: 0 2px 4px rgba(0, 92, 230, 0.2); }
    .btn-return:hover { background: #0044b3; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0, 92, 230, 0.3); }
    
    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 40px; margin-bottom: 15px; color: #cbd5e1; }

    /* Custom SweetAlert2 Style untuk Tema BalaNus */
    div:where(.swal2-container) { font-family: 'Poppins', sans-serif !important; }
    .custom-swal-popup { border-radius: 16px !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; padding: 2em !important; }
    .custom-swal-title { font-size: 22px !important; font-weight: 600 !important; color: #1e293b !important; margin-bottom: 5px !important; }
    .custom-swal-text { color: #64748b !important; font-size: 14px !important; }
    .custom-swal-button { border-radius: 8px !important; font-weight: 500 !important; padding: 12px 24px !important; font-size: 14px !important; transition: all 0.3s ease !important; border: none !important; }
</style>

<div class="table-container">
    <h2>Data Pengembalian Buku</h2>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Peminjam</th>
                    <th width="25%">Judul Buku</th>
                    <th width="15%">Tenggat Waktu</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($pengembalian)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-box-open"></i>
                                <p>Belum ada buku yang sedang dipinjam atau dikembalikan.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($pengembalian as $p): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><span class="fw-bold"><?= esc(ucwords($p['username'])) ?></span></td>
                        <td><?= esc($p['judul_buku']) ?></td>
                        <td>
                            <?php if(!empty($p['tenggat_waktu'])): ?>
                                <?php 
                                    $tenggat = strtotime($p['tenggat_waktu']);
                                    $sekarang = strtotime(date('Y-m-d'));
                                    $is_terlambat = ($sekarang > $tenggat && strtolower($p['status']) == 'dipinjam');
                                ?>
                                <span style="color: <?= $is_terlambat ? '#ef4444' : '#0f172a' ?>; font-weight: <?= $is_terlambat ? '600' : '400' ?>;">
                                    <i class="fa-regular fa-calendar" style="margin-right: 5px;"></i> <?= date('d M Y', $tenggat) ?>
                                    <?= $is_terlambat ? '<br><small style="font-weight:600;">(Terlambat)</small>' : '' ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php 
                                $status = strtolower($p['status']);
                                if ($status == 'dipinjam') {
                                    echo '<span class="status-badge bg-primary">Dipinjam</span>';
                                } elseif ($status == 'selesai' || $status == 'dikembalikan') {
                                    echo '<span class="status-badge bg-success">Dikembalikan</span>';
                                } else {
                                    echo '<span class="status-badge bg-warning">'.esc($p['status']).'</span>';
                                }
                            ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if(strtolower($p['status']) == 'dipinjam'): ?>
                                <div class="action-group">
                                    <a href="<?= base_url('admin/pengembalian/proses/' . $p['id_peminjaman']) ?>" class="btn-action btn-return" onclick="konfirmasiPengembalian(event, this.href)">
                                        <i class="fa-solid fa-rotate-left"></i> Terima Buku
                                    </a>
                                </div>
                            <?php else: ?>
                                <span style="font-size: 13px; color: #10b981; font-weight: 500;"><i class="fa-solid fa-check-circle" style="margin-right: 5px;"></i> Selesai</span>
                            <?php endif; ?>
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
// Menangkap flashdata dari CodeIgniter dan menampilkannya sebagai SweetAlert
<?php if(session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= session()->getFlashdata('success') ?>',
        confirmButtonColor: '#10b981',
        customClass: { popup: 'custom-swal-popup', title: 'custom-swal-title', confirmButton: 'custom-swal-button' }
    });
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?= session()->getFlashdata('error') ?>',
        confirmButtonColor: '#ef4444',
        customClass: { popup: 'custom-swal-popup', title: 'custom-swal-title', confirmButton: 'custom-swal-button' }
    });
<?php endif; ?>

// Fungsi untuk menggantikan confirm() bawaan browser
function konfirmasiPengembalian(event, urlTujuan) {
    // Cegah link agar tidak langsung berpindah halaman
    event.preventDefault();

    Swal.fire({
        title: 'Terima Buku?',
        html: '<p class="custom-swal-text">Proses pengembalian buku ini?<br>Stok fisik akan otomatis bertambah 1.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#005ce6',
        cancelButtonColor: '#f1f5f9',
        confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Terima',
        cancelButtonText: '<span style="color: #475569;">Batal</span>',
        reverseButtons: true, // Tombol batal di kiri, tombol konfirmasi di kanan
        backdrop: `rgba(15, 23, 42, 0.4) backdrop-filter: blur(4px)`,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            confirmButton: 'custom-swal-button',
            cancelButton: 'custom-swal-button'
        }
    }).then((result) => {
        // Jika admin menekan tombol "Ya, Terima"
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui status dan stok buku.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            // Lanjutkan perpindahan ke URL aksi controller
            window.location.href = urlTujuan;
        }
    });
}
</script>
<?= $this->endSection(); ?>