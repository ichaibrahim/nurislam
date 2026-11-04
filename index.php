<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/prayer-times.php';
require_once __DIR__ . '/includes/qibla.php';

$user = currentUser($pdo);
$lat  = $user['latitude']  ?? -6.2088;
$lng  = $user['longitude'] ?? 106.8456;
$city = $user['city']      ?? 'Jakarta';

$prayerData = getPrayerTimes($lat, $lng);
$timings    = $prayerData['data']['timings'] ?? [];
$nextPrayer = !empty($timings) ? getNextPrayer($timings) : null;
$qibla      = qiblaDirection($lat, $lng);
$hijri      = $prayerData['data']['date']['hijri'] ?? null;

// Hitung countdown ke shalat berikutnya
$countdown = '';
if ($nextPrayer) {
    $diff = $nextPrayer['timestamp'] - time();
    if ($diff > 0) {
        $h = floor($diff / 3600);
        $m = floor(($diff % 3600) / 60);
        $s = $diff % 60;
        $countdown = sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}

// Tanggal Masehi Indonesia
$hariID = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
$bulanID = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$now = new DateTime();
$tanggalID = $now->format('j') . ' ' . $bulanID[(int)$now->format('n')] . ' ' . $now->format('Y');

$currentPage = 'beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nurani — Teman Ibadah Harian</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ====== HEADER ====== -->
<header class="header">
    <div class="brand-wrap">
        <div class="logo-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </div>
        <div class="brand-text">
            <div class="brand-name">Nurani</div>
            <div class="brand-tag">Teman ibadah harian</div>
        </div>
    </div>

    <ul class="nav-main">
        <li><a href="#" class="active" data-tab="beranda">Beranda</a></li>
        <li><a href="#" data-tab="shalat">Shalat</a></li>
        <li><a href="#" data-tab="quran">Al-Quran</a></li>
        <li><a href="#" data-tab="kiblat">Kiblat</a></li>
        <li><a href="#" data-tab="dzikir">Dzikir Pagi &amp; Petang</a></li>
    </ul>

    <div style="display:flex;gap:8px;align-items:center;">
        <?php if ($user): ?>
            <a href="logout.php" class="btn-outline-sm">Keluar</a>
        <?php else: ?>
            <a href="login.php" class="btn-outline-sm">Masuk</a>
        <?php endif; ?>
        <button class="theme-btn" id="themeToggle">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
            </svg>
            Tema
        </button>
    </div>
</header>

<!-- ====== MAIN ====== -->
<main class="main">

    <!-- ============ TAB: BERANDA ============ -->
    <section id="tab-beranda" class="section active">

        <div class="hero-top">
            <div class="hero-text">
                <div class="eyebrow">Ruang Ibadahmu</div>
                <h1 class="hero-title">Hadir dengan tenang, satu waktu demi satu waktu</h1>
                <p class="hero-desc">Temukan ritme ibadah harian melalui jadwal shalat, Al-Quran, kiblat, dzikir, dan doa.</p>
            </div>
            <div class="date-badge">
                <span class="label">Hari Ini</span>
                <div class="value"><?= $tanggalID ?></div>
                <?php if ($hijri): ?>
                    <div class="hijri"><?= $hijri['day'] ?> <?= $hijri['month']['en'] ?> <?= $hijri['year'] ?> H</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-grid">
            <!-- Kartu Waktu Shalat -->
            <div class="card prayer-card">
                <div class="prayer-label">Waktu shalat berikutnya</div>
                <div class="prayer-name"><?= $nextPrayer['label'] ?? '—' ?></div>
                <div class="prayer-time"><?= $nextPrayer['time'] ?? '--:--' ?></div>
                <div class="countdown" id="countdown" data-target="<?= $nextPrayer['timestamp'] ?? 0 ?>">
                    Menuju waktu berikutnya: <strong id="countdownText"><?= $countdown ?: '--:--:--' ?></strong>
                </div>
                <button class="prayer-btn" onclick="switchTab('shalat')">Lihat jadwal</button>
            </div>

            <!-- Kartu Progress Al-Quran -->
            <div class="card progress-card">
                <div class="card-title">Progres Al-Quran</div>
                <div class="progress-last">Terakhir: Al-Fatihah</div>
                <div class="progress-sub">0 dari 114 surah ditandai selesai</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:0%"></div>
                </div>
                <button class="progress-link" onclick="switchTab('quran')">Buka Al-Quran</button>
            </div>
        </div>

        <!-- Kartu Tips -->
        <div class="tips-card">
            <div class="tips-icon">✨</div>
            <div>
                <div class="tips-title">Mulai dari yang dekat</div>
                <div class="tips-desc">Pilih lokasi dengan tepat, gunakan kompas sebagai bantuan, dan selalu verifikasi arah serta jadwal dengan kondisi setempat.</div>
            </div>
        </div>
    </section>

    <!-- ============ TAB: SHALAT ============ -->
    <section id="tab-shalat" class="section">
        <h2 class="section-title">Jadwal Shalat Hari Ini</h2>
        <div class="prayer-grid">
            <?php
            $labelMap = ['Fajr'=>'Subuh','Sunrise'=>'Syuruq','Dhuhr'=>'Dzuhur','Asr'=>'Ashar','Maghrib'=>'Maghrib','Isha'=>'Isya'];
            foreach ($labelMap as $k => $v):
                if (isset($timings[$k])): ?>
                <div class="prayer-tile">
                    <div class="name"><?= $v ?></div>
                    <div class="time"><?= $timings[$k] ?></div>
                </div>
            <?php endif; endforeach; ?>
        </div>
    </section>

    <!-- ============ TAB: AL-QURAN ============ -->
    <section id="tab-quran" class="section">
        <h2 class="section-title">Al-Quran Digital</h2>
        <div id="surah-list" class="surah-grid">Memuat surah...</div>
        <div id="surah-detail" style="display:none;margin-top:20px;">
            <button onclick="backToSurahList()" class="btn-outline-sm">← Kembali</button>
            <h3 id="surah-title" style="margin:16px 0;color:var(--emerald);"></h3>
            <div id="surah-verses"></div>
        </div>
    </section>

    <!-- ============ TAB: KIBLAT ============ -->
    <section id="tab-kiblat" class="section">
        <h2 class="section-title">Arah Kiblat</h2>
        <div class="card qibla-card">
            <div class="qibla-degree"><?= round($qibla, 2) ?>°</div>
            <div style="color:var(--muted);">dari Utara (searah jarum jam)</div>
            <div class="qibla-arrow" style="transform:rotate(<?= $qibla ?>deg);">⬆</div>
            <p style="color:var(--ink-soft);max-width:420px;margin:0 auto;">
                Letakkan HP mendatar, arahkan bagian atas = Utara, lalu putar badan hingga panah mengarah ke atas.
            </p>
        </div>
    </section>

    <!-- ============ TAB: DZIKIR ============ -->
    <section id="tab-dzikir" class="section">
        <h2 class="section-title">Dzikir Pagi</h2>
        <div id="dzikir-pagi">Memuat...</div>
        <h2 class="section-title" style="margin-top:32px;">Dzikir Petang</h2>
        <div id="dzikir-petang">Memuat...</div>
        <h2 class="section-title" style="margin-top:32px;">Doa Harian</h2>
        <div id="doa-harian">Memuat...</div>
    </section>

</main>

<script src="assets/js/app.js"></script>
</body>
</html>