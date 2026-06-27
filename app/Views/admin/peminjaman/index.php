<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin-bottom: 30px; }
    .table-container h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
    
    /* Perbaikan Tabel agar lebih lega dan modern */
    table { width: 100%; border-collapse: collapse; }
    th { padding: 16px 15px; background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    td { padding: 16px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; vertical-align: middle; transition: background-color 0.2s; }
    tr:hover td { background-color: #f8fafc; }
    
    .text-center { text-align: center; }
    .fw-bold { font-weight: 600; color: #0f172a; }

    /* Label Status */
    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .bg-warning { background: #fef08a; color: #854d0e; }
    .bg-primary { background: #dbeafe; color: #1e40af; }
    .bg-success { background: #d1fae5; color: #065f46; }
    .bg-danger { background: #fee2e2; color: #991b1b; }

    /* Tombol Aksi */
    .action-group { display: flex; justify-content: center; gap: 8px; }
    .btn-action { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
    .btn-acc { background: #10b981; color: white; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); }
    .btn-acc:hover { background: #059669; transform: translateY(-1px); }
    .btn-tolak { background: #ef4444; color: white; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    .btn-tolak:hover { background: #dc2626; transform: translateY(-1px); }
    
    /* State Kosong */
    .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-state i { font-size: 40px; margin-bottom: 15px; color: #cbd5e1; }

    /* Custom SweetAlert2 Style */
    div:where(.swal2-container) { font-family: 'Poppins', sans-serif !important; }
    .custom-swal-popup { border-radius: 16px !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; padding: 2em !important; }
    .custom-swal-title { font-size: 22px !important; font-weight: 600 !important; color: #1e293b !important; margin-bottom: 5px !important; }
    .custom-swal-text { color: #64748b !important; font-size: 14px !important; }
    .custom-swal-button { border-radius: 8px !important; font-weight: 500 !important; padding: 12px 24px !important; font-size: 14px !important; transition: all 0.3s ease !important; border: none !important; }
</style>

<div class="table-container">
    <h2>Data Transaksi Peminjaman</h2>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">Peminjam</th>
                    <th width="25%">Judul Buku</th>
                    <th width="15%">Tgl Pengajuan</th>
                    <th width="15%">Tenggat Waktu</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($peminjaman)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <p>Belum ada data transaksi peminjaman saat ini.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($peminjaman as $p): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><span class="fw-bold"><?= esc(ucwords($p['username'])) ?></span></td>
                        <td><?= esc($p['judul_buku']) ?></td>
                        <td><?= date('d M Y', strtotime($p['tgl_pengajuan'])) ?></td>
                        <td>
                            <?php if(!empty($p['tenggat_waktu'])): ?>
                                <span style="color: #0f172a;"><i class="fa-regular fa-calendar" style="color: #94a3b8; margin-right: 5px;"></i> <?= date('d M Y', strtotime($p['tenggat_waktu'])) ?></span>
                            <?php else: ?>
                                <span style="color: #94a3b8;">-</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php 
                                $status = strtolower($p['status']);
                                if ($status == 'menunggu acc') {
                                    echo '<span class="status-badge bg-warning">Menunggu ACC</span>';
                                } elseif ($status == 'dipinjam') {
                                    echo '<span class="status-badge bg-primary">Dipinjam</span>';
                                } elseif ($status == 'selesai' || $status == 'dikembalikan') {
                                    echo '<span class="status-badge bg-success">Selesai</span>';
                                } else {
                                    echo '<span class="status-badge bg-danger">'.esc($p['status']).'</span>';
                                }
                            ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if(strtolower($p['status']) == 'menunggu acc'): ?>
                                <div class="action-group">
                                    <a href="<?= base_url('admin/peminjaman/acc/' . $p['id_peminjaman']) ?>" class="btn-action btn-acc" onclick="konfirmasiAksi(event, this.href, 'acc')" title="Setujui">
                                        <i class="fa-solid fa-check"></i>
                                    </a>
                                    <a href="<?= base_url('admin/peminjaman/tolak/' . $p['id_peminjaman']) ?>" class="btn-action btn-tolak" onclick="konfirmasiAksi(event, this.href, 'tolak')" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                </div>
                            
                            <?php elseif(strtolower($p['status']) == 'dipinjam'): ?>
                                <span style="font-size: 13px; color: #005ce6; font-weight: 500;"><i class="fa-solid fa-book-open-reader"></i> Dibaca</span>
                            
                            <?php else: ?>
                                <span style="font-size: 13px; color: #10b981; font-weight: 500;"><i class="fa-solid fa-check-double"></i> Selesai</span>
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
// Menampilkan notifikasi sukses/gagal dari controller
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

// Fungsi cerdas untuk menangani konfirmasi ACC maupun Tolak
function konfirmasiAksi(event, urlTujuan, jenisAksi) {
    event.preventDefault();

    let titleTxt, htmlTxt, confirmBtnColor, confirmBtnIcon, confirmBtnTxt;

    // Tentukan tema popup berdasarkan tombol yang diklik
    if (jenisAksi === 'acc') {
        titleTxt = 'Setujui Peminjaman?';
        htmlTxt = 'Buku akan ditandai sebagai <b>Dipinjam</b> dan tenggat waktu pengembalian akan diatur secara otomatis.';
        confirmBtnColor = '#10b981'; // Hijau
        confirmBtnIcon = '<i class="fa-solid fa-check"></i>';
        confirmBtnTxt = ' Ya, Setujui';
    } else {
        titleTxt = 'Tolak Peminjaman?';
        htmlTxt = 'Pengajuan akan <b>Dibatalkan</b> dan ketersediaan stok buku akan dikembalikan seperti semula.';
        confirmBtnColor = '#ef4444'; // Merah
        confirmBtnIcon = '<i class="fa-solid fa-xmark"></i>';
        confirmBtnTxt = ' Ya, Tolak';
    }

    Swal.fire({
        title: titleTxt,
        html: `<p class="custom-swal-text">${htmlTxt}</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmBtnColor,
        cancelButtonColor: '#f1f5f9',
        confirmButtonText: confirmBtnIcon + confirmBtnTxt,
        cancelButtonText: '<span style="color: #475569;">Batal</span>',
        reverseButtons: true, // Tombol batal di kiri
        backdrop: `rgba(15, 23, 42, 0.4) backdrop-filter: blur(4px)`,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            confirmButton: 'custom-swal-button',
            cancelButton: 'custom-swal-button'
        }
    }).then((result) => {
        // Jika admin setuju (klik Ya)
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            // Lanjutkan eksekusi ke link controller
            window.location.href = urlTujuan;
        }
    });
}
</script>
<?= $this->endSection(); ?>