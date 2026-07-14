<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<style>
    .card-panel { background: #fff; border-radius: 12px; padding: 25px; border: 1px solid #eaedf2; box-shadow: 0 2px 10px rgba(0,0,0,0.02);}
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-header h3 { font-size: 18px; color: #1e293b; font-weight: 600; margin: 0; }
    
    .table-denda { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-denda th { background: #f8fafc; color: #64748b; padding: 12px 15px; text-align: left; font-weight: 600; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid #eaedf2; }
    .table-denda td { padding: 15px; border-bottom: 1px solid #eaedf2; vertical-align: middle; color: #475569; }
    
    .badge-terlambat { background-color: #ffeaea; color: #d9534f; padding: 5px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; }
    
    .btn-lunasi { background-color: #10b981; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 12px; transition: 0.2s; font-family: 'Poppins', sans-serif;}
    .btn-lunasi:hover { background-color: #059669; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); }

    /* Customisasi SweetAlert */
    .swal-denda-popup { border-radius: 16px !important; font-family: 'Poppins', sans-serif !important; width: 400px !important; padding: 2em !important; }
    .swal-denda-title { font-size: 20px !important; color: #1e293b !important; margin-bottom: 15px !important; font-weight: 600 !important;}
</style>

<div class="card-panel">
    <div class="panel-header">
        <h3><?= esc($title ?? 'Data Denda & Keterlambatan') ?></h3>
    </div>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div style="background: #e6ffef; color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500;">
            <i class="fa-solid fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div style="background: #ffeaea; color: #d9534f; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 500;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <table class="table-denda">
        <thead>
            <tr>
                <th>NO</th>
                <th>PEMINJAM</th>
                <th>JUDUL BUKU</th>
                <th>TENGGAT WAKTU</th>
                <th>KETERLAMBATAN</th>
                <th>TOTAL DENDA</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($denda)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #888; font-size: 14px;">
                        <i class="fa-solid fa-face-smile" style="font-size: 24px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                        Tidak ada data buku yang terlambat saat ini.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $no = 1; 
                $tarif_denda_per_hari = 1000; // Tarif denda Rp 1.000 per hari
                
                foreach($denda as $d): 
                    // Logika perhitungan hari terlambat
                    $tenggat = strtotime($d['tenggat_waktu']);
                    $sekarang = strtotime(date('Y-m-d'));
                    $hari_terlambat = floor(($sekarang - $tenggat) / (60 * 60 * 24));
                    
                    // Jika data bocor (belum telat tapi masuk sini), kita skip perhitungan negatifnya
                    if ($hari_terlambat <= 0) continue; 
                    
                    $nominal_denda = $hari_terlambat * $tarif_denda_per_hari;
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td style="font-weight: 600; color: #334155;"><?= esc($d['username']); ?></td>
                        <td><?= esc($d['judul_buku']); ?></td>
                        <td><i class="fa-regular fa-calendar" style="color: #94a3b8; margin-right: 5px;"></i> <?= date('d M Y', strtotime($d['tenggat_waktu'])); ?></td>
                        <td>
                            <span class="badge-terlambat"><?= $hari_terlambat; ?> Hari</span>
                        </td>
                        <td style="font-weight: 700; color: #d9534f;">Rp <?= number_format($nominal_denda, 0, ',', '.'); ?></td>
                        <td>
                            <button onclick="konfirmasiLunas('<?= $d['id_peminjaman']; ?>', '<?= esc($d['username']); ?>', '<?= number_format($nominal_denda, 0, ',', '.'); ?>')" class="btn-lunasi">
                                <i class="fa-solid fa-money-bill-wave"></i> Lunasi
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiLunas(id_peminjaman, nama, nominal) {
        Swal.fire({
            icon: 'warning',
            title: 'Terima Pembayaran?',
            html: `Konfirmasi pembayaran denda atas nama <b>${nama}</b> sebesar <b style="color:#d9534f; font-size: 16px;">Rp ${nominal}</b>.<br><br><span style="font-size:12px; color:#64748b;">Status peminjaman akan diubah menjadi "Selesai" dan stok buku akan dikembalikan (+1).</span>`,
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#f1f5f9',
            cancelButtonText: '<span style="color:#64748b; font-weight:600;">Batal</span>',
            confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Lunasi & Selesai',
            reverseButtons: true,
            customClass: {
                popup: 'swal-denda-popup',
                title: 'swal-denda-title'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading screen
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Menyelesaikan transaksi denda.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Arahkan ke rute 'lunas' yang ada di controllermu
                window.location.href = `<?= base_url('admin/denda/lunas') ?>/${id_peminjaman}`;
            }
        });
    }
</script>
<?= $this->endSection(); ?>