<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/adhkar.php';

$type = $_GET['type'] ?? 'pagi';
if ($type === 'pagi') echo json_encode(getDzikirPagi());
elseif ($type === 'petang') echo json_encode(getDzikirPetang());
elseif ($type === 'doa') echo json_encode(getDoaHarian());