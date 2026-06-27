<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    .profile-wrapper { max-width: 600px; margin: 0 auto; }
    
    .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 16px; background-color: #f8fafc; color: #475569; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 6px; border: 1px solid #e2e8f0; transition: 0.2s; }
    .btn-back:hover { background-color: #f1f5f9; color: #005ce6; border-color: #cbd5e1; transform: translateX(-3px); }

    .profile-card { background: white; border-radius: 16px; padding: 40px 30px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); text-align: center; border-top: 5px solid #005ce6; }
    
    /* Avatar Otomatis dari Huruf Depan */
    .avatar-circle { width: 100px; height: 100px; background: linear-gradient(135deg, #005ce6, #3b82f6); color: white; font-size: 40px; font-weight: bold; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 20px; box-shadow: 0 8px 15px rgba(0, 92, 230, 0.2); text-transform: uppercase; }
    
    .profile-name { font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 5px; }
    .profile-role { color: #64748b; font-size: 14px; font-weight: 500; margin-bottom: 30px; display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; padding: 6px 15px; border-radius: 20px; }

    .info-container { text-align: left; background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; }
    .info-row { display: flex; padding: 15px 0; border-bottom: 1px dashed #cbd5e1; }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    
    .info-label { font-weight: 600; color: #64748b; width: 40%; font-size: 14px; display: flex; align-items: center; gap: 10px; }
    .info-value { color: #0f172a; font-weight: 600; width: 60%; font-size: 14px; text-align: right; word-break: break-all; }
</style>

<div class="profile-wrapper">
    <a href="<?= base_url('admin/anggota') ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    <div class="profile-card">
        <div class="avatar-circle">
            <?= substr(esc($anggota['username']), 0, 1) ?>
        </div>
        
        <h2 class="profile-name"><?= esc(ucwords($anggota['username'])) ?></h2>
        <div class="profile-role"><i class="fa-solid fa-user-check" style="color: #10b981;"></i> Anggota Terdaftar</div>

        <div class="info-container">
            <div class="info-row">
                <div class="info-label"><i class="fa-solid fa-envelope"></i> Alamat Email</div>
                <div class="info-value"><?= esc($anggota['email'] ?? 'Tidak tersedia') ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label"><i class="fa-solid fa-calendar-days"></i> Bergabung Sejak</div>
                <div class="info-value">
                    <?php 
                        $tanggal = $anggota['created_at'] ?? $anggota['tanggal_bergabung'] ?? null;
                        echo $tanggal ? date('d F Y', strtotime($tanggal)) : 'Tidak tercatat'; 
                    ?>
                </div>
            </div>

            </div>
    </div>
</div>

<?= $this->endSection(); ?>