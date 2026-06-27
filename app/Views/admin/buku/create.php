<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .card { background: white; padding: 30px; border-radius: 12px; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #334155; }
    input[type="text"], input[type="number"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: 'Poppins', sans-serif; transition: border-color 0.2s; }
    input:focus { outline: none; border-color: var(--primary, #3b82f6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    input[readonly] { background-color: #f8fafc; cursor: not-allowed; color: #94a3b8; }
    
    .input-group { display: flex; gap: 10px; }
    .btn { padding: 10px 15px; background: var(--primary, #3b82f6); color: white; border: none; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 500; transition: all 0.2s; }
    .btn:hover { background: var(--primary-dark, #2563eb); }
    
    .btn-success { background: #10b981; width: 100%; margin-top: 15px; font-size: 15px; padding: 14px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); }
    .btn-success:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(16, 185, 129, 0.3); }
    
    .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #ef4444; }
    
    /* Style untuk Tombol Kembali */
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

    <h2 style="margin-bottom: 25px; font-size: 24px; color: var(--text-dark, #1e293b); font-weight: 600;">Tambah Data Buku</h2>
    
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
                <input type="text" id="isbn" name="isbn" value="<?= old('isbn') ?>" placeholder="Contoh: 9780134190440" required autocomplete="off">
                <button type="button" class="btn" id="btnCariIsbn"><i class="fa-solid fa-magnifying-glass"></i> Cari ISBN</button>
            </div>
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?= old('judul_buku') ?>" readonly required placeholder="Terisi otomatis dari API">
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" id="penulis" name="penulis" value="<?= old('penulis') ?>" readonly required placeholder="Terisi otomatis dari API">
        </div>
        
        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" id="penerbit" name="penerbit" value="<?= old('penerbit') ?>" readonly placeholder="Terisi otomatis dari API">
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
            <label>Upload Cover (Opsional)</label>
            <input type="file" name="cover_buku" accept="image/*" style="font-family: 'Poppins', sans-serif;">
            <input type="hidden" id="cover_url" name="cover_url">
            <div id="preview_cover" style="margin-top: 10px;"></div>
        </div>

        <button type="submit" class="btn-success btn">
            <i class="fa-solid fa-floppy-disk" style="margin-right: 5px;"></i> Simpan Buku
        </button>
    </form>
</div>

<div id="modalBatal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h3>Batalkan Proses?</h3>
        <p>Data yang sudah Anda isi belum disimpan.<br>Apakah Anda yakin ingin kembali?</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal btn-secondary" onclick="tutupModalBatal()">Tetap Disini</button>
            <a href="<?= base_url('admin/buku') ?>" class="btn-modal btn-danger">Ya, Buang Data</a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi untuk menarik data dari API (Via Backend CI4) dan memunculkan SweetAlert2
document.getElementById('btnCariIsbn').addEventListener('click', function() {
    let isbn = document.getElementById('isbn').value;
    
    if(!isbn) {
        Swal.fire({
            icon: 'warning',
            title: 'ISBN Kosong',
            text: 'Masukkan nomor ISBN terlebih dahulu!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    let btn = this;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari...';
    btn.disabled = true;

    // PENTING: Memanggil Endpoint CI4 lokal Anda, untuk memicu sistem Cache di Controller
    fetch(`<?= base_url('admin/buku/cari_api/') ?>${isbn}`)
        .then(response => response.json())
        .then(res => {
            if(res.status === 'success') {
                let bookData = res.data;
                
                // Isi Form Otomatis
                document.getElementById('judul_buku').value = bookData.judul || '';
                document.getElementById('penulis').value = bookData.penulis || '';
                document.getElementById('penerbit').value = bookData.penerbit || '';
                
                // Mengatur Cover URL dari API
                if(bookData.cover) {
                    document.getElementById('cover_url').value = bookData.cover;
                    document.getElementById('preview_cover').innerHTML = `<img src="${bookData.cover}" style="height: 150px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 2px solid #e2e8f0;">`;
                } else {
                    document.getElementById('cover_url').value = '';
                    document.getElementById('preview_cover').innerHTML = '';
                }

                // Munculkan SweetAlert2 "Success"
                Swal.fire({
                    icon: 'success',
                    title: 'Buku Ditemukan!',
                    text: `Data buku berhasil ditambahkan.`, 
                    confirmButtonColor: '#10b981'
                });

            } else {
                // Tentukan judul pop-up berdasarkan isi pesan dari backend
                let alertTitle = 'Tidak Ditemukan';
                if (res.pesan.includes('sudah terdaftar')) {
                    alertTitle = 'ISBN Sudah Ada';
                }

                // Munculkan SweetAlert2 "Error" atau "Sudah Ada"
                Swal.fire({
                    icon: 'error',
                    title: alertTitle,
                    text: res.pesan,
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Koneksi Gagal',
                text: 'Terjadi kesalahan saat menghubungi server lokal.',
                confirmButtonColor: '#ef4444'
            });
        })
        .finally(() => {
            // Kembalikan tombol ke kondisi semula
            btn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Cari ISBN';
            btn.disabled = false;
        });
});

// Fungsi untuk menampilkan modal konfirmasi batal
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