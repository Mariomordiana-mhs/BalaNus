<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: 'Poppins', sans-serif; padding: 20px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; font-size: 14px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-success { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Edit Data Buku</h2>
    
    <?php if(session('errors')) : ?>
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach(session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('admin/buku/update/' . $buku['id_buku']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="isbn" value="<?= old('isbn', $buku['isbn']) ?>" required>
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul_buku" value="<?= old('judul_buku', $buku['judul_buku']) ?>" required>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="<?= old('penulis', $buku['penulis']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="<?= old('penerbit', $buku['penerbit']) ?>">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= old('kategori', $buku['kategori']) ?>">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= old('stok', $buku['stok']) ?>" min="0" required>
        </div>

        <div class="form-group">
            <label>Cover Saat Ini</label>
            <br>
            <img src="<?= base_url('uploads/covers/' . $buku['cover']) ?>" style="height: 100px; border-radius: 5px; margin-bottom: 10px;">
            <label>Ganti Cover (Kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="cover_buku" accept="image/*">
        </div>

        <button type="submit" class="btn-success">Update Buku</button>
        <a href="<?= base_url('admin/buku') ?>" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Batal</a>
    </form>
</div>

</body>
</html>