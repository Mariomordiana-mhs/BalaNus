<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .table-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; margin: 0;; }
    .table-container h2 { color: #2c3e50; font-size: 22px; font-weight: 600; margin-top: 0; margin-bottom: 25px; }
    .btn-primary { display: inline-block; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background 0.3s; margin-bottom: 20px; }
    .btn-primary:hover { background: #0b5ed7; }
    .btn-edit { background: #ffc107; color: #000; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; margin-right: 5px; transition: 0.3s; }
    .btn-edit:hover { background: #e0a800; }
    .btn-delete { background: #dc3545; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; transition: 0.3s; }
    .btn-delete:hover { background: #c82333; }
    .alert-success { background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { padding: 15px 10px; text-align: left; border-bottom: 1px solid #edf2f6; }
    th { color: #6c757d; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    td { color: #495057; vertical-align: middle; }
    tr:hover { background-color: #f8f9fa; }
    .cover-img { width: 45px; height: 65px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); background-color: #e9ecef; }
</style>

<div class="table-container">
    <h2>Kelola Data Buku</h2>
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('admin/buku/create') ?>" class="btn-primary">+ Tambah Buku Baru</a>

    <table>
        <thead>
            <tr>
                <th>No</th><th>Cover</th><th>ISBN</th><th>Judul Buku</th><th>Penulis</th><th>Stok</th><th>Aksi</th> 
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($buku as $b): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><img src="<?= base_url('uploads/covers/' . esc($b['cover'])) ?>" class="cover-img" alt="Cover"></td>
                <td><?= esc($b['isbn']) ?></td>
                <td><strong><?= esc($b['judul_buku']) ?></strong></td>
                <td><?= esc($b['penulis']) ?></td>
                <td><?= esc($b['stok']) ?></td>
                <td>
                    <a href="<?= base_url('admin/buku/edit/' . $b['id_buku']) ?>" class="btn-edit">Edit</a>
                    <a href="<?= base_url('admin/buku/delete/' . $b['id_buku']) ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>