<?php
// Konfigurasi database - sesuaikan dengan XAMPP Anda
define('DB_HOST', 'localhost');
define('DB_NAME', 'nurislam');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');