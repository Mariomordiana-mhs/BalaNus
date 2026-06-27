<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin-bottom: 30px; }
    .table-container h2 { color: #1e293b; font-size: 22px; font-weight: 600; margin-bottom: 25px; }
    
    /* Tabel Modern */
    table { width: 100%; border-collapse: collapse; }
    th { padding: 16px 15px; background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: left; }
    td { padding: 16px 15px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 14px; vertical-align: middle; transition: background-color 0.2s; }
    tr:hover td { background-color: #fef2f2; } /* Hover kemerahan tipis */
    
    .text-center { text-align: center; }
    .fw-bold { font-weight: 600; color: #0f172a; }

    /* Tombol Aksi */
    .btn-action { padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; }
    .btn-hapus { background: #ef4444; color: white; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    .btn-hapus:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3); }

    .btn-detail { background: #3b82f6; color: white; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2); }
    .btn-detail:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3); }
    .action-group { display: flex; justify-content: center; gap: 8px; } /* Pastikan action-group ada */

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
    <h2>Data Anggota</h2>

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
                    <th width="25%">Username</th>
                    <th width="30%">Email</th>
                    <th width="25%">Tanggal Bergabung</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($anggota)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-users-slash"></i>
                                <p>Belum ada data anggota perpustakaan.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($anggota as $a): ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><span class="fw-bold"><?= esc($a['username']) ?></span></td>
                        <td><?= esc($a['email']) ?></td>
                        <!-- Ganti field 'created_at' di bawah ini jika nama kolom di database Anda berbeda -->
                        <td><i class="fa-regular fa-clock" style="color: #94a3b8; margin-right: 5px;"></i> <?= esc($a['created_at'] ?? $a['tanggal_bergabung'] ?? '') ?></td>
                        <td class="text-center">
                            <!-- PERBAIKAN: Penambahan div action-group dan Tombol Detail -->
                            <div class="action-group">
                                <a href="<?= base_url('admin/anggota/detail/' . ($a['id_user'] ?? $a['id_anggota'])) ?>" class="btn-action btn-detail" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                                <a href="<?= base_url('admin/anggota/delete/' . ($a['id_user'] ?? $a['id_anggota'])) ?>" class="btn-action btn-hapus" onclick="konfirmasiHapus(event, this.href, '<?= esc($a['username']) ?>')">
                                    <i class="fa-solid fa-trash"></i> Hapus
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
<!-- Load SweetAlert2 via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi cerdas untuk menangani konfirmasi Hapus Anggota
function konfirmasiHapus(event, urlTujuan, namaAnggota) {
    // Mencegah link pindah halaman secara langsung
    event.preventDefault();

    Swal.fire({
        title: 'Hapus Anggota?',
        html: `<p class="custom-swal-text">Apakah Anda yakin ingin menghapus <b>${namaAnggota}</b>?<br>Data anggota ini akan dihapus permanen dari sistem.</p>`,
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
                text: 'Mengahpus data dari database.',
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