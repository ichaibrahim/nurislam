<?php
require_once __DIR__ . '/../config/database.php';

function register($pdo, $username, $email, $password, $fullName) {
    // Validasi
    if (strlen($username) < 3) return "Username minimal 3 karakter";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Email tidak valid";
    if (strlen($password) < 6) return "Password minimal 6 karakter";

    // Cek duplikat
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) return "Username atau email sudah terdaftar";

    // Hash & simpan
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hash, $fullName]);
    return true;
}

function login($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        return true;
    }
    return "Username atau password salah";
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function currentUser($pdo) {
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function logout() {
    session_destroy();
    header('Location: index.php');
    exit;
}