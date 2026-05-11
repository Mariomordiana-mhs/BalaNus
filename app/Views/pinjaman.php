<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --primary: #005ce6; --bg-color: #f5f7fb; --white: #ffffff; --border: #eaedf2; --sidebar-width: 260px; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-color); display: flex; }
        .sidebar { width: var(--sidebar-width); background-color: var(--primary); color: white; position: fixed; height: 100vh; padding: 20px; }
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px; }
        .card-panel { background: var(--white); border-radius: 12px; border: 1px solid var(--border); padding: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); font-size: 13px; color: #888; }
        td { padding: 15px; border-bottom: 1px solid var(--border); font-size: 14px; }
        .status-badge { padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .bg-warning { background: #fff8e6; color: #f5a623; }
        .bg-blue { background: #e0e7ff; color: var(--primary); }
        .bg-green { background: #e6ffef; color: #28a745; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>BalaNus</h2>
        <div style="margin-top: 40px;">
            <a href="<?= base_url('member') ?>" style="color:white; text-decoration:none; display:block; margin-bottom:20px;"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="<?= base_url('katalog') ?>" style="color:white; text-decoration:none; display:block; margin-bottom:20px;"><i class="fa-solid fa-magnifying-glass"></i> Katalog Buku</a>
            <a href="<?= base_url('peminjaman/saya') ?>" style="color:white; font-weight:bold; text-decoration:none; display:block; margin-bottom:20px;"><i class="fa-solid fa-book-bookmark"></i> Peminjaman Saya</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="card-panel">
            <h3>Daftar Seluruh Peminjaman Anda</h3>
            <table>
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($riwayat)): ?>
                        <tr><td colspan="4" style="text-align:center;">Belum ada aktivitas peminjaman.</td></tr>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                        <tr>
                            <td><strong><?= esc($r['judul_buku']) ?></strong></td>
                            <td><?= date('d M Y', strtotime($r['tgl_pengajuan'])) ?></td>
                            <td>
                                <?php 
                                $class = ($r['status'] == 'Menunggu ACC') ? 'bg-warning' : (($r['status'] == 'Dipinjam') ? 'bg-blue' : 'bg-green');
                                ?>
                                <span class="status-badge <?= $class ?>"><?= $r['status'] ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('peminjaman/detail/'.$r['id_peminjaman']) ?>" style="color: var(--primary); text-decoration:none;">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>