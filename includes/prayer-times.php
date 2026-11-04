<?php
/**
 * Jadwal shalat via Aladhan API
 * method=20 → Kemenag Indonesia (Depag RI)
 */
function getPrayerTimes($lat, $lng, $date = null) {
    $date = $date ?: date('d-m-Y');
    $cacheKey = "prayer_" . md5("{$lat}_{$lng}_{$date}") . ".json";
    $cacheFile = __DIR__ . '/../cache/' . $cacheKey;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    $url = "https://api.aladhan.com/v1/timings/{$date}?" . http_build_query([
        'latitude' => $lat,
        'longitude' => $lng,
        'method' => 20,        // Kemenag RI
        'school' => 0,
    ]);

    $ctx = stream_context_create(['http' => ['timeout' => 10]]);
    $response = @file_get_contents($url, false, $ctx);

    if (!$response) {
        return ['error' => 'Gagal ambil jadwal shalat'];
    }

    $data = json_decode($response, true);
    if (!isset($data['data'])) {
        return ['error' => 'Data jadwal tidak valid'];
    }

    // Simpan cache
    @file_put_contents($cacheFile, json_encode($data));

    return $data;
}

function getNextPrayer($timings) {
    $now = time();
    $order = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
    $labels = [
        'Fajr' => 'Subuh',
        'Dhuhr' => 'Dzuhur',
        'Asr' => 'Ashar',
        'Maghrib' => 'Maghrib',
        'Isha' => 'Isya',
    ];

    foreach ($order as $key) {
        if (!isset($timings[$key])) continue;
        $time = strtotime($timings[$key]);
        if ($time > $now) {
            return [
                'key' => $key,
                'label' => $labels[$key],
                'time' => $timings[$key],
                'timestamp' => $time,
            ];
        }
    }
    // Semua sudah lewat → besok Subuh
    return [
        'key' => 'Fajr',
        'label' => 'Subuh (besok)',
        'time' => $timings['Fajr'],
        'timestamp' => strtotime($timings['Fajr'] . ' +1 day'),
    ];
}