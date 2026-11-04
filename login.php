<?php
require_once __DIR__ . '/includes/auth.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = login($pdo, trim($_POST['username'] ?? ''), $_POST['password'] ?? '');
    if ($result === true) {
        header('Location: index.php');
        exit;
    }
    $error = $result;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - NurIslam</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <h1>🕌 NurIslam</h1>
        <h2>Masuk</h2>
        <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username atau Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</div>
</body>
</html>