<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Denda & Tanggungan - BalaNus') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-OPPqMA-OqJ9FUQ5W"></script>
    
    <style>
        :root { --primary: #005ce6; --primary-dark: #0044b3; --bg-color: #f5f7fb; --text-dark: #333; --text-muted: #888; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; --danger: #d9534f; --success: #28a745; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-dark); display: flex; min-height: 100vh; overflow-x: hidden; }
        
        /* SIDEBAR */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100vh; }
        .logo-area { text-align: center; padding: 30px 20px; }
        .logo-area i { font-size: 50px; margin-bottom: 10px; }
        .logo-area h2 { font-size: 24px; font-weight: 600; }
        .logo-area p { font-size: 11px; opacity: 0.8; font-weight: 300; }
        .menu-wrapper { flex: 1; padding: 0 15px; }
        .menu-title { font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 10px 15px; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; color: var(--white); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; font-size: 14px; font-weight: 500; }
        .menu-item i { width: 25px; font-size: 16px; }
        .menu-item:hover, .menu-item.active { background-color: rgba(255,255,255,0.15); }
        .menu-item.active { background-color: var(--primary-dark); }
        .user-profile-sidebar { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px; cursor: pointer; }
        .user-profile-sidebar i { font-size: 24px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 50%; }
        .user-info p { font-size: 14px; font-weight: 600; margin-bottom: -3px;}
        .user-info span { font-size: 12px; opacity: 0.7; }
        
        /* MAIN CONTENT */
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; }
        
        /* DASHBOARD BODY */
        .dashboard-body { padding: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px; }
        
        /* Modifikasi .stat-card agar bisa menampung tombol di sebelah kanan */
        .stat-card { background: var(--white); padding: 25px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .stat-card-left { display: flex; align-items: center; gap: 20px; }
        
        .stat-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 24px; }
        .stat-info h3 { font-size: 14px; color: var(--text-muted); font-weight: 500; }
        .stat-info h2 { font-size: 28px; font-weight: 700; margin-top: 5px; }

        /* TOMBOL BAYAR */
        .btn-bayar { background-color: var(--danger); color: var(--white); border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: 0.3s; }
        .btn-bayar:hover { background-color: #c9302c; }
        .btn-bayar:disabled { background-color: #e59997; cursor: not-allowed; }

        /* TABLE PANEL */
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 25px; }
        .panel-header { margin-bottom: 20px; }
        .panel-header h3 { font-size: 18px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 10px; font-size: 13px; color: var(--text-muted); border-bottom: 2px solid var(--border); }
        td { padding: 15px 10px; font-size: 14px; border-bottom: 1px solid var(--border); }
        
        .status-badge { padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; color: white; }
        .bg-danger { background-color: var(--danger); }
        .bg-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-area">
            <i class="fa-solid fa-book-open-reader"></i>
            <h2>BalaNus</h2>
            <p>Books are windows to the world</p>
        </div>
        
        <div class="menu-wrapper">
            <a href="<?= base_url('member') ?>" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <div class="menu-title">PERPUSTAKAAN</div>
            <a href="<?= base_url('katalog') ?>" class="menu-item"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            <div class="menu-title">AKTIVITAS SAYA</div>
            <a href="<?= base_url('peminjaman') ?>" class="menu-item"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
            <a href="<?= base_url('riwayat') ?>" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
            <a href="<?= base_url('denda') ?>" class="menu-item active"><i class="fa-solid fa-wallet"></i> Denda & Tanggungan</a>
        </div>

        <div class="user-profile-sidebar" onclick="window.location.href='<?= base_url('logout') ?>'">
            <i class="fa-solid fa-user"></i>
            <div class="user-info">
                <p><?= esc($username ?? 'Member') ?></p>
                <span>Anggota Aktif (Logout)</span>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="header-title">
                <h1>Halaman Denda & Tanggungan</h1>
            </div>
            <div class="topbar-right">
                <p style="font-size: 13px; color: var(--text-muted);"><?= date('l, d F Y') ?></p>
            </div>
        </header>

        <div class="dashboard-body">
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon" style="background:#ffebee; color:var(--danger);"><i class="fa-solid fa-circle-exclamation"></i></div>
                        <div class="stat-info">
                            <h3>Total Denda Belum Dibayar</h3>
                            <h2 style="color:var(--danger);">Rp <?= number_format($total_denda ?? 0, 0, ',', '.') ?></h2>
                        </div>
                    </div>
                    
                    <?php if (isset($total_denda) && $total_denda > 0): ?>
                        <button id="btn-bayar" class="btn-bayar">
                            <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                        </button>
                    <?php endif; ?>
                </div>

                <div class="stat-card">
                    <div class="stat-card-left">
                        <div class="stat-icon" style="background:#e6ffef; color:var(--success);"><i class="fa-solid fa-receipt"></i></div>
                        <div class="stat-info">
                            <h3>Total Denda Lunas</h3>
                            <h2>Rp <?= number_format($total_lunas ?? 0, 0, ',', '.') ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-panel">
                <div class="panel-header">
                    <h3>Rincian Keterlambatan Buku</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tgl Harus Kembali</th>
                            <th>Keterlambatan</th>
                            <th>Nominal Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rincian_denda)): ?>
                        <?php foreach ($rincian_denda as $denda): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--text-dark);">
                                    <?= esc($denda['judul_buku']) ?>
                                </td>
                                <td>
                                    <?= date('d M Y', strtotime($denda['tenggat_waktu'])) ?>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold"><?= $denda['keterlambatan'] ?> Hari</span>
                                </td>
                                <td>
                                    Rp <?= number_format($denda['nominal_denda'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge bg-danger"><?= esc($denda['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem 0;">
                                <div style="color: #28a745; font-size: 2rem; margin-bottom: 1rem;">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <p style="color: #6c757d; margin: 0;">Hebat! Anda tidak memiliki tunggakan denda saat ini.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 20px; font-style: italic;">
                    * Pembayaran denda kini dapat dilakukan secara online dengan menekan tombol "Bayar Sekarang" di atas.
                </p>
            </div>

        </div>
    </main>

    <script>
        const btnBayar = document.getElementById('btn-bayar');
        if (btnBayar) {
            btnBayar.onclick = function (e) {
                e.preventDefault();
                
                // Efek loading pada tombol
                btnBayar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
                btnBayar.disabled = true;

                // Meminta Token ke Controller CodeIgniter
                fetch('<?= base_url('denda/bayarToken') ?>', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    // Kembalikan status tombol
                    btnBayar.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                    btnBayar.disabled = false;

                    if (data.snapToken) {
                        // Membuka Popup Midtrans
                        window.snap.pay(data.snapToken, {
                            onSuccess: function(result) {
                                alert("Pembayaran berhasil! Terima kasih.");
                                window.location.reload();
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran Anda. Silakan selesaikan transaksi.");
                                window.location.reload();
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                                window.location.reload();
                            },
                            onClose: function() {
                                alert('Anda menutup halaman pembayaran sebelum selesai.');
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan token pembayaran dari server.');
                    }
                })
                .catch(error => {
                    btnBayar.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                    btnBayar.disabled = false;
                    alert('Terjadi kesalahan jaringan: ' + error.message);
                });
            };
        }
    </script>

</body>
</html>