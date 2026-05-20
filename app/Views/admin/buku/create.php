<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .input-group { display: flex; gap: 10px; }
        .btn { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-success { background: #28a745; width: 100%; margin-top: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Tambah Data Buku</h2>
    
    <?php if(session('errors')) : ?>
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach(session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('admin/buku/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>ISBN (Masukkan ISBN, klik Cari)</label>
            <div class="input-group">
                <input type="text" id="isbn" name="isbn" value="<?= old('isbn') ?>" placeholder="Contoh: 9780134190440" required>
                <button type="button" class="btn" id="btnCariIsbn">Cari Auto (API)</button>
            </div>
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?= old('judul_buku') ?>" required>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" id="penulis" name="penulis" value="<?= old('penulis') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" id="penerbit" name="penerbit" value="<?= old('penerbit') ?>">
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= old('kategori') ?>" placeholder="Misal: Fiksi, Komputer...">
        </div>

        <div class="form-group">
            <label>Stok Awal</label>
            <input type="number" name="stok" value="<?= old('stok') ?: 1 ?>" min="1" required>
        </div>

        <div class="form-group">
            <label>Upload Cover (Opsional jika API berhasil ditarik)</label>
            <input type="file" name="cover_buku" accept="image/*">
            <input type="hidden" id="cover_url" name="cover_url">
            <div id="preview_cover" style="margin-top: 10px;"></div>
        </div>

        <button type="submit" class="btn-success btn">Simpan Buku</button>
        <a href="<?= base_url('admin/buku') ?>" style="display:block; text-align:center; margin-top:15px; color:#666;">Batal</a>
    </form>
</div>

<script>
document.getElementById('btnCariIsbn').addEventListener('click', function() {
    let isbn = document.getElementById('isbn').value;
    if(!isbn) return alert('Masukkan nomor ISBN terlebih dahulu!');

    let btn = this;
    btn.innerHTML = 'Mencari...';
    btn.disabled = true;

    // Hit API Open Library
    fetch(`https://openlibrary.org/api/books?bibkeys=ISBN:${isbn}&format=json&jscmd=data`)
        .then(response => response.json())
        .then(data => {
            let bookData = data[`ISBN:${isbn}`];
            if(bookData) {
                document.getElementById('judul_buku').value = bookData.title || '';
                document.getElementById('penulis').value = bookData.authors ? bookData.authors[0].name : '';
                document.getElementById('penerbit').value = bookData.publishers ? bookData.publishers[0].name : '';
                
                // Ambil gambar cover dari API jika ada
                if(bookData.cover && bookData.cover.large) {
                    document.getElementById('cover_url').value = bookData.cover.large;
                    document.getElementById('preview_cover').innerHTML = `<img src="${bookData.cover.large}" style="height: 150px; border-radius: 5px;"> <p style="font-size:12px; color:green;">Cover ditarik otomatis dari API!</p>`;
                }
                alert('Data buku berhasil ditemukan di Open Library!');
            } else {
                alert('Buku tidak ditemukan di Open Library.');
            }
        })
        .catch(error => alert('Terjadi kesalahan jaringan!'))
        .finally(() => {
            btn.innerHTML = 'Cari Auto (API)';
            btn.disabled = false;
        });
});
</script>

</body>
</html>