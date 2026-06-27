<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .card { background: white; padding: 30px; border-radius: 12px; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #334155; }
    input[type="text"], input[type="number"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: 'Poppins', sans-serif; transition: border-color 0.2s; }
    input:focus { outline: none; border-color: var(--primary, #3b82f6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    
    /* CSS untuk input yang tidak bisa diedit (readonly) */
    input[readonly] { background-color: #f8fafc; cursor: not-allowed; color: #94a3b8; }
    
    .btn-success { background: #10b981; color: white; padding: 14px 15px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-size: 15px; font-weight: 500; margin-top: 15px; font-family: 'Poppins', sans-serif; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); transition: all 0.2s; }
    .btn-success:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.3); }
    
    .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #ef4444; }
    .cover-preview { height: 150px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 2px solid #e2e8f0; margin-bottom: 10px; display: block; object-fit: cover; }
    
    /* Style untuk Tombol Kembali (Tidak flat) */
    .btn-back { 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        margin-bottom: 20px; 
        padding: 8px 16px; 
        background-color: #f8fafc; 
        color: #475569; 
        text-decoration: none; 
        font-size: 14px; 
        font-weight: 500; 
        border-radius: 6px; 
        border: 1px solid #e2e8f0; 
        transition: all 0.2s ease; 
    }
    .btn-back:hover { 
        background-color: #f1f5f9; 
        color: var(--primary, #3b82f6); 
        border-color: #cbd5e1; 
        transform: translateX(-3px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
    }

    /* Style untuk Modal Konfirmasi Custom */
    .modal-overlay {
        display: none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.6);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px); 
    }

    .modal-content {
        background-color: white;
        padding: 40px 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 420px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        font-family: 'Poppins', sans-serif;
        animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(-10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Animasi Ikon Peringatan */
    .modal-icon {
        font-size: 45px;
        color: #ef4444;
        margin-bottom: 15px;
        animation: pulseWarning 1.5s infinite ease-in-out;
    }
    
    @keyframes pulseWarning {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .modal-content h3 { margin-top: 0; color: #0f172a; font-size: 22px; font-weight: 600; margin-bottom: 10px; }
    .modal-content p { color: #64748b; margin-bottom: 30px; font-size: 15px; line-height: 1.6; }
    
    .modal-actions { display: flex; justify-content: center; gap: 15px; }
    
    .btn-modal { padding: 10px 20px; font-weight: 500; border-radius: 6px; font-family: 'Poppins', sans-serif; cursor: pointer; transition: all 0.2s; border: none; font-size: 14px;}
    
    .btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .btn-secondary:hover { background: #e2e8f0; color: #1e293b; }
    
    .btn-danger { background: #ef4444; color: white; text-decoration: none; display: inline-block; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2); }
    .btn-danger:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(239, 68, 68, 0.4); }
</style>

<div class="card">
    <a href="javascript:void(0);" class="btn-back" onclick="bukaModalBatal()">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <h2 style="margin-bottom: 25px; font-size: 24px; color: var(--text-dark, #1e293b); font-weight: 600;">Edit Data Buku</h2>
     
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
            <input type="text" name="isbn" value="<?= old('isbn', $buku['isbn']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul_buku" value="<?= old('judul_buku', $buku['judul_buku']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="<?= old('penulis', $buku['penulis']) ?>" readonly>
        </div>
        
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="<?= old('penerbit', $buku['penerbit']) ?>" readonly>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= old('kategori', $buku['kategori']) ?>" placeholder="Misal: Fiksi, Komputer...">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= old('stok', $buku['stok']) ?>" min="0" required>
        </div>

        <div class="form-group">
            <label>Cover Saat Ini</label>
            <?php 
                $isUrl = filter_var($buku['cover'], FILTER_VALIDATE_URL);
                $coverSrc = $isUrl ? $buku['cover'] : base_url('uploads/covers/' . $buku['cover']);
            ?>
            <img src="<?= esc($coverSrc) ?>" alt="Cover Buku" class="cover-preview" onerror="this.src='https://via.placeholder.com/100x150?text=No+Cover'">
            
            <label style="margin-top: 15px;">Ganti Cover (Kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="cover_buku" accept="image/*" style="font-family: 'Poppins', sans-serif;">
        </div>

        <button type="submit" class="btn-success">
            <i class="fa-solid fa-floppy-disk" style="margin-right: 5px;"></i> Update Buku
        </button>
    </form>
</div>

<div id="modalBatal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h3>Batalkan Perubahan?</h3>
        <p>Perubahan yang sudah Anda buat tidak akan disimpan.<br>Apakah Anda yakin ingin kembali?</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal btn-secondary" onclick="tutupModalBatal()">Lanjut Edit</button>
            <a href="<?= base_url('admin/buku') ?>" class="btn-modal btn-danger">Ya, Batalkan</a>
        </div>
    </div>
</div>

<script>
// Fungsi untuk menampilkan modal konfirmasi
function bukaModalBatal() {
    const modal = document.getElementById('modalBatal');
    modal.style.display = 'flex';
}

// Fungsi untuk menyembunyikan modal konfirmasi
function tutupModalBatal() {
    const modal = document.getElementById('modalBatal');
    modal.style.display = 'none';
}

// Tutup modal jika user mengklik area background yang gelap
window.onclick = function(event) {
    const modal = document.getElementById('modalBatal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?= $this->endSection(); ?>