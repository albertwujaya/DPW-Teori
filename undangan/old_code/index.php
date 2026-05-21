<?php
require_once __DIR__ . '/config.php';

$namaPria   = getSetting('nama_pria',     NAMA_MEMPELAI_PRIA);
$namaWanita = getSetting('nama_wanita',   NAMA_MEMPELAI_WANITA);
$tanggalDB  = getSetting('tanggal_acara', TANGGAL_ACARA);
$waktuDB    = getSetting('waktu_acara',   WAKTU_ACARA);
$lokasiDB   = getSetting('lokasi_acara',  LOKASI_ACARA);
$siteTitle  = 'Undangan Pernikahan ' . $namaPria . ' & ' . $namaWanita;

$namaTamu    = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : DEFAULT_GUEST_NAME;
$hariTersisa = hitung_hari_tersisa();
$tanggalFmt  = format_tanggal_indonesia($tanggalDB);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; color:#333; background:#f8fcf7; text-align:center; }
        h1,h2,h3,h4 { font-family:'Playfair Display',serif; margin-bottom:15px; }

        .cover-page {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)),
                        url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070') no-repeat center center/cover;
        }
        .open-invitation-btn {
            background: linear-gradient(135deg, #5d3b52 0%, #8b5a8f 100%);
            color: white; border: 2px solid rgba(255,255,255,0.3);
            padding: 14px 40px; font-size: 1.1rem; font-weight: 600;
            border-radius: 50px; cursor: pointer; transition: all 0.3s;
            margin-top: 40px; box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            letter-spacing: 1px; text-decoration: none; display: inline-block;
        }
        .open-invitation-btn:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,0.4); }
        .music-toggle-btn {
            background: rgba(255,255,255,0.15); color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 12px 25px; font-size: 0.95rem; font-weight: 500;
            border-radius: 50px; cursor: pointer; transition: all 0.3s;
            margin-top: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        .music-toggle-btn:hover { background:rgba(255,255,255,0.25); transform:translateY(-2px); }
    </style>
</head>
<body>
    <audio id="backgroundMusic" loop>
        <source src="https://assets.mixkit.co/active_storage/sfx/2727/2727-preview.mp3" type="audio/mpeg">
    </audio>

    <div class="cover-page h-screen flex flex-col justify-center items-center text-white p-5 relative">
        <h2 class="text-[#e0d5c1]">Undangan Pernikahan</h2>
        <h1 class="text-6xl my-6 leading-tight text-white">
            <?= htmlspecialchars($namaPria) ?> <br> &amp; <br> <?= htmlspecialchars($namaWanita) ?>
        </h1>
        <p class="text-lg text-gray-100">
            Save The Date <br>
            <?= $tanggalFmt ?> | <?= htmlspecialchars($waktuDB) ?> WIB
        </p>
        <h3 class="mt-7 text-base font-normal text-[#e0d5c1]">
            Location:<br><?= htmlspecialchars($lokasiDB) ?>
        </h3>

        <a href="undangan.php<?= isset($_GET['nama']) ? '?nama='.urlencode($_GET['nama']) : '' ?>"
           class="open-invitation-btn">Buka Undangan 💌</a>

        <button class="music-toggle-btn" id="musicToggleBtn">
            <span id="musicIcon">🔊</span> Musik
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-200 italic">Selamat datang di undangan pernikahan kami!</p>
            <?php if($namaTamu !== DEFAULT_GUEST_NAME): ?>
            <p class="text-sm text-[#e0d5c1] font-semibold mt-2">Khusus untuk: <?= $namaTamu ?></p>
            <?php endif; ?>
            <p class="text-xs text-gray-300 mt-2">
                <?= $hariTersisa > 0 ? "Acara dalam {$hariTersisa} hari lagi" : "Acara sudah berlangsung" ?>
            </p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn   = document.getElementById('musicToggleBtn');
        const music = document.getElementById('backgroundMusic');
        const icon  = document.getElementById('musicIcon');
        let playing = false;

        if (localStorage.getItem('undanganMusicPlaying') === 'true') {
            music.play().then(() => { playing = true; updateIcon(); }).catch(() => {});
        }

        btn.addEventListener('click', () => {
            if (playing) {
                music.pause(); playing = false;
                localStorage.setItem('undanganMusicPlaying', 'false');
            } else {
                music.play().catch(() => {});
                playing = true;
                localStorage.setItem('undanganMusicPlaying', 'true');
            }
            updateIcon();
        });

        function updateIcon() { icon.textContent = playing ? '🔊' : '🔇'; }
    });
    </script>
</body>
</html>
