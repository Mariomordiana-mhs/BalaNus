<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Halaman Register</h2>

<form action="/register-process" method="post">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>

</form>

<br>
<a href="/login">Sudah punya akun? Login</a>

</body>
</html>