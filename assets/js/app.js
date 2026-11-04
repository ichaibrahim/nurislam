// ===== TEMA =====
const themeToggle = document.getElementById('themeToggle');
const savedTheme = localStorage.getItem('theme') || 'light';
if (savedTheme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');

themeToggle?.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    if (next === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    else document.documentElement.removeAttribute('data-theme');
    localStorage.setItem('theme', next);
});

// ===== TAB NAVIGASI =====
function switchTab(name) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.nav-main a').forEach(a => a.classList.remove('active'));

    const section = document.getElementById('tab-' + name);
    const navLink = document.querySelector(`.nav-main a[data-tab="${name}"]`);
    if (section) section.classList.add('active');
    if (navLink) navLink.classList.add('active');

    // Lazy load
    if (name === 'quran' && !window.quranLoaded) { loadSurahList(); window.quranLoaded = true; }
    if (name === 'dzikir' && !window.dzikirLoaded) { loadDzikir(); window.dzikirLoaded = true; }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.querySelectorAll('.nav-main a').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        switchTab(a.dataset.tab);
    });
});

// ===== COUNTDOWN =====
const cdEl = document.getElementById('countdown');
if (cdEl) {
    const target = parseInt(cdEl.dataset.target) * 1000;
    const textEl = document.getElementById('countdownText');

    setInterval(() => {
        const diff = target - Date.now();
        if (diff <= 0) { textEl.textContent = '00:00:00'; return; }
        const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        textEl.textContent = `${h}:${m}:${s}`;
    }, 1000);
}

// ===== AL-QURAN =====
async function loadSurahList() {
    const el = document.getElementById('surah-list');
    try {
        const res = await fetch('api/quran.php?action=list');
        const data = await res.json();
        const chapters = data.chapters || [];
        el.innerHTML = chapters.map(c => `
            <div class="surah-item" onclick="loadSurah(${c.id}, '${c.name_simple}', '${c.name_arabic}')">
                <div class="surah-num">${c.id}</div>
                <div class="surah-info">
                    <div class="surah-name">${c.name_simple}</div>
                    <div class="surah-sub">${c.translated_name?.name || ''} · ${c.verses_count} ayat</div>
                </div>
                <div class="surah-arabic">${c.name_arabic}</div>
            </div>
        `).join('');
    } catch (e) {
        el.innerHTML = '<p>Gagal memuat daftar surah.</p>';
    }
}

async function loadSurah(num, name, arabic) {
    document.getElementById('surah-list').style.display = 'none';
    document.getElementById('surah-detail').style.display = 'block';
    document.getElementById('surah-title').textContent = `${num}. ${name} (${arabic})`;
    const el = document.getElementById('surah-verses');
    el.innerHTML = '<p>Memuat ayat...</p>';

    try {
        const res = await fetch('api/quran.php?action=surah&number=' + num);
        const data = await res.json();
        const verses = data.verses || [];
        el.innerHTML = verses.map(v => `
            <div class="verse">
                <div class="verse-arabic">${v.text_uthmani}</div>
                <div class="verse-translation">${v.translations?.[0]?.text || ''}</div>
                <div class="verse-num">Ayat ${v.verse_number}</div>
            </div>
        `).join('');
    } catch (e) {
        el.innerHTML = '<p>Gagal memuat ayat.</p>';
    }
}

function backToSurahList() {
    document.getElementById('surah-detail').style.display = 'none';
    document.getElementById('surah-list').style.display = 'grid';
}

// ===== DZIKIR =====
async function loadDzikir() {
    loadDzikirType('pagi', 'dzikir-pagi');
    loadDzikirType('petang', 'dzikir-petang');
    loadDzikirType('doa', 'doa-harian');
}

async function loadDzikirType(type, targetId) {
    const el = document.getElementById(targetId);
    try {
        const res = await fetch('api/adhkar.php?type=' + type);
        const data = await res.json();
        el.innerHTML = data.map(d => `
            <div class="dzikir-card">
                <div class="dzikir-title">${d.title}</div>
                <div class="dzikir-arabic">${d.arabic}</div>
                <div class="dzikir-translation">${d.translation}</div>
                <div class="dzikir-footer">
                    <span>${d.reference || ''}</span>
                    ${d.count ? `<span class="badge">${d.count}x</span>` : ''}
                </div>
            </div>
        `).join('');
    } catch (e) {
        el.innerHTML = '<p>Gagal memuat dzikir.</p>';
    }
}