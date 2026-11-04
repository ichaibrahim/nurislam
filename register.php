<?php
require_once __DIR__ . '/includes/auth.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = register(
        $pdo,
        trim($_POST['username'] ?? ''),
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? '',
        trim($_POST['full_name'] ?? '')
    );
    if ($result === true) {
        $success = 'Registrasi berhasil! Silakan login.';
    } else {
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar - NurIslam</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <h1>🕌 NurIslam</h1>
        <h2>Daftar Akun</h2>
        <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password (min 6)" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</div>
</body>
</html>