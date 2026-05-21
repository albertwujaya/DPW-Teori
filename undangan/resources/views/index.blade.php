<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Undangan Pernikahan Albert & Selviana</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: #333; background-color: #f8fcf7; line-height: 1.6; text-align: center; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; margin-bottom: 15px; }
        .cover-page {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), 
                        url('{{ asset('assets/heading.jpg') }}') no-repeat center center/cover !important;
        }
        .input-underline { border: none; border-bottom: 2px solid #ddd; background: transparent; transition: 0.3s; }
        .input-underline:focus { outline: none; border-color: #572932 !important; }
        .splash-screen { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; background: #000; cursor: pointer; transition: opacity 1s ease-in-out; }
        .splash-screen.hidden { opacity: 0; pointer-events: none; }
        .splash-screen img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .tap-hint { position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.3); border-radius: 999px; padding: 10px 24px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; animation: tapPulse 2s ease-in-out infinite; white-space: nowrap; }
        @keyframes tapPulse { 0%, 100% { opacity: 0.7; transform: translateX(-50%) scale(1); } 50% { opacity: 1; transform: translateX(-50%) scale(1.05); } }
        #audioToggle { position: fixed; bottom: 16px; right: 16px; z-index: 200; background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); border-radius: 9999px; padding: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); border: none; cursor: pointer; transition: background 0.3s; }
        #audioToggle:hover { background: rgba(255,255,255,0.3); }
        /* Toast notification */
        #toast { position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#3d5a44; color:#fff;
            padding:14px 28px; border-radius:50px; font-size:0.9rem; font-weight:600;
            box-shadow:0 6px 20px rgba(0,0,0,0.2); opacity:0; pointer-events:none; transition:opacity 0.4s; z-index:999; }
        #toast.show { opacity:1; }
    </style>
</head>
<body>

    <div id="splash-1" class="splash-screen" onclick="goToStage2()">
        <img src="{{ asset('assets/cover.png') }}" alt="Cover Undangan">
        <div class="tap-hint">✦ Tap untuk Membuka ✦</div>
    </div>

    <div id="splash-2" class="splash-screen" style="opacity:0; pointer-events:none;" onclick="goToInvitation()">
        <img src="{{ asset('assets/AlbertSelviana.png') }}" alt="Albert & Selviana">
        <div class="tap-hint">✦ Tap untuk Masuk ✦</div>
    </div>

    <button id="audioToggle">
        <svg id="audioOnIcon" xmlns="http://www.w3.org/2000/svg" class="hidden w-6 h-6" style="color:#fff; width:24px; height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
        </svg>
        <svg id="audioOffIcon" xmlns="http://www.w3.org/2000/svg" style="color:#fff; width:24px; height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
        </svg>
    </button>

    <div class="cover-page h-screen flex flex-col justify-center items-center text-white p-5">
        <h2 class="text-[#e0d5c1]">Undangan Pernikahan</h2>
        <h1 class="text-6xl my-6 leading-tight text-white">Albert <br> & <br> Selviana</h1>
        <p class="text-lg text-gray-100">Save The Date <br> Minggu | 2 Agustus 2032 | 14.00 WIB</p>
        <h3 class="mt-7 text-base font-normal text-[#e0d5c1]">Location :<br> 123 Anywhere St.. Any City, ST 12345</h3>
    </div>

    <div class="py-20 px-5 bg-white">
        <h2 class="text-4xl text-[#3d5a44] mb-8">Menghitung Hari</h2>
        <div class="flex justify-center gap-4 flex-wrap max-w-4xl mx-auto">
            <div class="bg-[#6f816a] text-white p-5 rounded-lg min-w-[85px] shadow-lg">
                <span id="days" class="text-4xl font-bold block leading-tight">0</span>
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

    <div class="py-20 px-5 bg-[#f1f5f0]">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl text-[#572932] mb-12">Kepada</h2>
            <div class="flex flex-wrap justify-center items-start gap-10">
                <div class="flex-shrink-0">
                    <img src="{{ asset('assets/grooms.png') }}" alt="Foto Mempelai Pria" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
                </div>
                <div class="flex-1 min-w-[300px] px-5 text-left">
                    <h2 class="text-3xl text-[#572932]">Kepada</h2>
                    <p class="text-xl font-bold text-[#572932] mb-3">Bapak/Ibu/Saudara/i</p>
                    <p class="text-justify text-gray-600 mb-5">Dengan memohon rahmat dan ridho Tuhan Yang Maha Esa, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami. Oleh karena itu, dengan segala kerendahan hati kami mengundang Bapak/Ibu/Saudara/i untuk berkenan hadir serta memberikan doa restu pada acara pernikahan yang akan kami laksanakan.</p>
                </div>
                <div class="flex-shrink-0">
                    <img src="{{ asset('assets/brides.png') }}" alt="Foto Mempelai Wanita" class="w-72 h-96 object-cover rounded-t-3xl border-4 border-[#6f816a] shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <div class="py-20 px-5 bg-white">
        <div class="max-w-3xl mx-auto bg-white rounded-3xl p-10 shadow-xl border border-gray-100">
            <div class="mb-5">
                <span class="block text-xs uppercase tracking-widest text-gray-500">Mempelai</span>
                <p class="text-xl font-semibold text-gray-700">Albert & Selviana</p>
            </div>
            <div class="mb-5">
                <span class="block text-xs uppercase tracking-widest text-gray-500">Putra/Putri Dari</span>
                <p class="text-lg text-gray-600 italic">Putra dari Mr. Darcy Esquire & Mrs. Elizabeth Bennet <br> & <br> Putri dari Mr. George William & Mrs. Sophia Charlotte</p>
            </div>
            <hr class="my-6 border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div class="bg-[#fdfaf7] p-4 rounded-lg border-l-4 border-[#572932]">
                    <h4 class="text-[#572932] font-bold mb-1">Akad Nikah</h4>
                    <p class="text-gray-600">14.00 - 15.30 WIB</p>
                </div>
                <div class="bg-[#fdfaf7] p-4 rounded-lg border-l-4 border-[#572932]">
                    <h4 class="text-[#572932] font-bold mb-1">Resepsi</h4>
                    <p class="text-gray-600">16.00 - Selesai WIB</p>
                </div>
            </div>
            <div class="mb-8">
                <p class="text-gray-600 mb-4"><strong>Lokasi:</strong><br> 123 Anywhere St.. Any City, ST 12345</p>
                <a class="inline-block px-6 py-3 bg-[#6f816a] text-white rounded-lg font-bold text-sm hover:bg-[#3d5a44] transition" href="https://maps.google.com/?q=123+Anywhere+St" target="_blank">📍 Lihat di Peta</a>
            </div>
            <div class="text-center mb-8">
                <img src="{{ asset('assets/couple.png') }}" alt="Foto Mempelai" class="max-w-96 rounded-2xl mx-auto my-8 border-8 border-white shadow-lg">
            </div>
            <div class="mb-8 text-left">
                <p class="text-gray-600 mb-4"><strong>Nama Keluarga Besar:</strong><br> Keluarga Besar Albert & Keluarga Besar Selviana</p>
                <ul class="list-none space-y-2">
                    <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span>Dresscode: Pakaian Rapi & Sopan</li>
                    <li class="text-gray-600 border-b border-dashed border-gray-200 pb-2"><span class="text-[#572932]">✦ </span>Mohon doa restu agar acara diberkati</li>
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

    <div class="max-w-4xl mx-auto my-20 px-5">
        <div class="flex flex-wrap bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
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
                    <input type="number" name="guests" min="1" max="10" placeholder="Jumlah orang" class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base">
                    
                    <label class="block mb-1 font-semibold text-sm text-gray-600">Ucapan &amp; Doa:</label>
                    <textarea name="notes" rows="4" placeholder="Tuliskan pesan manis Anda untuk mempelai..." class="input-underline w-full px-1 py-3 mb-5 font-inherit text-base"></textarea>
                    
                    <button type="submit" id="rsvpBtn" class="w-full py-4 bg-[#572932] text-white border-0 rounded-lg font-bold uppercase tracking-widest hover:bg-[#361a1f] transition transform hover:-translate-y-0.5">
                        Kirim Konfirmasi
                    </button>
                    <div id="rsvpError" style="display:none;color:#9b2335;font-size:0.85rem;margin-top:10px;"></div>
                </form>
            </div>
            <div class="flex-1 min-w-[320px] p-10 bg-[#fdfaf7] border-l border-gray-200 flex flex-col justify-center">
                <h3 class="text-[#572932] text-3xl mb-6 text-center">Kado Digital</h3>
                <p class="text-gray-600 mb-6">Doa restu Anda adalah kado terindah. Namun jika ingin memberi lebih, Anda dapat mengirimkannya melalui:</p>
                <div class="space-y-6">
                    <div class="text-center">
                        <h4 class="text-[#572932] font-bold mb-2">💳 Transfer Bank</h4>
                        <p class="text-gray-600 text-sm mb-3">Bank BCA<br><strong>123456789</strong><br>a.n Albert / Selviana</p>
                        <button class="copy-btn px-4 py-2 bg-[#6f816a] text-white rounded font-bold text-sm hover:bg-[#3d5a44] transition" data-value="123456789">Salin No. Rekening</button>
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

    <script src="{{ asset('assets/javascript.js') }}"></script>
   
    <script>
        const bgAudio = new Audio('{{ asset('assets/LanaDelRey_Salvatore.mp3') }}');
        bgAudio.loop = true;
        bgAudio.volume = 0.5;

        let isAudioPlaying = false;
        const audioToggle = document.getElementById('audioToggle');
        const audioOnIcon = document.getElementById('audioOnIcon');
        const audioOffIcon = document.getElementById('audioOffIcon');

        function playAudio() {
            bgAudio.play().catch(e => console.log('Autoplay diblokir browser.'));
            isAudioPlaying = true;
            audioOnIcon.classList.remove('hidden');
            audioOffIcon.classList.add('hidden');
        }

        function pauseAudio() {
            bgAudio.pause();
            isAudioPlaying = false;
            audioOnIcon.classList.add('hidden');
            audioOffIcon.classList.remove('hidden');
        }

        audioToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            isAudioPlaying ? pauseAudio() : playAudio();
        });

        const splash1 = document.getElementById('splash-1');
        const splash2 = document.getElementById('splash-2');

        function goToStage2() {
            splash1.style.opacity = '0';
            splash1.style.pointerEvents = 'none';
            splash2.style.opacity = '1';
            splash2.style.pointerEvents = 'auto';
            setTimeout(() => { splash1.style.display = 'none'; }, 1000);
            if (!isAudioPlaying) playAudio();
        }

        function goToInvitation() {
            splash2.style.opacity = '0';
            splash2.style.pointerEvents = 'none';
            setTimeout(() => { splash2.style.display = 'none'; }, 1000);
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

        // ============ TOAST ============
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3500);
        }
    </script>

    <!-- Toast Container -->
    <div id="toast"></div>

</body>
</html>