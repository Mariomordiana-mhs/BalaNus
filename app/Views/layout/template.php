<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BalaNus Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { --primary: #005ce6; --primary-dark: #0044b3; --bg-color: #f5f7fb; --text-dark: #333; --text-muted: #888; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-dark); display: flex; min-height: 100vh; overflow-x: hidden; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 10px; }
        
        .logo-area { text-align: center; padding: 30px 20px; }
        .logo-area i { font-size: 50px; margin-bottom: 10px; }
        .logo-area h2 { font-size: 24px; font-weight: 600; letter-spacing: 1px; }
        .logo-area p { font-size: 11px; opacity: 0.8; font-weight: 300; }
        
        .menu-wrapper { flex: 1; padding: 0 15px; }
        .menu-title { font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 10px 15px; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; color: var(--white); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; font-size: 14px; font-weight: 500; }
        .menu-item i { width: 25px; font-size: 16px; }
        .menu-item:hover, .menu-item.active { background-color: rgba(255,255,255,0.15); }

        .user-profile-sidebar { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px; cursor: pointer; }
        .user-profile-sidebar i { font-size: 24px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 50%; }
        .user-info p { font-size: 14px; font-weight: 600; margin-bottom: -3px;}
        .user-info span { font-size: 12px; opacity: 0.7; }

        /* MAIN CONTENT */
        .main-content { flex: 1; margin-left: var(--sidebar-width); display: flex; flex-direction: column; min-height: 100vh; }
        
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .header-title p { font-size: 12px; color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .search-bar { background: var(--bg-color); border-radius: 20px; padding: 8px 15px; display: flex; align-items: center; width: 250px; }
        .search-bar input { 
            border: none !important; 
            background: transparent !important; 
            outline: none !important; 
            width: 100% !important; 
            font-size: 13px !important; 
            margin-left: 10px !important; 
            box-shadow: none !important;
            padding: 0 !important;
        }
        .notifications { position: relative; cursor: pointer; color: var(--text-muted); font-size: 18px; }
        .badge { position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; font-weight: bold; }
        
        /* Dashboard Body diubah agar tidak ada padding berlebih jika ingin full */
        .dashboard-body { padding: 20px; flex: 1; }
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
            <a href="<?= base_url('admin') ?>" class="menu-item"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
            <div class="menu-title">KOLEKSI</div>
            <a href="<?= base_url('admin/buku') ?>" class="menu-item"><i class="fa-solid fa-book"></i> Buku</a>
            <a href="<?= base_url('admin/eksemplar')?>" class="menu-item"><i class="fa-solid fa-cubes"></i> Eksemplar</a>
            <div class="menu-title">ANGGOTA</div>
            <a href="<?= base_url('admin/anggota')?>" class="menu-item"><i class="fa-solid fa-users"></i> Anggota</a>
            <div class="menu-title">TRANSAKSI</div>
            <a href="<?= base_url('admin/peminjaman')?>" class="menu-item"><i class="fa-solid fa-right-left"></i> Peminjaman</a>
            <a href="<?= base_url('admin/pengembalian') ?>" class="menu-item <?= (url_is('admin/pengembalian')) ? 'active' : '' ?>">
    <i class="fa-solid fa-rotate-left"></i> Pengembalian
</a>
            <a href="<?= base_url('admin/denda') ?>" class="menu-item <?= (url_is('admin/denda')) ? 'active' : '' ?>">
    <i class="fa-solid fa-money-bill-wave"></i> Denda
</a>
            <div class="menu-title">LAPORAN</div>
            <a href="<?= base_url('admin/laporan') ?>" class="menu-item <?= (url_is('admin/laporan')) ? 'active' : '' ?>">
    <i class="fa-solid fa-chart-simple"></i> Laporan
</a>
        </div>

        <div class="user-profile-sidebar" onclick="window.location.href='<?= base_url('logout') ?>'">
            <i class="fa-solid fa-user"></i>
            <div class="user-info">
                <p><?= session()->get('username') ?? 'Admin' ?></p>
                <span>Pustakawan (Logout)</span>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="header-title">
                <?php
                date_default_timezone_set('Asia/Jakarta');
                $jam = date('H');
                if ($jam >= 5 && $jam < 11) { $sapaan = "Selamat Pagi"; }
                elseif ($jam >= 11 && $jam < 15) { $sapaan = "Selamat Siang"; }
                elseif ($jam >= 15 && $jam < 19) { $sapaan = "Selamat Sore"; }
                else { $sapaan = "Selamat Malam"; }
                ?>
                <h1><?= $sapaan ?>, <?= session()->get('username') ?? 'Admin' ?></h1>
                <p>Sistem Informasi Manajemen Perpustakaan BalaNus</p>
            </div>
            <div class="topbar-right">
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted)"></i>
                    <input type="text" placeholder="Cari buku, anggota, ISBN...">
                </div>
                <div class="notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge" id="notifBadge">0</span>
                </div>
            </div>
        </header>

        <div class="dashboard-body">
            <?= $this->renderSection('content'); ?>
        </div>
    </main>

    <?= $this->renderSection('scripts'); ?>
</body>
</html>