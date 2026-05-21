// ============ COUNTDOWN TIMER ============
// Set tanggal tujuan (2 Agustus 2032)
const weddingDate = new Date("Aug 2, 2032 14:00:00").getTime();

const countdownTask = setInterval(function() {
    const now = new Date().getTime();
    const distance = weddingDate - now;

    // Kalkulasi waktu
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Tampilkan hasil ke elemen HTML
    const daysEl = document.getElementById("days");
    const hoursEl = document.getElementById("hours");
    const minutesEl = document.getElementById("minutes");
    const secondsEl = document.getElementById("seconds");

    if (daysEl) daysEl.innerHTML = days;
    if (hoursEl) hoursEl.innerHTML = hours;
    if (minutesEl) minutesEl.innerHTML = minutes;
    if (secondsEl) secondsEl.innerHTML = seconds;

    // Jika waktu habis
    if (distance < 0) {
        clearInterval(countdownTask);
        if (daysEl) daysEl.innerHTML = "0";
        if (hoursEl) hoursEl.innerHTML = "0";
        if (minutesEl) minutesEl.innerHTML = "0";
        if (secondsEl) secondsEl.innerHTML = "0";
    }
}, 1000);

// ============ KONTROL UNDANGAN & MUSIK ============
document.addEventListener('DOMContentLoaded', function() {
    // Element referensi
    const openInvitationBtn = document.getElementById('openInvitationBtn');
    const musicToggleBtn = document.getElementById('musicToggleBtn');
    const backgroundMusic = document.getElementById('backgroundMusic');
    const musicIcon = document.getElementById('musicIcon');
    const invitationContent = document.getElementById('invitation-content');
    
    let isMusicPlaying = false;
    let isInvitationOpened = false;

    // Fungsi untuk membuka undangan (scroll ke bawah)
    if (openInvitationBtn) {
        openInvitationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tampilkan konten undangan
            if (invitationContent && !isInvitationOpened) {
                invitationContent.classList.remove('invitation-hidden');
                invitationContent.classList.add('invitation-visible');
                isInvitationOpened = true;
                
                // Tunggu animasi opacity selesai, kemudian scroll
                setTimeout(() => {
                    invitationContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
            
            // Trigger autoplay musik (jika belum diputar)
            if (!isMusicPlaying && backgroundMusic) {
                playMusic();
            }
        });
    }

    // Fungsi untuk toggle musik
    if (musicToggleBtn) {
        musicToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (isMusicPlaying) {
                pauseMusic();
            } else {
                playMusic();
            }
        });
    }

    // Function untuk memutar musik
    function playMusic() {
        if (backgroundMusic) {
            backgroundMusic.play().catch(function(error) {
                console.log('Autoplay tertahan. Pengguna perlu interaksi pertama.', error);
            });
            isMusicPlaying = true;
            updateMusicIcon();
        }
    }

    // Function untuk pause musik
    function pauseMusic() {
        if (backgroundMusic) {
            backgroundMusic.pause();
            isMusicPlaying = false;
            updateMusicIcon();
        }
    }

    // Update icon musik
    function updateMusicIcon() {
        if (musicIcon) {
            musicIcon.textContent = isMusicPlaying ? '🔊' : '🔇';
        }
    }

    // Detect ketika musik sedang diputar/dihentikan dari event listener
    if (backgroundMusic) {
        backgroundMusic.addEventListener('play', function() {
            isMusicPlaying = true;
            updateMusicIcon();
        });

        backgroundMusic.addEventListener('pause', function() {
            isMusicPlaying = false;
            updateMusicIcon();
        });
    }

    // Copy button untuk nomor rekening
    const copyBtns = document.querySelectorAll('.copy-btn');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const text = '123456789';
            navigator.clipboard.writeText(text).then(() => {
                const originalText = this.textContent;
                this.textContent = '✓ Tersalin!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Error copying text: ', err);
            });
        });
    });
});