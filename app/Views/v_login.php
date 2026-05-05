<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BalaNus</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        /* Background body disesuaikan */
        body { background-color: #f5f7fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; overflow-x: hidden; }
        
        /* DI SINI PERUBAHAN FULL SCREEN NYA (100vw & 100vh) */
        .container { display: flex; width: 100vw; min-height: 100vh; background-color: #ffffff; } 
        
        .left-panel { flex: 1.2; background: linear-gradient(135deg, #005ce6, #003399); color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative; overflow: hidden; }
        .circle { position: absolute; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 50%; }
        .circle-1 { width: 350px; height: 350px; }
        .circle-2 { width: 450px; height: 450px; }
        .logo-container { z-index: 10; text-align: center; }
        .logo-container i { font-size: 80px; margin-bottom: 10px; }
        .logo-container h1 { font-size: 28px; font-weight: 600; letter-spacing: 1px; margin-bottom: 5px; }
        .logo-container p { font-size: 12px; font-weight: 300; color: #d1e0ff; }
        
        .right-panel { flex: 1; background: linear-gradient(to bottom right, #ffffff, #eaeaea); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; }
        
        /* Lebar form dinaikkan sedikit agar proporsional di layar besar */
        .form-container { width: 100%; max-width: 380px; text-align: center; }
        
        .form-container h2 { font-size: 24px; color: #222; margin-bottom: 5px; }
        .form-container p.subtitle { font-size: 13px; color: #666; margin-bottom: 30px; }
        
        /* Notifikasi Flashdata CI4 */
        .alert { font-size: 12px; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: left; }
        .alert-error { background-color: #ffe6e6; color: #cc0000; border: 1px solid #ffcccc; }
        .alert-success { background-color: #e6ffe6; color: #009900; border: 1px solid #ccffcc; }

        .input-group { position: relative; margin-bottom: 15px; }
        .input-group i { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); color: #aaa; font-size: 14px; }
        .input-group input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; outline: none; transition: 0.3s; }
        .input-group input:focus { border-color: #005ce6; }
        .btn-primary { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-bottom: 10px; }
        .btn-primary:hover { background-color: #005ce6; }
        .forgot-pass { display: block; text-align: right; font-size: 12px; color: #666; text-decoration: none; margin-bottom: 20px; }
        .btn-google { width: 100%; padding: 10px; background-color: #fff; color: #444; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .btn-google img { width: 18px; }
        .register-link { font-size: 12px; color: #666; }
        .register-link a { color: #005ce6; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

    <div class="container">
        <div class="left-panel">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="logo-container">
                <i class="fa-solid fa-book-open-reader"></i>
                <h1>BalaNus</h1>
                <p>Books are windows to the world</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="form-container">
                <h2>Hello!</h2>
                <p class="subtitle">Sign In to Get Started</p>

                <!-- Menampilkan Pesan Error/Success dari CI4 Flashdata -->
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <!-- Form Login (Menggunakan base_url) -->
                <form action="<?= base_url('login-process') ?>" method="post">
                    <?= csrf_field() ?> <!-- Sangat disarankan di CI4 untuk keamanan -->
                    
                    <div class="input-group">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" name="username" placeholder="Username or email" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-primary">Login</button>
                    <a href="#" class="forgot-pass">Forgot Password ?</a>
                </form>

                <button class="btn-google">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">
                    Sign In with Google
                </button>

                <!-- Link Register (Menggunakan base_url) -->
                <p class="register-link">Belum punya akun? <a href="<?= base_url('register') ?>">Register</a></p>
            </div>
        </div>
    </div>

</body>
</html>