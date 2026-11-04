<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/prayer-times.php';

$lat = $_GET['lat'] ?? -6.2088;
$lng = $_GET['lng'] ?? 106.8456;
$date = $_GET['date'] ?? null;

$data = getPrayerTimes($lat, $lng, $date);
echo json_encode($data);