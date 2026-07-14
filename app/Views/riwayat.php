<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Riwayat Baca - BalaNus') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root { --primary: #005ce6; --primary-dark: #0044b3; --bg-color: #f5f7fb; --text-dark: #333; --text-muted: #888; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; }
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
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .header-title p { font-size: 12px; color: var(--text-muted); }
        
        /* PAGE CONTENT STYLES */
        .page-content { padding: 30px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 10px; font-size: 13px; color: var(--text-muted); border-bottom: 2px solid var(--border); font-weight: 600; text-transform: uppercase; }
        td { padding: 15px 10px; font-size: 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        
        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; text-align: center; }
        .bg-success { background: #dcfce7; color: #16a34a; }
        
        .empty-state { text-align: center; padding: 50px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 50px; color: #e2e8f0; margin-bottom: 15px; }
        .empty-state h4 { color: var(--text-dark); margin-bottom: 5px; }
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
            <a href="<?= base_url('riwayat') ?>" class="menu-item active"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
            <a href="<?= base_url('denda') ?>" class="menu-item"><i class="fa-solid fa-wallet"></i> Denda & Tanggungan</a>
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
                <h1>Riwayat Baca</h1>
                <p>Kumpulan buku yang pernah Anda pinjam dan selesai dibaca.</p>
            </div>
        </header>

        <div class="page-content">
            <div class="card-panel">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Judul Buku</th>
                            <th width="20%">Tanggal Pinjam</th>
                            <th width="20%">Tanggal Kembali</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($riwayat)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-book-journal-whills"></i>
                                        <h4>Belum Ada Riwayat</h4>
                                        <p>Anda belum memiliki riwayat buku yang selesai dipinjam.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach($riwayat as $r): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td style="font-weight: 600; color: var(--text-dark);"><?= esc($r['judul_buku']) ?></td>
                                <td><?= date('d M Y', strtotime($r['tgl_pengajuan'])) ?></td>
                                <td><?= !empty($r['tgl_dikembalikan']) ? date('d M Y', strtotime($r['tgl_dikembalikan'])) : '-' ?></td>
                                <td><span class="status-badge bg-success">Selesai</span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>