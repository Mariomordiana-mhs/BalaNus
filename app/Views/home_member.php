<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - BalaNus</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
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
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .search-bar { background: var(--bg-color); border-radius: 20px; padding: 8px 15px; display: flex; align-items: center; width: 250px; }
        .search-bar input { border: none; background: transparent; outline: none; width: 100%; font-size: 13px; margin-left: 10px; }
        .notifications { position: relative; cursor: pointer; color: var(--text-muted); font-size: 18px; }
        .badge { position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; font-weight: bold; }
        
        .dashboard-body { padding: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: var(--white); padding: 20px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 20px; }
        .stat-info h3 { font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .stat-info h2 { font-size: 24px; color: var(--primary); font-weight: 600; margin-top: 5px; }
        
        /* GRID BAWAH */
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 20px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .panel-header h3 { font-size: 16px; font-weight: 600; }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border); font-weight: 500;}
        td { padding: 15px 10px; font-size: 13px; border-bottom: 1px solid var(--border); }
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; text-align: center; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
        .bg-warning { background: #fff8e6; color: #f5a623; }
        .bg-green { background: #e6ffef; color: #28a745; }
        
        /* LIST BUKU */
        .book-item { display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border); }
        .book-item:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .book-cover { width: 45px; height: 65px; background: #e0e7ff; border-radius: 4px; display: flex; justify-content: center; align-items: center; color: var(--primary); font-size: 20px;}
        .book-info h4 { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
        .book-info p { font-size: 11px; color: var(--text-muted); margin-bottom: 5px; }
        .book-action a { font-size: 11px; color: var(--primary); font-weight: 500; text-decoration: none; }
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
            <a href="javascript:void(0)" class="menu-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <div class="menu-title">PERPUSTAKAAN</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            <div class="menu-title">AKTIVITAS SAYA</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
        </div>

        <div class="user-profile-sidebar" onclick="window.location.href='<?= base_url('logout') ?>'">
            <i class="fa-solid fa-user"></i>
            <div class="user-info">
                <p><?= session()->get('username') ?? 'Mario Mordiana' ?></p>
                <span>Anggota Aktif (Logout)</span>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <?php 
                date_default_timezone_set('Asia/Jakarta');
                $jam = date('H');
                if ($jam >= 5 && $jam < 11) { $salam = 'Pagi'; }
                elseif ($jam >= 11 && $jam < 15) { $salam = 'Siang'; }
                elseif ($jam >= 15 && $jam < 18) { $salam = 'Sore'; }
                else { $salam = 'Malam'; }
                $nama_user = session()->get('username') ?? 'Member';
            ?>
            <div class="header-title">
                <h1>Selamat <?= $salam ?>, <?= $nama_user ?>!</h1>
                <p>Selamat membaca buku pilihanmu hari ini.</p>
            </div>
            <div class="topbar-right">
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted)"></i>
                    <input type="text" placeholder="Cari judul, ISBN, penulis...">
                </div>
                <div class="notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge" style="display: none;">0</span>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <div class="dashboard-body">

            <!-- 3 CARDS KHUSUS MEMBER -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Menunggu Persetujuan</h3>
                            <h2 style="color:#f5a623;">1 Pengajuan</h2>
                        </div>
                    </div>
                    <div class="stat-icon" style="background:#fff8e6; color:#f5a623;"><i class="fa-solid fa-hourglass-half"></i></div>
                </div>
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
            </div>

            <!-- GRID KONTEN BAWAH -->
            <div class="content-grid">
                <div class="card-panel">
                    <div class="panel-header">
                        <h3>Status Peminjaman Anda</h3>
                        <a href="javascript:void(0)" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 500;">Lihat Semua</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Info Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 600;">Harry Potter: Sorcerer's Stone</td>
                                <td style="font-size: 12px;">Diajukan: 06 Mei 2026</td>
                                <td><span class="status-badge bg-warning">Menunggu ACC</span></td>
                                <td><a href="javascript:void(0)" style="color:#d9534f; font-size:12px; text-decoration:none;">Batalkan</a></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Filosofi Teras</td>
                                <td style="font-size: 12px;">Tenggat: 08 Mei 2026</td>
                                <td><span class="status-badge bg-blue">Dipinjam</span></td>
                                <td><a href="javascript:void(0)" style="color:var(--text-muted); font-size:12px; text-decoration:none;">Detail</a></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;">Negeri 5 Menara</td>
                                <td style="font-size: 12px;">Dikembalikan: 03 Mei 2026</td>
                                <td><span class="status-badge bg-green">Selesai</span></td>
                                <td><a href="javascript:void(0)" style="color:var(--text-muted); font-size:12px; text-decoration:none;">Detail</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-panel">
                    <div class="panel-header">
                        <h3>Tambahan Terbaru</h3>
                        <a href="javascript:void(0)" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 500;">Katalog</a>
                    </div>
                    <div class="book-item">
                        <div class="book-cover"><i class="fa-solid fa-book"></i></div>
                        <div class="book-info">
                            <h4>Clean Code</h4>
                            <p>Robert C. Martin • Tersedia</p>
                            <div class="book-action"><a href="javascript:void(0)">Pinjam</a></div>
                        </div>
                    </div>
                    <div class="book-item">
                        <div class="book-cover" style="background:#fff4e6; color:#fd7e14;"><i class="fa-solid fa-book"></i></div>
                        <div class="book-info">
                            <h4>The Pragmatic Programmer</h4>
                            <p>David Thomas • Dipinjam</p>
                            <div class="book-action"><span style="font-size:11px; color:#f5a623;">Ingatkan Saya</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>