<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Peminjaman & Antrean - BalaNus') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --primary: #005ce6; --primary-dark: #0044b3; --bg-color: #f5f7fb; --text-dark: #333; --text-muted: #888; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-dark); display: flex; min-height: 100vh; overflow-x: hidden; }
        
        /* SIDEBAR (Sama persis dengan home_member) */
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
        .notifications { position: relative; cursor: pointer; color: var(--text-muted); font-size: 18px; }
        
        /* PAGE CONTENT STYLES */
        .page-content { padding: 30px; }
        .section-title { font-size: 18px; font-weight: 600; margin-bottom: 15px; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 10px; font-size: 13px; color: var(--text-muted); border-bottom: 2px solid var(--border); font-weight: 600; text-transform: uppercase; }
        td { padding: 15px 10px; font-size: 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        
        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; text-align: center; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
        .bg-warning { background: #fff8e6; color: #f5a623; }
        
        .btn-action { padding: 8px 15px; border-radius: 8px; font-size: 13px; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: 0.2s; }
        .btn-cancel { background: #fee2e2; color: #dc2626; }
        .btn-cancel:hover { background: #fca5a5; }
        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
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
            <a href="<?= base_url('peminjaman') ?>" class="menu-item active"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
            <a href="<?= base_url('riwayat') ?>" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
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
                <h1>Peminjaman & Antrean</h1>
                <p>Pantau status buku yang sedang Anda pinjam dan antrean pengajuan Anda.</p>
            </div>
            <div class="topbar-right">
                <div class="notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge" style="display: none;">0</span>
                </div>
            </div>
        </header>

        <div class="page-content">

            <?php if(session()->getFlashdata('success')): ?>
                <div style="background-color: #dcfce7; color: #16a34a; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                    <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <h3 class="section-title"><i class="fa-solid fa-book-open" style="color: var(--primary);"></i> Sedang Dipinjam</h3>
            <div class="card-panel">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Judul Buku</th>
                            <th width="25%">Tanggal Pinjam</th>
                            <th width="20%">Tenggat Waktu</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($dipinjam)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-book"></i>
                                        <p>Anda belum meminjam buku apa pun saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach($dipinjam as $d): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td style="font-weight: 600; color: var(--text-dark);"><?= esc($d['judul_buku']) ?></td>
                                <td><?= date('d M Y', strtotime($d['tgl_pengajuan'])) ?></td>
                                <td style="color: #dc2626; font-weight: 500;"><?= date('d M Y', strtotime($d['tenggat_waktu'])) ?></td>
                                <td><span class="status-badge bg-blue">Dipinjam</span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h3 class="section-title"><i class="fa-solid fa-hourglass-half" style="color: #f5a623;"></i> Menunggu Persetujuan Admin</h3>
            <div class="card-panel">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Judul Buku</th>
                            <th width="25%">Tanggal Pengajuan</th>
                            <th width="15%">Status</th>
                            <th width="15%" style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($antrean)): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fa-regular fa-clock"></i>
                                        <p>Tidak ada pengajuan buku dalam antrean.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach($antrean as $a): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td style="font-weight: 600; color: var(--text-dark);"><?= esc($a['judul_buku']) ?></td>
                                <td><?= date('d M Y', strtotime($a['tgl_pengajuan'])) ?></td>
                                <td><span class="status-badge bg-warning">Menunggu ACC</span></td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn-action btn-cancel" onclick="konfirmasiBatal(<?= $a['id_peminjaman'] ?>)">Batalkan</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <script>
        function konfirmasiBatal(idPeminjaman) {
            Swal.fire({
                title: 'Batalkan Pengajuan?',
                text: "Anda yakin ingin membatalkan antrean peminjaman buku ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke URL fungsi batal di controller
                    window.location.href = '<?= base_url('peminjaman/batal/') ?>' + idPeminjaman;
                }
            })
        }
    </script>
</body>
</html>