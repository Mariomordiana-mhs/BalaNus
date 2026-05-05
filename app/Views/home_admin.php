<!DOCTYPE html>
<html>
<head>
    <title>Halaman Admin</title>
</head>
<body style="background-color: #e6ffef; text-align: center; padding-top: 50px; font-family: sans-serif;">
    
    <!-- Mengambil data username dari session -->
    <h1>Halo, <?= session()->get('username'); ?>! 👤</h1>
    
    <p>Selamat datang di halaman dashboard admin.</p>
    <br>
    <a href="/logout" style="padding: 10px 20px; background: #005ce6; color: white; text-decoration: none; border-radius: 5px;">Logout</a>
    
</body>
</html>