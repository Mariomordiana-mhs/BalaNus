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
        body { background-color: #222222; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { display: flex; width: 100vw; height: 100vh; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); }
        .left-panel { flex: 1; background: linear-gradient(135deg, #005ce6, #003399); color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative; }
        .circle { position: absolute; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 50%; }
        .circle-1 { width: 350px; height: 350px; }
        .circle-2 { width: 450px; height: 450px; }
        .logo-container { z-index: 10; text-align: center; }
        .logo-container i { font-size: 80px; margin-bottom: 10px; }
        .logo-container h1 { font-size: 28px; font-weight: 600; letter-spacing: 1px; margin-bottom: 5px; }
        .logo-container p { font-size: 12px; font-weight: 300; color: #d1e0ff; }
        .right-panel { flex: 1.2; background: linear-gradient(to bottom right, #ffffff, #eaeaea); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; }
        .form-container { width: 100%; max-width: 320px; text-align: center; }
        .form-container h2 { font-size: 24px; color: #222; margin-bottom: 5px; }
        .form-container p.subtitle { font-size: 13px; color: #666; margin-bottom: 25px; }
        .input-group { position: relative; margin-bottom: 15px; }
        .input-group i { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); color: #aaa; font-size: 14px; }
        .input-group input { width: 100%; padding: 12px 15px 12px 40px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; outline: none; transition: 0.3s; }
        .input-group input:focus { border-color: #005ce6; }
        .btn-primary { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-bottom: 15px; }
        .btn-primary:hover { background-color: #005ce6; }
        .divider { display: flex; align-items: center; text-align: center; color: #999; font-size: 12px; margin-bottom: 15px; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #ddd; }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }
        .btn-google { width: 100%; padding: 10px; background-color: #fff; color: #444; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn-google img { width: 18px; }
        .login-link { font-size: 12px; color: #666; margin-top: 20px;}
        .login-link a { color: #005ce6; text-decoration: none; font-weight: 600; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 12px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
                <h2>Welcome to BalaNus</h2>
                <p class="subtitle">Log in to your account</p>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login-process') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="input-group">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-primary">Login</button>
                </form>

                <div class="divider">Or</div>

                <button class="btn-google">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">
                    Log In with Google
                </button>

                <p class="login-link">Belum punya akun? <a href="<?= base_url('register') ?>">Register</a></p>
            </div>
        </div>
    </div>

</body>
</html>