<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .card-form { background: white; padding: 30px; border-radius: 12px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #334155; }
    
    .form-group input[type="text"], .form-group select { 
        width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; 
        box-sizing: border-box; font-family: 'Poppins', sans-serif; transition: 0.2s; 
    }
    .form-group input:focus, .form-group select:focus { 
        outline: none; border-color: #005ce6; box-shadow: 0 0 0 3px rgba(0, 92, 230, 0.1); 
    }
    
    .btn-success { background: #f59e0b; width: 100%; margin-top: 10px; font-size: 15px; padding: 14px; border: none; color: white; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 500; transition: 0.2s; }
    .btn-success:hover { background: #d97706; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(245, 158, 11, 0.3); }
    
    .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 16px; background-color: #f8fafc; color: #475569; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 6px; border: 1px solid #e2e8f0; transition: 0.2s; }
    .btn-back:hover { background-color: #f1f5f9; color: #005ce6; border-color: #cbd5e1; transform: translateX(-3px); }
</style>

<div class="card-form">
    <a href="<?= base_url('admin/eksemplar') ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <h2 style="margin-bottom: 25px; font-size: 24px; color: #1e293b; font-weight: 600;">Edit Data Eksemplar</h2>

    <form action="<?= base_url('admin/eksemplar/update/' . $eksemplar['id_eksemplar']) ?>" method="POST">
        <?= csrf_field(); ?>

        <div class="form-group">
            <label for="id_buku">Pilih Buku (Judul & ISBN)</label>
            <select name="id_buku" id="id_buku" required>
                <option value="">-- Silakan Pilih Judul Buku --</option>
                <?php foreach($buku as $b): ?>
                    <option value="<?= esc($b['id_buku']) ?>" <?= ($b['id_buku'] == ($eksemplar['id_buku'] ?? '')) ? 'selected' : '' ?>>
                        <?= esc($b['judul_buku']) ?> (ISBN: <?= esc($b['isbn']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kode_eksemplar">Kode Fisik Eksemplar</label>
            <input type="text" name="kode_eksemplar" id="kode_eksemplar" value="<?= esc($eksemplar['kode_eksemplar'] ?? '') ?>" placeholder="Contoh: HJN-001" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="kondisi">Kondisi Buku</label>
            <select name="kondisi" id="kondisi" required>
                <?php $kondisi = $eksemplar['kondisi'] ?? 'Baik'; ?>
                <option value="Baik" <?= ($kondisi == 'Baik') ? 'selected' : '' ?>>Baik</option>
                <option value="Rusak Ringan" <?= ($kondisi == 'Rusak Ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= ($kondisi == 'Rusak Berat') ? 'selected' : '' ?>>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status Saat Ini</label>
            <select name="status" id="status" required>
                <?php $status = $eksemplar['status'] ?? 'Tersedia'; ?>
                <option value="Tersedia" <?= ($status == 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                <option value="Dipinjam" <?= ($status == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                <option value="Hilang" <?= ($status == 'Hilang') ? 'selected' : '' ?>>Hilang</option>
            </select>
        </div>

        <button type="submit" class="btn-success">
            <i class="fa-solid fa-floppy-disk" style="margin-right: 5px;"></i> Simpan Perubahan
        </button>
    </form>
</div>

<?= $this->endSection(); ?>