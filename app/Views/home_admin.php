<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BalaNus</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary: #005ce6;
            --primary-dark: #0044b3;
            --bg-color: #f5f7fb;
            --text-dark: #333;
            --text-muted: #888;
            --white: #ffffff;
            --border: #eaedf2;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-dark); display: flex; min-height: 100vh; overflow-x: hidden; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary); color: var(--white); display: flex; flex-direction: column; position: fixed; height: 100vh; overflow-y: auto; }
        .logo-area { text-align: center; padding: 30px 20px; }
        .logo-area i { font-size: 50px; margin-bottom: 10px; }
        .logo-area h2 { font-size: 24px; font-weight: 600; letter-spacing: 1px; }
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
        
        /* TOPBAR */
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .header-title p { font-size: 12px; color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .search-bar { background: var(--bg-color); border-radius: 20px; padding: 8px 15px; display: flex; align-items: center; width: 250px; }
        .search-bar input { border: none; background: transparent; outline: none; width: 100%; font-size: 13px; margin-left: 10px; }
        .notifications { position: relative; cursor: pointer; color: var(--text-muted); font-size: 18px; }
        .badge { position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; font-weight: bold; }
        
        /* DASHBOARD CONTENT */
        .dashboard-body { padding: 30px; }
        
        /* CARDS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: var(--white); padding: 20px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 20px; }
        .stat-info h3 { font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .stat-info h2 { font-size: 24px; color: var(--primary); font-weight: 600; margin-top: 5px; }
        .stat-card .link { font-size: 12px; color: var(--primary); text-decoration: none; margin-top: 10px; display: inline-block; font-weight: 500;}

        /* MIDDLE & BOTTOM GRID */
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 20px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .panel-header h3 { font-size: 16px; font-weight: 600; }
        .panel-header a { font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 500; }

        /* TABLES */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border); font-weight: 500;}
        td { padding: 15px 10px; font-size: 13px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .user-td { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 30px; height: 30px; background: #e0e7ff; color: var(--primary); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: bold; }
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 500; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
        .bg-red { background: #ffeaea; color: #d9534f; }

        /* LISTS */
        .book-list-item { display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--border); }
        .book-cover { width: 50px; height: 70px; background: #ddd; border-radius: 4px; object-fit: cover; }
        .book-details h4 { font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .book-details p { font-size: 12px; color: var(--text-muted); margin-bottom: 5px; }
        .late-text { font-size: 11px; color: #d9534f; font-weight: 500; }
        .borrower-info { text-align: right; margin-left: auto; font-size: 12px; color: var(--text-muted); }

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
            <a href="javascript:void(0)" class="menu-item active"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>
            
            <div class="menu-title">KOLEKSI</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-book"></i> Buku</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-cubes"></i> Eksemplar</a>
            
            <div class="menu-title">ANGGOTA</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-users"></i> Anggota</a>
            
            <div class="menu-title">TRANSAKSI</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-right-left"></i> Peminjaman</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-rotate-left"></i> Pengembalian</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-money-bill-wave"></i> Denda</a>
            
            <div class="menu-title">LAPORAN</div>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-chart-simple"></i> Laporan</a>
        </div>

        <div class="user-profile-sidebar" onclick="window.location.href='<?= base_url('logout') ?>'">
            <i class="fa-solid fa-user"></i>
            <div class="user-info">
                <p><?= session()->get('username') ?? 'Admin' ?></p>
                <span>Pustakawan (Logout)</span>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="header-title">
                <h1>Dashboard</h1>
                <p>Selamat datang kembali, <?= session()->get('username') ?? 'Admin' ?>!</p>
            </div>
            <div class="topbar-right">
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted)"></i>
                    <input type="text" placeholder="Cari buku, anggota, ISBN...">
                </div>
                <div class="notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge">3</span>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <div class="dashboard-body">
            
            <!-- 4 CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Total Buku</h3>
                            <h2>1.248</h2>
                        </div>
                        <a href="javascript:void(0)" class="link">Lihat semua ></a>
                    </div>
                    <div class="stat-icon" style="background:#e0e7ff; color:var(--primary);"><i class="fa-solid fa-book"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Total Eksemplar</h3>
                            <h2 style="color:#28a745;">2.562</h2>
                        </div>
                        <a href="javascript:void(0)" class="link">Lihat semua ></a>
                    </div>
                    <div class="stat-icon" style="background:#e6ffef; color:#28a745;"><i class="fa-solid fa-cubes"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Peminjaman Aktif</h3>
                            <h2 style="color:#fd7e14;">156</h2>
                        </div>
                        <a href="javascript:void(0)" class="link">Lihat semua ></a>
                    </div>
                    <div class="stat-icon" style="background:#fff4e6; color:#fd7e14;"><i class="fa-solid fa-user-group"></i></div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="stat-info">
                            <h3>Denda Belum Dibayar</h3>
                            <h2 style="color:#d9534f; font-size:20px;">Rp 2.450.000</h2>
                        </div>
                        <a href="javascript:void(0)" class="link">Lihat semua ></a>
                    </div>
                    <div class="stat-icon" style="background:#ffeaea; color:#d9534f;"><i class="fa-solid fa-wallet"></i></div>
                </div>
            </div>

            <!-- TABLE & LIST -->
            <div class="content-grid">
                <!-- Tabel Peminjaman -->
                <div class="card-panel">
                    <div class="panel-header">
                        <h3>Peminjaman Terbaru</h3>
                        <a href="javascript:void(0)">Lihat semua</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="user-td">
                                        <div class="user-avatar">B</div>
                                        <div>Mario Mordiana<br><span style="color:#888;font-size:11px;">A001</span></div>
                                    </div>
                                </td>
                                <td>Atomic Habits<br><span style="color:#888;font-size:11px;">James Clear</span></td>
                                <td>20 Mei 2026</td>
                                <td>27 Mei 2026</td>
                                <td><span class="status-badge bg-blue">Dipinjam</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="user-td">
                                        <div class="user-avatar">D</div>
                                        <div>Muhammad Yunus Alfian<br><span style="color:#888;font-size:11px;">A004</span></div>
                                    </div>
                                </td>
                                <td>Negeri 5 Menara<br><span style="color:#888;font-size:11px;">Ahmad Fuadi</span></td>
                                <td>17 Mei 2026</td>
                                <td>24 Mei 2026</td>
                                <td><span class="status-badge bg-red">Terlambat</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- List Buku Terlambat -->
                <div class="card-panel">
                    <div class="panel-header">
                        <h3>Buku Terlambat</h3>
                        <a href="javascript:void(0)">Lihat semua</a>
                    </div>
                    <div class="book-list-item">
                        <div class="book-cover" style="background:#1e3c72; display:flex; align-items:center; justify-content:center; color:white;"><i class="fa-solid fa-book"></i></div>
                        <div class="book-details">
                            <h4>Negeri 5 Menara</h4>
                            <p>Ahmad Fuadi</p>
                            <span class="late-text">Terlambat 3 hari</span>
                        </div>
                        <div class="borrower-info">
                            Rudi Hartono<br>A005
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS (SUDAH DIPERBAIKI UKURANNYA) -->
            <div class="content-grid">
                <div class="card-panel" style="height: 350px; display: flex; flex-direction: column;">
                    <div class="panel-header" style="margin-bottom: 10px;">
                        <h3>Statistik Peminjaman <span style="font-size:12px;font-weight:400;color:var(--text-muted);">(6 Bulan Terakhir)</span></h3>
                    </div>
                    <!-- Pembungkus ketat untuk Canvas agar tidak melar -->
                    <div style="position: relative; flex-grow: 1; min-height: 0;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                
                <div class="card-panel" style="height: 350px; display: flex; flex-direction: column;">
                    <div class="panel-header" style="margin-bottom: 10px;">
                        <h3>Ringkasan Denda</h3>
                    </div>
                    <!-- Pembungkus ketat untuk Canvas agar tidak melar -->
                    <div style="position: relative; flex-grow: 1; min-height: 0;">
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Script Konfigurasi Grafik Chart.js -->
    <script>
        // Data Grafik Batang
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Des 2025', 'Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'Mei 2026'],
                datasets: [
                    { label: 'Peminjaman', data: [140, 200, 190, 160, 140, 120], backgroundColor: '#005ce6', borderRadius: 4 },
                    { label: 'Pengembalian', data: [90, 130, 140, 120, 100, 80], backgroundColor: '#a3c4f9', borderRadius: 4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
        });

        // Data Grafik Donut
        const ctxDonut = document.getElementById('donutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Belum Dibayar', 'Sebagian Dibayar', 'Lunas'],
                datasets: [{
                    data: [70, 21, 9],
                    backgroundColor: ['#ff4d4d', '#ffc107', '#28a745'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } }, cutout: '70%' }
        });
    </script>
</body>
</html>