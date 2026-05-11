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
        
        /* MAIN CONTENT */
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; }
        .topbar { background: var(--white); height: 70px; padding: 0 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .header-title h1 { font-size: 20px; font-weight: 600; color: var(--text-dark); }
        .dashboard-body { padding: 30px; }
        
        /* FORM SEARCH */
        .search-container { background: var(--white); padding: 20px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; }
        .search-box { display: flex; flex: 1; max-width: 600px; background: var(--bg-color); border-radius: 8px; padding: 5px 15px; align-items: center; border: 1px solid var(--border); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; padding: 10px; font-size: 14px; }
        .btn-search { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 500; transition: 0.3s; }
        .btn-search:hover { background: var(--primary-dark); }

        /* GRID KATALOG BUKU */
        .katalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }
        .buku-card { background: var(--white); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; transition: 0.3s; display: flex; flex-direction: column; }
        .buku-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .buku-cover { height: 180px; background: #e0e7ff; display: flex; justify-content: center; align-items: center; font-size: 50px; color: var(--primary); }
        .buku-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .buku-kategori { font-size: 11px; color: var(--primary); font-weight: 600; text-transform: uppercase; margin-bottom: 5px; }
        .buku-judul { font-size: 15px; font-weight: 600; color: var(--text-dark); margin-bottom: 5px; line-height: 1.4; }
        .buku-penulis { font-size: 12px; color: var(--text-muted); margin-bottom: 10px; }
        .buku-stok { font-size: 12px; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-bottom: 15px; font-weight: 500; }
        .stok-ada { background: #e6ffef; color: #28a745; }
        .stok-habis { background: #ffebee; color: #d32f2f; }
        .btn-pinjam { text-align: center; display: block; width: 100%; padding: 10px; border-radius: 6px; font-size: 13px; font-weight: 500; text-decoration: none; margin-top: auto; }
        .btn-pinjam-active { background: #f0f4ff; color: var(--primary); border: 1px solid #dce4ff; transition: 0.3s; }
        .btn-pinjam-active:hover { background: var(--primary); color: white; }
        .btn-pinjam-disabled { background: #f5f5f5; color: #aaa; cursor: not-allowed; }

        /* MODAL POP-UP CUSTOM */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;
            backdrop-filter: blur(2px);
        }
        .modal-box {
            background: white; padding: 30px; border-radius: 12px; text-align: center;
            max-width: 400px; width: 90%; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;

            -webkit-user-select: none; 
            -moz-user-select: none; 
            -ms-user-select: none; 
            user-select: none;
        }
        @keyframes modalFadeIn { 
            from { opacity: 0; transform: translateY(-20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        /* Icon menggunakan warna primary (biru) */
        .modal-icon { font-size: 50px; color: var(--primary); margin-bottom: 15px; }
        .modal-title { font-size: 18px; font-weight: 600; margin-bottom: 10px; color: var(--text-dark); }
        .modal-text { font-size: 13px; color: var(--text-muted); margin-bottom: 25px; line-height: 1.5; }
        .modal-actions { display: flex; justify-content: center; gap: 15px; }
        .btn-modal { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 500; text-decoration: none; font-size: 13px; transition: 0.2s; }
        .btn-modal-cancel { background: #f5f7fb; color: #333; border: 1px solid var(--border); }
        .btn-modal-cancel:hover { background: #eaedf2; }
        /* Tombol konfirmasi menggunakan warna primary (biru) */
        .btn-modal-confirm { background: var(--primary); color: white; }
        .btn-modal-confirm:hover { background: var(--primary-dark); }
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
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-book-bookmark"></i> Peminjaman & Antrean</a>
            <a href="javascript:void(0)" class="menu-item"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Baca</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="header-title">
                <h1>Katalog Koleksi Buku</h1>
            </div>
        </header>

        <div class="dashboard-body">
            
            <div class="search-container">
                <form action="<?= base_url('katalog') ?>" method="GET" style="width: 100%; display: flex; gap: 15px;">
                    <div class="search-box">
                        <i class="fa-solid fa-search" style="color: var(--text-muted);"></i>
                        <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari berdasarkan Judul, Penulis, ISBN, atau Kategori...">
                    </div>
                    <button type="submit" class="btn-search">Cari Buku</button>
                    <?php if(!empty($keyword)): ?>
                        <a href="<?= base_url('katalog') ?>" style="display: flex; align-items: center; padding: 0 15px; color: #d9534f; text-decoration: none; font-size: 14px;"><i class="fa-solid fa-xmark" style="margin-right: 5px;"></i> Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i> <?= session()->getFlashdata('error'); ?></span>
                    <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #721c24;">&times;</button>
                </div>
            <?php endif; ?>

            <?php if(!empty($keyword)): ?>
                <p style="margin-bottom: 20px; font-size: 14px; color: var(--text-muted);">
                    Menampilkan hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong>
                </p>
            <?php endif; ?>

            <div class="katalog-grid">
                <?php if(empty($buku)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: var(--white); border-radius: 12px; border: 1px solid var(--border);">
                        <i class="fa-solid fa-box-open" style="font-size: 40px; color: #ccc; margin-bottom: 15px;"></i>
                        <h3 style="color: var(--text-muted);">Buku tidak ditemukan</h3>
                        <p style="font-size: 13px; color: #aaa;">Coba gunakan kata kunci lain untuk mencari.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($buku as $b): ?>
                        <div class="buku-card">
                            <div class="buku-cover">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="buku-body">
                                <span class="buku-kategori"><?= esc($b['kategori'] ?? 'Umum') ?></span>
                                <h3 class="buku-judul"><?= esc($b['judul_buku']) ?></h3>
                                <p class="buku-penulis"><?= esc($b['penulis'] ?? 'Penulis Tidak Diketahui') ?></p>
                                <p style="font-size: 11px; color: #aaa; margin-top: -5px; margin-bottom: 10px;">ISBN: <?= esc($b['isbn'] ?? '-') ?></p>
                                
                                <div>
                                    <?php if($b['stok'] > 0): ?>
                                        <span class="buku-stok stok-ada">Tersedia: <?= esc($b['stok']) ?> Buku</span>
                                        <a href="#" class="btn-pinjam btn-pinjam-active" onclick="tampilkanModalPinjam('<?= base_url('peminjaman/ajukan/' . $b['id_buku']) ?>'); return false;">Ajukan Peminjaman</a>
                                    <?php else: ?>
                                        <span class="buku-stok stok-habis">Stok Habis</span>
                                        <a href="#" class="btn-pinjam btn-pinjam-disabled" onclick="return false;">Tidak Tersedia</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <div id="modalPinjam" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-icon"><i class="fa-solid fa-book-bookmark"></i></div>
            <h3 class="modal-title">Ajukan Peminjaman?</h3>
            <p class="modal-text">Apakah Anda yakin ingin mengajukan peminjaman untuk buku ini? Pastikan Anda membacanya dan mengembalikannya tepat waktu ya!</p>
            <div class="modal-actions">
                <button class="btn-modal btn-modal-cancel" onclick="tutupModalPinjam()">Batal</button>
                <a href="#" id="btnKonfirmasiPinjam" class="btn-modal btn-modal-confirm">Ya, Pinjam Buku</a>
            </div>
        </div>
    </div>

    <script>
        function tampilkanModalPinjam(urlAjukan) {
            // Tampilkan background gelap dan kotak modal
            document.getElementById('modalPinjam').style.display = 'flex';
            // Sisipkan URL aksi ke tombol "Ya, Pinjam"
            document.getElementById('btnKonfirmasiPinjam').href = urlAjukan;
        }

        function tutupModalPinjam() {
            // Sembunyikan kembali modal
            document.getElementById('modalPinjam').style.display = 'none';
        }
    </script>
</body>
</html>