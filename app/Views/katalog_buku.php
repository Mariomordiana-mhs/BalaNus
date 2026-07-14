<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Katalog Buku - BalaNus') ?></title>
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
        
        /* MAIN CONTENT & TOPBAR */
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .header-title p { font-size: 12px; color: var(--text-muted); }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .notifications { position: relative; cursor: pointer; color: var(--text-muted); font-size: 18px; }
        
        /* KATALOG STYLES */
        .catalog-container { padding: 30px; }
        .page-title { font-size: 20px; font-weight: 600; margin-bottom: 24px; color: var(--text-dark); }
        
        /* SEARCH SECTION */
        .search-section { background: var(--white); padding: 15px; border-radius: 12px; border: 1px solid var(--border); display: flex; gap: 15px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .search-box { flex: 1; background: var(--bg-color); border-radius: 8px; display: flex; align-items: center; padding: 10px 15px; }
        .search-box i { color: var(--text-muted); font-size: 16px; }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; margin-left: 10px; font-size: 14px; color: var(--text-dark); }
        .btn-search { background: var(--primary); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; transition: 0.2s; }
        .btn-search:hover { background: var(--primary-dark); }

        /* GRID BUKU */
        .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; }
        .book-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 20px; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        
        .book-card-cover { height: 180px; display: flex; justify-content: center; align-items: center; margin-bottom: 15px; }
        .book-card-cover img { max-height: 100%; max-width: 100%; object-fit: contain; box-shadow: 2px 4px 8px rgba(0,0,0,0.1); border-radius: 4px; }
        
        .book-category { color: var(--primary); font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
        .book-title { font-size: 15px; font-weight: 600; color: var(--text-dark); margin-bottom: 4px; line-height: 1.3; }
        .book-author { font-size: 13px; color: var(--text-muted); margin-bottom: 2px; }
        .book-isbn { font-size: 11px; color: #a0aec0; margin-bottom: 15px; }
        
        .book-footer { margin-top: auto; }
        .book-stock { background: #e6ffef; color: #28a745; padding: 6px 0; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center; margin-bottom: 12px; }
        .book-stock-empty { background: #fee2e2; color: #dc2626; padding: 6px 0; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center; margin-bottom: 12px; }
        
        .book-actions { display: flex; flex-direction: column; gap: 8px; }
        .btn-detail { background: white; border: 1px solid var(--border); color: var(--text-muted); padding: 8px; border-radius: 8px; text-align: center; text-decoration: none; font-size: 13px; font-weight: 500; transition: 0.2s; }
        .btn-detail:hover { background: var(--bg-color); color: var(--text-dark); }
        .btn-pinjam { background: #e0e7ff; color: var(--primary); border: none; padding: 8px; border-radius: 8px; text-align: center; cursor: pointer; font-size: 13px; font-weight: 600; transition: 0.2s; text-decoration: none; display: block; }
        .btn-pinjam:hover { background: #c7d2fe; }
        .btn-disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
        .btn-disabled:hover { background: #f1f5f9; }
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
            <a href="<?= base_url('katalog') ?>" class="menu-item active"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            
            <div class="menu-title">AKTIVITAS SAYA</div>
            <a href="<?= base_url('peminjaman') ?>" class="menu-item"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
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
            <?php 
                date_default_timezone_set('Asia/Jakarta');
                $jam = date('H');
                if ($jam >= 5 && $jam < 11) { $salam = 'Pagi'; }
                elseif ($jam >= 11 && $jam < 15) { $salam = 'Siang'; }
                elseif ($jam >= 15 && $jam < 18) { $salam = 'Sore'; }
                else { $salam = 'Malam'; }
            ?>
            <div class="header-title">
                <h1>Selamat <?= $salam ?>, <?= esc($username ?? 'Member') ?>!</h1>
                <p>Jelajahi ribuan koleksi buku yang ada di BalaNus.</p>
            </div>
            <div class="topbar-right">
                <div class="notifications">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge" style="display: none;">0</span>
                </div>
            </div>
        </header>

        <div class="catalog-container">
            <h2 class="page-title">Katalog Koleksi Buku</h2>

            <form action="<?= base_url('katalog') ?>" method="GET" class="search-section">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="keyword" placeholder="Cari berdasarkan Judul, Penulis, ISBN..." value="<?= esc($keyword ?? '') ?>">
                </div>
                <button type="submit" class="btn-search">Cari Buku</button>
            </form>

            <?php if(session()->getFlashdata('error')): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="catalog-grid">
                
                <?php if(isset($buku) && !empty($buku)): ?>
                    <?php foreach($buku as $b): ?>
                        <div class="book-card">
                            <div class="book-card-cover">
                                <?php if(!empty($b['cover']) && $b['cover'] != 'default.png'): ?>
                                    <img src="<?= base_url('uploads/covers/' . esc($b['cover'])) ?>" alt="<?= esc($b['judul_buku']) ?>">
                                <?php else: ?>
                                    <i class="fa-solid fa-book" style="font-size: 60px; color: #cbd5e1;"></i>
                                <?php endif; ?>
                            </div>

                            <?php if(!empty($b['kategori'])): ?>
                                <span class="book-category"><?= esc($b['kategori']) ?></span>
                            <?php else: ?>
                                <span class="book-category">Umum</span>
                            <?php endif; ?>
                            
                            <h3 class="book-title"><?= esc($b['judul_buku']) ?></h3>
                            <p class="book-author"><?= esc($b['penulis'] ?? 'Penulis Tidak Diketahui') ?></p>
                            <p class="book-isbn">ISBN: <?= esc($b['isbn'] ?? '-') ?></p>

                            <div class="book-footer">
                                <?php if($b['stok'] > 0): ?>
                                    <div class="book-stock">Tersedia: <?= $b['stok'] ?> Buku</div>
                                <?php else: ?>
                                    <div class="book-stock-empty">Stok Habis</div>
                                <?php endif; ?>

                                <div class="book-actions">
                                    <a href="<?= base_url('katalog/detail/' . $b['id_buku']) ?>" class="btn-detail">Lihat Deskripsi</a>
                                    
                                    <?php if($b['stok'] > 0): ?>
                                        <a href="<?= base_url('peminjaman/ajukan/' . $b['id_buku']) ?>" class="btn-pinjam">Ajukan Peminjaman</a>
                                    <?php else: ?>
                                        <button disabled class="btn-pinjam btn-disabled">Tidak Dapat Dipinjam</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px 0; color: var(--text-muted);">
                        <i class="fa-solid fa-box-open" style="font-size: 40px; margin-bottom: 15px; color: #cbd5e1;"></i>
                        <p>Buku yang Anda cari belum tersedia di perpustakaan.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
    
</body>
</html>