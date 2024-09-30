<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (isset($_GET['error'])): ?>
    <div style="color:red;">
        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
    </div>
<?php endif; ?>

<form action="login_proses.php" method="POST">
    <div>
        <label for="userid">User ID:</label>
        <input type="text" id="userid" name="userid" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
</form>

<p>belum punya akun? <a href="register.php">Register now!</a></p>

</body>
</html>
