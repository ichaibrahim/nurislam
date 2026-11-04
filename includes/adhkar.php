<?php
/**
 * Dzikir pagi & petang + doa harian
 */
function getDzikirPagi() {
    return [
        [
            'key' => 'pagi_ayat_kursi',
            'title' => 'Ayat Kursi',
            'arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ ۚ لَا تَأْخُذُهُ سِنَةٌ وَلَا نَوْمٌ...',
            'translation' => 'Allah, tidak ada Tuhan selain Dia, Yang Maha Hidup, Yang terus-menerus mengurus (makhluk-Nya)...',
            'count' => 1,
            'reference' => 'Al-Baqarah: 255',
        ],
        [
            'key' => 'pagi_sayyidul_istighfar',
            'title' => 'Sayyidul Istighfar',
            'arabic' => 'اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَٰهَ إِلَّا أَنْتَ، خَلَقْتَنِي وَأَنَا عَبْدُكَ...',
            'translation' => 'Ya Allah, Engkau Tuhanku, tidak ada Tuhan selain Engkau. Engkau menciptakanku dan aku hamba-Mu...',
            'count' => 1,
            'reference' => 'HR. Bukhari',
        ],
        [
            'key' => 'pagi_asbahna',
            'title' => 'Doa Pagi',
            'arabic' => 'أَصْبَحْنَا وَأَصْبَحَ الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ...',
            'translation' => 'Kami telah memasuki waktu pagi dan kerajaan hanya milik Allah, segala puji bagi Allah...',
            'count' => 1,
            'reference' => 'HR. Muslim',
        ],
        [
            'key' => 'pagi_subhanallah',
            'title' => 'Tasbih',
            'arabic' => 'سُبْحَانَ اللَّهِ وَبِحَمْدِهِ',
            'translation' => 'Maha Suci Allah dan segala puji bagi-Nya',
            'count' => 100,
            'reference' => 'HR. Muslim',
        ],
    ];
}

function getDzikirPetang() {
    return [
        [
            'key' => 'petang_ayat_kursi',
            'title' => 'Ayat Kursi',
            'arabic' => 'اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ الْحَيُّ الْقَيُّومُ...',
            'translation' => 'Allah, tidak ada Tuhan selain Dia, Yang Maha Hidup...',
            'count' => 1,
            'reference' => 'Al-Baqarah: 255',
        ],
        [
            'key' => 'petang_amsayna',
            'title' => 'Doa Petang',
            'arabic' => 'أَمْسَيْنَا وَأَمْسَى الْمُلْكُ لِلَّهِ، وَالْحَمْدُ لِلَّهِ...',
            'translation' => 'Kami telah memasuki waktu petang dan kerajaan hanya milik Allah...',
            'count' => 1,
            'reference' => 'HR. Muslim',
        ],
        [
            'key' => 'petang_audzu',
            'title' => 'Perlindungan',
            'arabic' => 'أَعُوذُ بِكَلِمَاتِ اللَّهِ التَّامَّاتِ مِنْ شَرِّ مَا خَلَقَ',
            'translation' => 'Aku berlindung dengan kalimat-kalimat Allah yang sempurna dari kejahatan makhluk-Nya',
            'count' => 3,
            'reference' => 'HR. Muslim',
        ],
    ];
}

function getDoaHarian() {
    return [
        ['title' => 'Doa Sebelum Makan', 'arabic' => 'بِسْمِ اللَّهِ', 'translation' => 'Dengan menyebut nama Allah'],
        ['title' => 'Doa Sesudah Makan', 'arabic' => 'الْحَمْدُ لِلَّهِ الَّذِي أَطْعَمَنَا وَسَقَانَا', 'translation' => 'Segala puji bagi Allah yang memberi makan dan minum'],
        ['title' => 'Doa Sebelum Tidur', 'arabic' => 'بِاسْمِكَ اللَّهُمَّ أَمُوتُ وَأَحْيَا', 'translation' => 'Dengan nama-Mu ya Allah aku mati dan hidup'],
        ['title' => 'Doa Bangun Tidur', 'arabic' => 'الْحَمْدُ لِلَّهِ الَّذِي أَحْيَانَا بَعْدَ مَا أَمَاتَنَا', 'translation' => 'Segala puji bagi Allah yang menghidupkan kami setelah mematikan kami'],
    ];
}