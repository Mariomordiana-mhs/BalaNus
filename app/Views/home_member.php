<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - BalaNus</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- CSS SAMA DENGAN ADMIN AGAR KONSISTEN -->
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
        .user-profile-sidebar { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px; cursor: pointer; }
        .user-profile-sidebar i { font-size: 24px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 50%; }
        .user-info p { font-size: 14px; font-weight: 600; margin-bottom: -3px;}
        .user-info span { font-size: 12px; opacity: 0.7; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .header-title p { font-size: 12px; color: var(--text-muted); }
        .dashboard-body { padding: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: var(--white); padding: 20px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 20px; }
        .stat-info h3 { font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .stat-info h2 { font-size: 24px; color: var(--primary); font-weight: 600; margin-top: 5px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 20px; margin-bottom: 20px;}
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .panel-header h3 { font-size: 16px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border); font-weight: 500;}
        td { padding: 15px 10px; font-size: 13px; border-bottom: 1px solid var(--border); }
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 500; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-area">
            <i class="fa-solid fa-book-open-reader"></i>
            <h2>BalaNus</h2>
            <p>Books are windows to the world</p>
        </div>
        
        <div class="menu-wrapper">
            <a href="#" class="menu-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            
            <div class="menu-title">PERPUSTAKAAN</div>
            <a href="#" class="menu-item"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            
            <div class="menu-title">AKTIVITAS SAYA</div>
            <a href="#" class="menu-item"><i class="fa-solid fa-book-bookmark"></i> Sedang Dipinjam</a>
            <a href="#" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
            <a href="#" class="menu-item"><i class="fa-solid fa-money-bill-wave"></i> Tagihan Denda</a>
        </div>

        <div class="user-profile-sidebar" onclick="window.location.href='<?= base_url('logout') ?>'">
            <i class="fa-solid fa-user"></i>
            <div class="user-info">
                <p><?= session()->get('username') ?? 'Member' ?></p>
                <span>Anggota Aktif (Logout)</span>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="header-title">
                <h1>Dashboard Anggota</h1>
                <p>Selamat membaca, <?= session()->get('username') ?? 'Member' ?>!</p>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <div class="dashboard-body">
            
            <!-- 3 CARDS KHUSUS MEMBER -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Buku Sedang Dipinjam</h3>
                            <h2 style="color:var(--primary);">2 Buku</h2>
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#e0e7ff; color:var(--primary);"><i class="fa-solid fa-book-open"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Total Buku Dibaca</h3>
                            <h2 style="color:#28a745;">15 Buku</h2>
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#e6ffef; color:#28a745;"><i class="fa-solid fa-check-double"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Tagihan Denda</h3>
                            <h2 style="color:#28a745;">Rp 0</h2>
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#e6ffef; color:#28a745;"><i class="fa-solid fa-wallet"></i></div>
                </div>
            </div>

            <!-- TABEL PEMINJAMAN AKTIF MEMBER -->
            <div class="card-panel">
                <div class="panel-header">
                    <h3>Peminjaman Saat Ini</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 600;">Filosofi Teras</td>
                            <td>Henry Manampiring</td>
                            <td>01 Mei 2026</td>
                            <td>08 Mei 2026</td>
                            <td><span class="status-badge bg-blue">Aktif (3 Hari Lagi)</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

</body>
</html>