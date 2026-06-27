<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

    <style>
        /* =========================================
           GAYA UNTUK ELEMEN DASHBOARD
           ========================================= */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #eaedf2; display: flex; align-items: center; justify-content: space-between; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 20px; }
        .stat-info h3 { font-size: 13px; color: #888; font-weight: 500; margin: 0; }
        .stat-info h2 { font-size: 24px; color: #005ce6; font-weight: 600; margin-top: 5px; margin-bottom: 0; }
        .stat-card .link { font-size: 12px; color: #005ce6; text-decoration: none; margin-top: 10px; display: inline-block; font-weight: 500;}
        
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px; }
        .card-panel { background: #fff; border-radius: 12px; border: 1px solid #eaedf2; padding: 20px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .panel-header h3 { font-size: 16px; font-weight: 600; margin: 0; }
        .panel-header a { font-size: 13px; color: #005ce6; text-decoration: none; font-weight: 500; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 12px; color: #888; border-bottom: 1px solid #eaedf2; font-weight: 500;}
        td { padding: 15px 10px; font-size: 13px; border-bottom: 1px solid #eaedf2; vertical-align: middle; }
        
        .user-td { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 30px; height: 30px; background: #e0e7ff; color: #005ce6; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: bold; }
        
        .book-list-item { display: flex; gap: 15px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eaedf2; }
        .book-cover { width: 50px; height: 70px; background: #ddd; border-radius: 4px; object-fit: cover; }
        .book-details h4 { font-size: 14px; font-weight: 600; margin-bottom: 2px; margin-top: 0; }
        .book-details p { font-size: 12px; color: #888; margin-bottom: 5px; margin-top: 0; }
        .late-text { font-size: 11px; color: #d9534f; font-weight: 500; }
        .borrower-info { text-align: right; margin-left: auto; font-size: 12px; color: #888; }

        /* CSS untuk Tombol Aksi */
        .action-buttons { display: flex; gap: 8px; }
        .btn-action { padding: 6px 12px; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif;}
        .btn-acc { background-color: #e6ffef; color: #28a745; }
        .btn-acc:hover { background-color: #28a745; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2); }
        .btn-tolak { background-color: #ffeaea; color: #d9534f; }
        .btn-tolak:hover { background-color: #d9534f; color: white; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(217, 83, 79, 0.2); }

        /* =========================================
           KUSTOMISASI SWEETALERT2 UNTUK DASHBOARD
           ========================================= */
        .swal2-popup {
            width: 380px !important; 
            padding: 2em !important;
            border-radius: 16px !important;
            font-family: 'Poppins', sans-serif !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        .swal2-title {
            font-size: 20px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            margin-bottom: 10px !important;
        }
        .swal2-html-container {
            font-size: 14px !important;
            color: #64748b !important;
            margin-top: 0 !important;
        }
        .swal2-icon {
            width: 70px !important;
            height: 70px !important;
            margin: 0 auto 15px !important;
            border-width: 3px !important; 
        }
        .swal2-icon .swal2-icon-content {
            font-size: 40px !important;
        }
        .swal2-actions {
            margin-top: 25px !important;
            gap: 12px;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        /* Custom Tombol SweetAlert */
        .btn-swal-confirm, .btn-swal-cancel {
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 10px 24px !important;
            border: none !important;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-swal-cancel {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
        }
        .btn-swal-cancel:hover {
            background-color: #e2e8f0 !important;
        }
    </style>

    <div class="stats-grid">
        <div class="stat-card">
            <div><div class="stat-info"><h3>Total Buku</h3><h2>1.248</h2></div><a href="<?= base_url('admin/buku') ?>" class="link">Lihat semua ></a></div>
            <div class="stat-icon" style="background:#e0e7ff; color:#005ce6;"><i class="fa-solid fa-book"></i></div>
        </div>
        <div class="stat-card">
            <div><div class="stat-info"><h3>Total Eksemplar</h3><h2 style="color:#28a745;">2.562</h2></div><a href="<?= base_url('admin/eksemplar') ?>" class="link">Lihat semua ></a></div>
            <div class="stat-icon" style="background:#e6ffef; color:#28a745;"><i class="fa-solid fa-cubes"></i></div>
        </div>
        <div class="stat-card">
            <div><div class="stat-info"><h3>Peminjaman Aktif</h3><h2 style="color:#fd7e14;">156</h2></div><a href="<?= base_url('admin/peminjaman') ?>" class="link">Lihat semua ></a></div>
            <div class="stat-icon" style="background:#fff4e6; color:#fd7e14;"><i class="fa-solid fa-user-group"></i></div>
        </div>
        <div class="stat-card">
            <div><div class="stat-info"><h3>Denda Belum Dibayar</h3><h2 style="color:#d9534f; font-size:20px;">Rp 2.450.000</h2></div><a href="<?= base_url('admin/denda') ?>" class="link">Lihat semua ></a></div>
            <div class="stat-icon" style="background:#ffeaea; color:#d9534f;"><i class="fa-solid fa-wallet"></i></div>
        </div>
    </div>

    <div class="content-grid">
        <div class="card-panel">
            <div class="panel-header">
                <h3>Peminjaman Terbaru</h3>
                <a href="<?= base_url('admin/peminjaman') ?>">Lihat semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="peminjamanTableBody">
                    </tbody>
            </table>
        </div>

        <div class="card-panel">
            <div class="panel-header">
                <h3>Buku Terlambat</h3>
                <a href="<?= base_url('admin/peminjaman') ?>">Lihat semua</a>
            </div>
            <div class="book-list-item">
                <div class="book-cover" style="background:#1e3c72; display:flex; align-items:center; justify-content:center; color:white;"><i class="fa-solid fa-book"></i></div>
                <div class="book-details"><h4>Negeri 5 Menara</h4><p>Ahmad Fuadi</p><span class="late-text">Terlambat 3 hari</span></div>
                <div class="borrower-info">Rudi Hartono<br>A005</div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="card-panel" style="height: 350px; display: flex; flex-direction: column;">
            <div class="panel-header" style="margin-bottom: 10px;"><h3>Statistik Peminjaman</h3></div>
            <div style="position: relative; flex-grow: 1; min-height: 0;"><canvas id="barChart"></canvas></div>
        </div>
        <div class="card-panel" style="height: 350px; display: flex; flex-direction: column;">
            <div class="panel-header" style="margin-bottom: 10px;"><h3>Ringkasan Denda</h3></div>
            <div style="position: relative; flex-grow: 1; min-height: 0;"><canvas id="donutChart"></canvas></div>
        </div>
    </div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const baseUrl = '<?= base_url() ?>';
    
    // JS UNTUK UPDATE NOTIFIKASI & TABEL PEMINJAMAN
    function fetchLivePeminjaman() {
        fetch(`${baseUrl}/admin/live_peminjaman`)
            .then(response => response.json())
            .then(data => {
                let badge = document.getElementById('notifBadge');
                if(badge) badge.innerText = data.length;
                
                const tbody = document.getElementById('peminjamanTableBody');
                
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #888; padding: 20px;">Belum ada pengajuan peminjaman baru.</td></tr>`;
                    return;
                }
                
                let htmlOutput = ''; 
                let no = 1;
                
                data.forEach(row => {
                    let firstLetter = (row.username || 'U').charAt(0).toUpperCase();
                    let tglFormatted = new Date(row.tgl_pengajuan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    
                    let idData = row.id_peminjaman || row.id; 
                    
                    let actionButtons = `
                        <div class="action-buttons">
                            <button onclick="prosesPeminjaman('${idData}', 'acc')" class="btn-action btn-acc" title="Setujui Peminjaman">
                                <i class="fa-solid fa-check"></i> &nbsp;ACC
                            </button>
                            <button onclick="prosesPeminjaman('${idData}', 'tolak')" class="btn-action btn-tolak" title="Tolak Peminjaman">
                                <i class="fa-solid fa-xmark"></i> &nbsp;Tolak
                            </button>
                        </div>
                    `;

                    htmlOutput += `
                        <tr>
                            <td>${no++}</td>
                            <td>
                                <div class="user-td">
                                    <div class="user-avatar">${firstLetter}</div>
                                    <div><span style="font-weight: 600;">${row.username}</span></div>
                                </div>
                            </td>
                            <td><span style="font-weight: 500;">${row.judul_buku}</span></td>
                            <td>${tglFormatted}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = htmlOutput;
            })
            .catch(error => console.error('Error fetching data:', error));
    }
    
    fetchLivePeminjaman();
    setInterval(fetchLivePeminjaman, 4000);

    // FUNGSI UNTUK MENGELOLA AKSI TOMBOL DENGAN SWEETALERT2 CUSTOM DESAIN
    function prosesPeminjaman(id_peminjaman, aksi) {
        let isAcc = aksi === 'acc';
        let pesanText = isAcc ? 'menyetujui' : 'menolak';
        let warnaTombol = isAcc ? '#28a745' : '#ef4444';
        let iconAlert = isAcc ? 'question' : 'warning';
        
        Swal.fire({
            title: 'Konfirmasi Aksi',
            html: `Apakah Anda yakin ingin <b style="color: ${warnaTombol}">${pesanText}</b> pengajuan peminjaman ini?`,
            icon: iconAlert,
            showCancelButton: true,
            confirmButtonText: `Ya, ${pesanText}!`,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false, 
            customClass: {
                confirmButton: 'btn-swal-confirm',
                cancelButton: 'btn-swal-cancel'
            },
            didOpen: () => {
                const confirmBtn = Swal.getConfirmButton();
                confirmBtn.style.backgroundColor = warnaTombol;
                confirmBtn.style.color = '#ffffff';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // URL disesuaikan dengan file Routes.php Anda
                // Output: /admin/peminjaman/acc/15 atau /admin/peminjaman/tolak/15
                window.location.href = `<?= base_url('admin/peminjaman') ?>/${aksi}/${id_peminjaman}`;
            }
        });
    }

    // CHARTS INITIALIZATION
    document.addEventListener("DOMContentLoaded", function() {
        const barCtx = document.getElementById('barChart');
        if(barCtx) {
            new Chart(barCtx.getContext('2d'), { 
                type: 'bar', 
                data: { 
                    labels: ['Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei'], 
                    datasets: [
                        { label: 'Peminjaman', data: [140, 200, 190, 160, 140, 120], backgroundColor: '#005ce6', borderRadius: 4 }, 
                        { label: 'Pengembalian', data: [90, 130, 140, 120, 100, 80], backgroundColor: '#a3c4f9', borderRadius: 4 }
                    ] 
                }, 
                options: { responsive: true, maintainAspectRatio: false } 
            });
        }

        const donutCtx = document.getElementById('donutChart');
        if(donutCtx) {
            new Chart(donutCtx.getContext('2d'), { 
                type: 'doughnut', 
                data: { 
                    labels: ['Belum Dibayar', 'Sebagian Dibayar', 'Lunas'], 
                    datasets: [{ data: [70, 21, 9], backgroundColor: ['#ff4d4d', '#ffc107', '#28a745'], borderWidth: 0 }] 
                }, 
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom' } } } 
            });
        }
    });
</script>
<?= $this->endSection(); ?>