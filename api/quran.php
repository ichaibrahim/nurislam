<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/quran.php';

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    echo json_encode(getSurahList());
} elseif ($action === 'surah') {
    $num = (int)($_GET['number'] ?? 1);
    echo json_encode(getSurahVerses($num));
}