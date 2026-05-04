<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Halaman Login</h2>

<?php if(session()->getFlashdata('error')): ?>
    <p style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </p>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
    <p style="color:green;">
        <?= session()->getFlashdata('success'); ?>
    </p>
<?php endif; ?>

<form action="/login-process" method="post">
    
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>

</form>

<br>
<a href="/register">Belum punya akun? Register</a>

</body>
</html>