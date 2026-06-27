<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin-bottom: 30px; }
    .table-container h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
    
    /* Tombol Tambah Data */
    .btn-add { background: #005ce6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 25px; transition: 0.3s; box-shadow: 0 4px 6px rgba(0, 92, 230, 0.2); }
    .btn-add:hover { background: #0044b3; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0, 92, 230, 0.3); }

    /* Tabel Modern */
    table { width: 100%; border-collapse: collapse; }
    th { padding: 16px 15px; background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    td { padding: 16px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; vertical-align: middle; transition: background-color 0.2s; }
    tr:hover td { background-color: #f8fafc; }
    
    .text-center { text-align: center; }
    .fw-bold { font-weight: 600; color: #0f172a; }

    /* Label Status & Kondisi */
    .badge-status { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .bg-tersedia { background: #d1fae5; color: #065f46; }
    .bg-dipinjam { background: #dbeafe; color: #1e40af; }
    .bg-hilang { background: #fee2e2; color: #991b1b; }

    /* Tombol Aksi */
    .action-group { display: flex; justify-content: center; gap: 8px; }
    .btn-action { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
    .btn-edit { background: #f59e0b; color: white; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2); }
    .btn-edit:hover { background: #d97706; transform: translateY(-1px); }
    .btn-hapus { background: #ef4444; color: white; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    .btn-hapus:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3); }

    /* Notifikasi */
    .alert-success { background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #10b981; }
    .alert-error { background: #fef2f2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #ef4444; }

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
    <h2>Data Eksemplar</h2>

    <a href="<?= base_url('admin/eksemplar/create') ?>" class="btn-add">
        <i class="fa-solid fa-plus"></i> Tambah Eksemplar
    </a>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> 
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i> 
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="20%">Kode Eksemplar</th>
                    <th width="30%">Judul Buku</th>
                    <th width="15%">Kondisi</th>
                    <th width="15%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($eksemplar)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-cubes"></i>
                                <p>Belum ada data eksemplar fisik buku.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($eksemplar as $e): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><span class="fw-bold"><?= esc($e['kode_eksemplar']) ?></span></td>
                        <td><?= esc($e['judul_buku'] ?? 'Judul Tidak Diketahui') ?></td>
                        <td><?= esc($e['kondisi'] ?? 'Baik') ?></td>
                        <td class="text-center">
                            <?php 
                                $status = strtolower($e['status'] ?? 'tersedia');
                                if ($status == 'tersedia') {
                                    echo '<span class="badge-status bg-tersedia">Tersedia</span>';
                                } elseif ($status == 'dipinjam') {
                                    echo '<span class="badge-status bg-dipinjam">Dipinjam</span>';
                                } else {
                                    echo '<span class="badge-status bg-hilang">'.esc($status).'</span>';
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <div class="action-group">
                                <a href="<?= base_url('admin/eksemplar/edit/' . $e['id_eksemplar']) ?>" class="btn-action btn-edit" title="Edit">
                                    Edit
                                </a>
                                <a href="<?= base_url('admin/eksemplar/delete/' . $e['id_eksemplar']) ?>" class="btn-action btn-hapus" onclick="konfirmasiHapus(event, this.href, '<?= esc($e['kode_eksemplar']) ?>')">
                                    Hapus
                                </a>
                            </div>
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
// Fungsi cerdas untuk menangani konfirmasi Hapus Eksemplar
function konfirmasiHapus(event, urlTujuan, kodeEksemplar) {
    // Mencegah link pindah halaman secara langsung
    event.preventDefault();

    Swal.fire({
        title: 'Hapus Eksemplar?',
        html: `<p class="custom-swal-text">Apakah Anda yakin ingin menghapus fisik buku dengan kode <b>${kodeEksemplar}</b>?<br>Data eksemplar ini akan dihapus permanen dari sistem.</p>`,
        icon: 'warning',
        iconColor: '#ef4444', // Ikon warna merah
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Tombol Ya warna merah
        cancelButtonColor: '#f1f5f9',
        confirmButtonText: '<i class="fa-solid fa-trash"></i> Ya, Hapus',
        cancelButtonText: '<span style="color: #475569;">Batal</span>',
        reverseButtons: true, // Tombol batal di sebelah kiri
        backdrop: `rgba(15, 23, 42, 0.4) backdrop-filter: blur(4px)`,
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            confirmButton: 'custom-swal-button',
            cancelButton: 'custom-swal-button'
        }
    }).then((result) => {
        // Jika admin klik "Ya, Hapus"
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                text: 'Menghapus data dari database.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            // Lanjutkan eksekusi ke link URL penghapusan
            window.location.href = urlTujuan;
        }
    });
}
</script>
<?= $this->endSection(); ?>