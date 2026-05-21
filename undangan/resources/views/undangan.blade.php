<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteTitle }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<a href="{{ url('/') }}{{ request()->has('nama') ? '?nama='.urlencode(request()->query('nama')) : '' }}" class="back-to-cover">← Kembali</a>

<!-- Welcome Banner -->
<div class="welcome-message">
    <h3 style="color:#572932;font-weight:700;">{{ $salam }}, {{ $namaTamu }}! 👋</h3>
    <p style="color:#555;margin-top:8px;">Terima kasih atas doa restu dan kehadiran Anda. Selamat datang di undangan pernikahan kami!</p>
    <p style="font-size:0.85rem;color:#888;margin-top:4px;">Acara akan dilaksanakan dalam: {{ $hariTersisa > 0 ? $hariTersisa.' hari lagi' : 'Acara sudah berlangsung' }}</p>
</div>

<!-- Countdown -->
<div class="py-20 px-5 bg-white">
    <h2 class="text-4xl text-[#3d5a44] mb-8">Menghitung Hari</h2>
    <div class="flex justify-center gap-4 flex-wrap max-w-4xl mx-auto">
        <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
            <span id="days" class="text-4xl font-bold block leading-tight">{{ $hariTersisa }}</span>
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
                <img src="{{ $fotoPria ? asset($fotoPria) : asset('assets/grooms.png') }}" alt="Foto Mempelai Pria" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
            </div>
            <div class="flex-1 min-w-[300px] px-5 text-left">
                <h2 class="text-3xl text-[#572932]">Kepada</h2>
                <p class="text-xl font-bold text-[#572932] mb-3">{{ $namaTamu }}</p>
                <p class="text-justify text-gray-600 mb-5">
                    Dengan memohon rahmat dan ridho Tuhan Yang Maha Esa, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami.
                    Oleh karena itu, dengan segala kerendahan hati kami mengundang {{ $namaTamu }} untuk berkenan hadir serta memberikan doa restu
                    pada acara pernikahan yang akan kami laksanakan pada tanggal {{ $tanggalFmt }} pukul {{ $waktuDB }} WIB.
                </p>
            </div>
            <div class="flex-shrink-0">
                <img src="{{ $fotoWanita ? asset($fotoWanita) : asset('assets/brides.png') }}" alt="Foto Mempelai Wanita" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Informasi Acara -->
<div class="py-20 px-5 bg-white">
    <div class="max-w-3xl mx-auto bg-white rounded-3xl p-10 shadow-xl border border-gray-100">
        <div class="mb-5">
            <span class="block text-xs uppercase tracking-widest text-gray-500">Mempelai</span>
            <p class="text-xl font-semibold text-gray-700">{{ $namaPria }} &amp; {{ $namaWanita }}</p>
        </div>
        <div class="mb-5">
            <span class="block text-xs uppercase tracking-widest text-gray-500">Putra/Putri Dari</span>
            <p class="text-lg text-gray-600 italic">
                Putra dari {{ $putraDari }}
                <br> &amp; <br>
                Putri dari {{ $putriDari }}
            </p>
        </div>
        <hr class="my-6 border-gray-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            @foreach($jadwal as $acara)
            <div class="bg-[#fdfaf7] p-4 rounded-lg border-l-4 border-[#572932]">
                <h4 class="text-[#572932] font-bold mb-1">{{ $acara['nama'] }}</h4>
                <p class="text-gray-600">{{ $acara['waktu'] }}</p>
            </div>
            @endforeach
        </div>
        <div class="mb-8">
            <p class="text-gray-600 mb-4"><strong>Lokasi:</strong><br> {{ $lokasiDB }}</p>
            <a class="inline-block px-6 py-3 bg-[#6f816a] text-white rounded-lg font-bold text-sm hover:bg-[#3d5a44] transition"
               href="https://maps.google.com/?q={{ urlencode($lokasiDB) }}" target="_blank">📍 Lihat di Peta</a>
        </div>
        <div class="text-center mb-8">
            <img src="{{ $fotoHero ? asset($fotoHero) : asset('assets/couple.png') }}" alt="Foto Mempelai" class="max-w-96 rounded-2xl mx-auto my-8 border-8 border-white shadow-lg">
        </div>
        <div class="mb-8 text-left">
            <ul class="list-none space-y-2">
                <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span>Dresscode: {{ $dresscode }}</li>
                <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span>{{ $catatan }}</li>
            </ul>
        </div>
    </div>
</div>

<!-- Galeri Foto -->
<div class="py-20 px-5 bg-[#fdfaf7]">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-4xl text-[#572932] mb-4 font-bold">Galeri Foto</h2>
        <p class="text-gray-600 mb-12 max-w-md mx-auto">Momen-momen indah kebersamaan kami yang diabadikan dalam bingkai cinta.</p>
        
        @if(count($galeri) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="gallery-grid">
            @foreach($galeri as $index => $item)
            <div class="aspect-square overflow-hidden rounded-xl shadow-lg border-2 border-white group relative cursor-pointer" onclick="openLightbox({{ $index }})">
                <img src="{{ asset($item->foto) }}" alt="Galeri" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-[#572932]/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#572932" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-gray-500 italic py-12 border-2 border-dashed border-gray-300 rounded-xl bg-white shadow-sm max-w-xl mx-auto">
            <div class="w-16 h-16 bg-[#fcf8f5] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#e8dcd3]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#a08085" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <p class="font-medium text-gray-700">Belum ada foto di galeri.</p>
            <p class="text-sm text-gray-500 mt-2">Silakan unggah foto dari Dashboard Admin.</p>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 z-[1000] bg-black/90 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center">
    <!-- Close Button -->
    <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-[1010] p-2 hover:bg-white/10 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Navigation: Prev -->
    <button onclick="prevImage(event)" class="absolute left-4 md:left-8 text-white/70 hover:text-white transition-colors z-[1010] p-3 hover:bg-white/10 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </button>

    <!-- Image Container -->
    <div class="max-w-[90%] max-h-[85%] md:max-w-[80%] md:max-h-[80%] flex flex-col items-center justify-center relative select-none">
        <img id="lightbox-img" src="" alt="Lightbox Image" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl border-4 border-white/10 scale-95 transition-transform duration-300 ease-out">
        <div id="lightbox-counter" class="absolute bottom-[-50px] text-white/80 font-medium tracking-wider text-sm bg-black/40 backdrop-blur-sm px-4 py-1.5 rounded-full border border-white/10"></div>
    </div>

    <!-- Navigation: Next -->
    <button onclick="nextImage(event)" class="absolute right-4 md:right-8 text-white/70 hover:text-white transition-colors z-[1010] p-3 hover:bg-white/10 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>
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
                @csrf
                <label class="block mb-1 font-semibold text-sm text-gray-600">Nama Lengkap:</label>
                <input type="text" name="name" placeholder="Contoh: Budi Santoso"
                    value="{{ ($namaTamu !== 'Tamu Undangan') ? $namaTamu : '' }}"
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
                        {{ $bankName }}<br>
                        <strong>{{ $noRek }}</strong><br>
                        a.n {{ $atasNama }}
                    </p>
                    <button class="copy-btn px-4 py-2 bg-[#6f816a] text-white rounded font-bold text-sm hover:bg-[#3d5a44] transition"
                        data-value="{{ $noRek }}">Salin No. Rekening</button>
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
const weddingDate = new Date("{{ \Carbon\Carbon::parse($tanggalDB)->format('M j, Y') }} {{ $waktuDB }}:00").getTime();
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
        const res  = await fetch('{{ url('rsvp') }}', { 
            method: 'POST', 
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('rsvpSuccess').style.display = 'block';
            this.reset();
            showToast('✅ ' + data.message);
        } else {
            errEl.textContent = data.message || data.error || 'Validasi Gagal.';
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

// ============ GALLERY LIGHTBOX ============
const galleryImages = [
    @foreach($galeri as $item)
        "{{ asset($item->foto) }}",
    @endforeach
];

let currentImageIndex = 0;
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const lightboxCounter = document.getElementById('lightbox-counter');

function openLightbox(index) {
    if (galleryImages.length === 0) return;
    currentImageIndex = index;
    updateLightbox();
    lightbox.classList.remove('opacity-0', 'pointer-events-none');
    setTimeout(() => {
        lightboxImg.classList.remove('scale-95');
        lightboxImg.classList.add('scale-100');
    }, 50);
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    lightbox.classList.add('opacity-0', 'pointer-events-none');
    lightboxImg.classList.remove('scale-100');
    lightboxImg.classList.add('scale-95');
    document.body.style.overflow = '';
}

function updateLightbox() {
    lightboxImg.src = galleryImages[currentImageIndex];
    lightboxCounter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
}

// Swipe gestures/touch navigation support (Optional but extremely premium!)
let touchStartX = 0;
let touchEndX = 0;

lightbox.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

lightbox.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleGesture();
});

function handleGesture() {
    if (touchEndX < touchStartX - 50) {
        nextImage();
    }
    if (touchEndX > touchStartX + 50) {
        prevImage();
    }
}

function nextImage(e) {
    if (e) e.stopPropagation();
    if (galleryImages.length === 0) return;
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    updateLightbox();
}

function prevImage(e) {
    if (e) e.stopPropagation();
    if (galleryImages.length === 0) return;
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    updateLightbox();
}

lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
        closeLightbox();
    }
});

document.addEventListener('keydown', (e) => {
    if (lightbox.classList.contains('pointer-events-none')) return;
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
    if (e.key === 'Escape') closeLightbox();
});
</script>
</body>
</html>
