<?php
/**
 * Al-Quran via api.quran.com v4
 * Cache 24 jam
 */
function getSurahList() {
    $cacheFile = __DIR__ . '/../cache/surah_list.json';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    $url = "https://api.quran.com/api/v4/chapters?language=id";
    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $response = @file_get_contents($url, false, $ctx);
    if (!$response) return [];

    $data = json_decode($response, true);
    @file_put_contents($cacheFile, json_encode($data));
    return $data;
}

function getSurahVerses($surahNumber, $translationId = 134) {
    // translationId 134 = Kemenag Indonesia (jika tersedia), fallback ke 33 (English)
    $cacheFile = __DIR__ . "/../cache/surah_{$surahNumber}.json";
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    // Ambil ayat Arab Utsmani + terjemahan
    $url = "https://api.quran.com/api/v4/verses/by_chapter/{$surahNumber}?" . http_build_query([
        'language' => 'id',
        'words' => 'false',
        'translations' => $translationId,
        'fields' => 'text_uthmani',
        'per_page' => 300,
    ]);

    $ctx = stream_context_create(['http' => ['timeout' => 20]]);
    $response = @file_get_contents($url, false, $ctx);
    if (!$response) return [];

    $data = json_decode($response, true);
    @file_put_contents($cacheFile, json_encode($data));
    return $data;
}

function getSurahAudio($surahNumber, $reciterId = 7) {
    // reciterId 7 = Mishary Rashid Alafasy
    return "https://verses.quran.com/AbdulBaset/Mujawwad/mp3/001001.mp3"; // fallback
}