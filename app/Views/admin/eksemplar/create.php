<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .form-container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); width: 100%; max-width: 600px; margin: 0 auto; }
    .form-container h2 { color: #2c3e50; font-size: 22px; font-weight: 600; margin-top: 0; margin-bottom: 25px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #495057; font-size: 14px; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; box-sizing: border-box; transition: border-color 0.3s; }
    .form-control:focus { border-color: #0d6efd; outline: none; }
    .btn-primary { padding: 12px 25px; background: #0d6efd; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: 0.3s; }
    .btn-primary:hover { background: #0b5ed7; }
    .btn-secondary { padding: 12px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; margin-right: 10px; display: inline-block; transition: 0.3s; }
    .btn-secondary:hover { background: #5a6268; }
    .btn-group-custom { margin-top: 30px; }
</style>

<div class="form-container">
    <h2>Tambah Eksemplar Baru</h2>

    <form action="<?= base_url('admin/eksemplar/store') ?>" method="POST">
        <?= csrf_field(); ?>

        <div class="form-group">
            <label for="id_buku">Pilih Buku (Judul & ISBN)</label>
            <select name="id_buku" id="id_buku" class="form-control" required>
                <option value="">-- Pilih Judul Buku --</option>
                <?php foreach($buku as $b): ?>
                    <option value="<?= esc($b['id_buku']) ?>">
                        <?= esc($b['judul_buku']) ?> (ISBN: <?= esc($b['isbn']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kode_eksemplar">Kode Fisik Eksemplar</label>
            <input type="text" name="kode_eksemplar" id="kode_eksemplar" class="form-control" placeholder="Contoh: HJN-001" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="kondisi">Kondisi Buku</label>
            <select name="kondisi" id="kondisi" class="form-control" required>
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
            </select>
        </div>

        <div class="btn-group-custom">
            <a href="<?= base_url('admin/eksemplar') ?>" class="btn-secondary">Kembali</a>
            <button type="submit" class="btn-primary">Simpan Data</button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>