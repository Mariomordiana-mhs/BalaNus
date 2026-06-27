<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    <a href="<?= base_url('katalog') ?>" style="margin-bottom: 20px; display: inline-block; text-decoration: none; color: #005ce6;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>

    <div style="display: flex; gap: 30px;">
        <div style="width: 200px; height: 300px; background: #e0e7ff; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
            <?php if(!empty($buku['cover'])): ?>
                <img src="<?= base_url('uploads/covers/' . $buku['cover']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
            <?php else: ?>
                <i class="fa-solid fa-book" style="font-size: 50px; color: #005ce6;"></i>
            <?php endif; ?>
        </div>

        <div style="flex: 1;">
            <h1><?= esc($buku['judul_buku']) ?></h1>
            <p style="color: #888; margin-bottom: 20px;">Penulis: <strong><?= esc($buku['penulis']) ?></strong></p>
            
            <h4 style="margin-bottom: 10px;">Sinopsis:</h4>
            <p style="line-height: 1.6; text-align: justify; white-space: pre-wrap;">
                <?= esc($buku['deskripsi'] ?? 'Deskripsi tidak tersedia.') ?>
            </p>
            
            <div style="margin-top: 30px;">
                <a href="<?= base_url('peminjaman/ajukan/' . $buku['id_buku']) ?>" style="background: #005ce6; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none;">
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>