<?php
require_once __DIR__ . '/config.php';

// Ambil data dari DB
$namaPria    = getSetting('nama_pria',     NAMA_MEMPELAI_PRIA);
$namaWanita  = getSetting('nama_wanita',   NAMA_MEMPELAI_WANITA);
$tanggalDB   = getSetting('tanggal_acara', TANGGAL_ACARA);
$waktuDB     = getSetting('waktu_acara',   WAKTU_ACARA);
$lokasiDB    = getSetting('lokasi_acara',  LOKASI_ACARA);
$bankName    = getSetting('bank_name',     BANK_NAME);
$noRek       = getSetting('nomor_rekening',NOMOR_REKENING);
$putraDari   = getSetting('putra_dari',    'Bapak & Ibu');
$putriDari   = getSetting('putri_dari',    'Bapak & Ibu');
$dresscode   = getSetting('dresscode',     'Pakaian Rapi & Sopan');
$catatan     = getSetting('catatan_tambahan','Mohon doa restu agar acara diberkati');

$atasNama    = $namaPria . ' / ' . $namaWanita;
$siteTitle   = 'Undangan Pernikahan ' . $namaPria . ' & ' . $namaWanita;
$namaTamu    = DEFAULT_GUEST_NAME;
$guestSlug   = trim((string)($_GET['guest'] ?? ''));
if ($guestSlug !== '') {
    $stmt = getDB()->prepare("SELECT nama FROM rsvp WHERE slug = ? LIMIT 1");
    $stmt->execute([$guestSlug]);
    $row = $stmt->fetch();
    if ($row && !empty($row['nama'])) {
        $namaTamu = htmlspecialchars($row['nama']);
    } elseif (isset($_GET['nama'])) {
        $namaTamu = htmlspecialchars($_GET['nama']);
    }
} elseif (isset($_GET['nama'])) {
    $namaTamu = htmlspecialchars($_GET['nama']);
}
$hariTersisa = hitung_hari_tersisa();
$tanggalFmt  = format_tanggal_indonesia($tanggalDB);
$salam       = salam_waktu();

$jadwal = [
    ['nama' => 'Akad Nikah', 'waktu' => '14.00 - 15.30 WIB', 'lokasi' => $lokasiDB],
    ['nama' => 'Resepsi',    'waktu' => '16.00 - Selesai WIB','lokasi' => $lokasiDB],
];
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
        body { font-family:'Poppins',sans-serif; color:#333; background-color:#f8fcf7; line-height:1.6; text-align:center; }
        h1,h2,h3,h4 { font-family:'Playfair Display',serif; margin-bottom:15px; }
        .input-underline { border:none; border-bottom:2px solid #ddd; background:transparent; transition:0.3s; }
        .input-underline:focus { outline:none; border-color:#572932 !important; }
        .back-to-cover {
            position:fixed; top:20px; left:20px;
            background:rgba(93,59,82,0.9); color:white; border:none;
            padding:10px 20px; border-radius:50px; cursor:pointer;
            font-weight:600; z-index:100; transition:all 0.3s; text-decoration:none; display:inline-block;
        }
        .back-to-cover:hover { background:rgba(93,59,82,1); transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.2); }
        html { scroll-behavior:smooth; }
        .welcome-message { background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%); border-left:4px solid #572932; padding:20px; margin:0; }
        /* Toast notification */
        #toast { position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#3d5a44; color:#fff;
            padding:14px 28px; border-radius:50px; font-size:0.9rem; font-weight:600;
            box-shadow:0 6px 20px rgba(0,0,0,0.2); opacity:0; pointer-events:none; transition:opacity 0.4s; z-index:999; }
        #toast.show { opacity:1; }
    </style>
</head>
<body>

<audio id="backgroundMusic" loop>
    <source src="https://assets.mixkit.co/active_storage/sfx/2727/2727-preview.mp3" type="audio/mpeg">
</audio>

<a href="index.php<?= isset($_GET['nama']) ? '?nama='.urlencode($_GET['nama']) : '' ?>" class="back-to-cover">← Kembali</a>

<!-- Welcome Banner -->
<div class="welcome-message">
    <h3 style="color:#572932;font-weight:700;"><?= $salam ?>, <?= $namaTamu ?>! 👋</h3>
    <p style="color:#555;margin-top:8px;">Terima kasih atas doa restu dan kehadiran Anda. Selamat datang di undangan pernikahan kami!</p>
    <p style="font-size:0.85rem;color:#888;margin-top:4px;">Acara akan dilaksanakan dalam: <?= $hariTersisa > 0 ? $hariTersisa.' hari lagi' : 'Acara sudah berlangsung' ?></p>
</div>

<!-- Countdown -->
<div class="py-20 px-5 bg-white">
    <h2 class="text-4xl text-[#3d5a44] mb-8">Menghitung Hari</h2>
    <div class="flex justify-center gap-4 flex-wrap max-w-4xl mx-auto">
        <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
            <span id="days" class="text-4xl font-bold block leading-tight"><?= $hariTersisa ?></span>
            <p class="text-sm mt-1 uppercase tracking-wider">Hari</p>
        </div>
        <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
            <span id="hours" class="text-4xl font-bold block leading-tight">0</span>
            <p class="text-sm mt-1 uppercase tracking-wider">Jam</p>
        </div>
        <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
            <span id="minutes" class="text-4xl font-bold block leading-tight">0</span>
            <p class="text-sm mt-1 uppercase tracking-wider">Menit</p>
        </div>
        <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
            <span id="seconds" class="text-4xl font-bold block leading-tight">0</span>
            <p class="text-sm mt-1 uppercase tracking-wider">Detik</p>
        </div>
    </div>
</div>

<!-- Kepada -->
<div class="py-20 px-5 bg-[#f1f5f0]">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-4xl text-[#572932] mb-12">Kepada</h2>
        <div class="flex flex-wrap justify-center items-start gap-10">
            <div class="flex-shrink-0">
                <img src="grooms.png" alt="Foto Mempelai Pria" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
            </div>
            <div class="flex-1 min-w-[300px] px-5 text-left">
                <h2 class="text-3xl text-[#572932]">Kepada</h2>
                <p class="text-xl font-bold text-[#572932] mb-3"><?= $namaTamu ?></p>
                <p class="text-justify text-gray-600 mb-5">
                    Dengan memohon rahmat dan ridho Tuhan Yang Maha Esa, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami.
                    Oleh karena itu, dengan segala kerendahan hati kami mengundang <?= $namaTamu ?> untuk berkenan hadir serta memberikan doa restu
                    pada acara pernikahan yang akan kami laksanakan pada tanggal <?= $tanggalFmt ?> pukul <?= htmlspecialchars($waktuDB) ?> WIB.
                </p>
            </div>
            <div class="flex-shrink-0">
                <img src="brides.png" alt="Foto Mempelai Wanita" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Informasi Acara -->
<div class="py-20 px-5 bg-white">
    <div class="max-w-3xl mx-auto bg-white rounded-3xl p-10 shadow-xl border border-gray-100">
        <div class="mb-5">
            <span class="block text-xs uppercase tracking-widest text-gray-500">Mempelai</span>
            <p class="text-xl font-semibold text-gray-700"><?= htmlspecialchars($namaPria) ?> &amp; <?= htmlspecialchars($namaWanita) ?></p>
        </div>
        <div class="mb-5">
            <span class="block text-xs uppercase tracking-widest text-gray-500">Putra/Putri Dari</span>
            <p class="text-lg text-gray-600 italic">
                Putra dari <?= htmlspecialchars($putraDari) ?>
                <br> &amp; <br>
                Putri dari <?= htmlspecialchars($putriDari) ?>
            </p>
        </div>
        <hr class="my-6 border-gray-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <?php foreach($jadwal as $acara): ?>
            <div class="bg-[#fdfaf7] p-4 rounded-lg border-l-4 border-[#572932]">
                <h4 class="text-[#572932] font-bold mb-1"><?= htmlspecialchars($acara['nama']) ?></h4>
                <p class="text-gray-600"><?= htmlspecialchars($acara['waktu']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-8">
            <p class="text-gray-600 mb-4"><strong>Lokasi:</strong><br> <?= htmlspecialchars($lokasiDB) ?></p>
            <a class="inline-block px-6 py-3 bg-[#6f816a] text-white rounded-lg font-bold text-sm hover:bg-[#3d5a44] transition"
               href="https://maps.google.com/?q=<?= urlencode($lokasiDB) ?>" target="_blank">📍 Lihat di Peta</a>
        </div>
        <div class="text-center mb-8">
            <img src="couple.png" alt="Foto Mempelai" class="max-w-96 rounded-2xl mx-auto my-8 border-8 border-white shadow-lg">
        </div>
        <div class="mb-8 text-left">
            <ul class="list-none space-y-2">
                <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span>Dresscode: <?= htmlspecialchars($dresscode) ?></li>
                <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span><?= htmlspecialchars($catatan) ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- RSVP & Kado -->
<div class="max-w-4xl mx-auto my-20 px-5">
    <div class="flex flex-wrap bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
        <!-- RSVP Form -->
        <div class="flex-1 min-w-[320px] p-10 text-left">
            <h3 class="text-[#572932] text-3xl mb-6 text-center">Konfirmasi Kehadiran</h3>
            <div id="rsvpSuccess" style="display:none;background:#e8f5e9;border-left:4px solid #4caf50;padding:16px;border-radius:8px;margin-bottom:20px;color:#2e7d32;font-weight:600;">
                ✅ Konfirmasi berhasil dikirim! Terima kasih.
            </div>
            <form id="rsvpForm">
                <label class="block mb-1 font-semibold text-sm text-gray-600">Nama Lengkap:</label>
                <input type="text" name="name" placeholder="Contoh: Budi Santoso"
                    value="<?= ($namaTamu !== DEFAULT_GUEST_NAME) ? htmlspecialchars($namaTamu) : '' ?>"
                    class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base" required>

                <label class="block mb-1 font-semibold text-sm text-gray-600">Kehadiran:</label>
                <select name="attendance" class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base" required>
                    <option value="" disabled selected>Apakah Anda akan hadir?</option>
                    <option value="ya">Ya, Saya Hadir</option>
                    <option value="tidak">Maaf, Tidak Bisa Hadir</option>
                </select>

                <label class="block mb-1 font-semibold text-sm text-gray-600">Jumlah Tamu:</label>
                <input type="number" name="guests" min="1" max="10" placeholder="Jumlah orang"
                    class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base">

                <label class="block mb-1 font-semibold text-sm text-gray-600">Ucapan &amp; Doa:</label>
                <textarea name="notes" rows="4" placeholder="Tuliskan pesan manis Anda untuk mempelai..."
                    class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base"></textarea>

                <button type="submit" id="rsvpBtn"
                    class="w-full py-4 bg-[#572932] text-white border-0 rounded-lg font-bold uppercase tracking-widest hover:bg-[#361a1f] transition transform hover:-translate-y-0.5">
                    Kirim Konfirmasi
                </button>
                <div id="rsvpError" style="display:none;color:#9b2335;font-size:0.85rem;margin-top:10px;"></div>
            </form>
        </div>

        <!-- Kado Digital -->
        <div class="flex-1 min-w-[320px] p-10 bg-[#fdfaf7] border-l border-gray-200 flex flex-col justify-center">
            <h3 class="text-[#572932] text-3xl mb-6 text-center">Kado Digital</h3>
            <p class="text-gray-600 mb-6">Doa restu Anda adalah kado terindah. Namun jika ingin memberi lebih, Anda dapat mengirimkannya melalui:</p>
            <div class="space-y-6">
                <div class="text-center">
                    <h4 class="text-[#572932] font-bold mb-2">💳 Transfer Bank</h4>
                    <p class="text-gray-600 text-sm mb-3">
                        <?= htmlspecialchars($bankName) ?><br>
                        <strong><?= htmlspecialchars($noRek) ?></strong><br>
                        a.n <?= htmlspecialchars($atasNama) ?>
                    </p>
                    <button class="copy-btn px-4 py-2 bg-[#6f816a] text-white rounded font-bold text-sm hover:bg-[#3d5a44] transition"
                        data-value="<?= htmlspecialchars($noRek) ?>">Salin No. Rekening</button>
                </div>
                <hr class="border-gray-300">
                <div class="text-center">
                    <h4 class="text-[#572932] font-bold mb-2">🎁 Secara Langsung</h4>
                    <p class="text-gray-600 text-sm">Anda dapat memberikan kado fisik secara langsung di lokasi acara melalui kotak yang tersedia.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast"></div>

<script>
// ============ COUNTDOWN ============
const weddingDate = new Date("<?= date('M j, Y', strtotime($tanggalDB)) ?> <?= $waktuDB ?>:00").getTime();
const countdownTask = setInterval(() => {
    const dist = weddingDate - Date.now();
    if (dist < 0) { clearInterval(countdownTask); return; }
    document.getElementById('days').textContent    = Math.floor(dist / 86400000);
    document.getElementById('hours').textContent   = Math.floor((dist % 86400000) / 3600000);
    document.getElementById('minutes').textContent = Math.floor((dist % 3600000) / 60000);
    document.getElementById('seconds').textContent = Math.floor((dist % 60000) / 1000);
}, 1000);

// ============ RSVP AJAX ============
document.getElementById('rsvpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('rsvpBtn');
    const errEl = document.getElementById('rsvpError');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';
    errEl.style.display = 'none';

    const formData = new FormData(this);
    try {
        const res  = await fetch('rsvp_submit.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            document.getElementById('rsvpSuccess').style.display = 'block';
            this.reset();
            showToast('✅ ' + data.message);
        } else {
            errEl.textContent = data.message;
            errEl.style.display = 'block';
        }
    } catch(err) {
        errEl.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
        errEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Kirim Konfirmasi';
    }
});

// ============ COPY REKENING ============
document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        navigator.clipboard.writeText(this.dataset.value).then(() => {
            const orig = this.textContent;
            this.textContent = '✓ Tersalin!';
            setTimeout(() => this.textContent = orig, 2000);
        });
    });
});

// ============ MUSIK ============
document.addEventListener('DOMContentLoaded', () => {
    const music = document.getElementById('backgroundMusic');
    if (localStorage.getItem('undanganMusicPlaying') === 'true' && music) {
        music.play().catch(() => {});
    }
});

// ============ TOAST ============
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}
</script>
</body>
</html>
