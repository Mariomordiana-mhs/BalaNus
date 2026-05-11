<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root { --primary: #005ce6; --primary-dark: #0044b3; --bg-color: #f5f7fb; --text-dark: #333; --text-muted: #888; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-dark); display: flex; min-height: 100vh; overflow-x: hidden; }
        
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
        
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .dashboard-body { padding: 30px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 30px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .panel-header h3 { font-size: 18px; font-weight: 600; }
        
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; text-align: center; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
        .bg-warning { background: #fff8e6; color: #f5a623; }
        .bg-green { background: #e6ffef; color: #28a745; }
        
        .detail-table { width: 100%; max-width: 600px; border-collapse: collapse; margin-top: 20px; }
        .detail-table td { padding: 12px 0; border-bottom: 1px solid var(--border); font-size: 14px; }
        .detail-table td:first-child { color: var(--text-muted); width: 200px; }
        .detail-table td:last-child { font-weight: 500; color: var(--text-dark); }
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
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            <div class="menu-title">AKTIVITAS SAYA</div>
            <a href="javascript:void(0)" class="menu-item active"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="header-title">
                <h1>Detail Aktivitas Buku</h1>
            </div>
        </header>

        <div class="dashboard-body">
            <div class="card-panel">
                <div class="panel-header">
                    <h3>Informasi Peminjaman</h3>
                    <a href="<?= base_url('member') ?>" style="font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 500;">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
                
                <div style="display: flex; gap: 30px;">
                    <div style="width: 150px; height: 220px; background: #f0f4ff; border-radius: 8px; display: flex; justify-content: center; align-items: center; font-size: 60px; color: var(--primary); border: 1px solid #dce4ff;">
                        <i class="fa-solid fa-book-journal-whills"></i>
                    </div>
                    
                    <div style="flex: 1;">
                        <h2 style="margin-bottom: 5px; font-size: 24px;"><?= esc($detail['judul_buku']); ?></h2>
                        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 10px;">
                            <i class="fa-solid fa-pen-nib" style="margin-right: 5px;"></i> Penulis: <?= esc($detail['penulis'] ?? 'Tidak diketahui'); ?>
                        </p>

                        <table class="detail-table">
                            <tr>
                                <td>Status Saat Ini</td>
                                <td>
                                    <?php 
                                    $badge_class = '';
                                    if ($detail['status'] == 'Menunggu ACC') $badge_class = 'bg-warning';
                                    if ($detail['status'] == 'Dipinjam') $badge_class = 'bg-blue';
                                    if ($detail['status'] == 'Selesai') $badge_class = 'bg-green';
                                    ?>
                                    <span class="status-badge <?= $badge_class; ?>">
                                        <?= esc($detail['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td><?= $detail['tgl_pengajuan'] ? date('d F Y', strtotime($detail['tgl_pengajuan'])) : '<span style="color:#aaa;">-</span>'; ?></td>
                            </tr>
                            <tr>
                                <td>Tenggat Waktu Kembali</td>
                                <td><?= $detail['tenggat_waktu'] ? date('d F Y', strtotime($detail['tenggat_waktu'])) : '<span style="color:#aaa;">-</span>'; ?></td>
                            </tr>
                            <tr>
                                <td>Dikembalikan Pada</td>
                                <td><?= $detail['tgl_dikembalikan'] ? date('d F Y', strtotime($detail['tgl_dikembalikan'])) : '<span style="color:#aaa;">Belum dikembalikan</span>'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>