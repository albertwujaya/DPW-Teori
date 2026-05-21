document.addEventListener('DOMContentLoaded', function() {
    // INISIALISASI
    const coverPage = document.querySelector('.cover-page');
    const allContentPages = document.querySelectorAll('[style*="display"], .countdown-page, .page-two, .rsvp-container');
    const guestGreeting = document.querySelector('.guest-greeting');
    
    // Buat tombol "Buka Undangan" di cover
    createOpenButton();
    
    // Buat modal input nama
    createNameModal();
    
    // FUNGSI-FUNGSI 
    
    //Membuat tombol "Buka Undangan" di halaman cover
    function createOpenButton() {
        const button = document.createElement('button');
        button.className = 'open-invitation-btn';
        button.innerHTML = 'Buka Undangan';
        button.setAttribute('aria-label', 'Buka undangan pernikahan');
    
        coverPage.appendChild(button);
        
        button.addEventListener('click', function() {
            openInvitation();
        });
    }
    
    /**
     * Membuka undangan (sembunyikan cover, tampilkan konten)
     */
    function openInvitation() {
        coverPage.style.display = 'none';
        
        // Tampilkan semua halaman konten
        const countdownPage = document.querySelector('.countdown-page');
        const pageTwo = document.querySelector('.page-two');
        const rsvpContainer = document.querySelector('.rsvp-container');
        
        if (countdownPage) countdownPage.style.display = 'block';
        if (pageTwo) pageTwo.style.display = 'block';
        if (rsvpContainer) rsvpContainer.style.display = 'block';
        
        // Inisialisasi countdown
        initializeCountdown();
        
        // SELALU tampilkan modal input nama
        showNameModal();
    }
    
    /**
     * Inisialisasi countdown timer
     */
    function initializeCountdown() {
        const weddingDate = new Date('Aug 2, 2032 14:00:00').getTime();
        
        const updateCountdown = function() {
            const now = new Date().getTime();
            const distance = weddingDate - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            const daysEl = document.getElementById('days');
            const hoursEl = document.getElementById('hours');
            const minutesEl = document.getElementById('minutes');
            const secondsEl = document.getElementById('seconds');
            
            if (daysEl) daysEl.innerHTML = days;
            if (hoursEl) hoursEl.innerHTML = hours;
            if (minutesEl) minutesEl.innerHTML = minutes;
            if (secondsEl) secondsEl.innerHTML = seconds;
        };
        
        // Update countdown immediately and then every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    /**
     * Membuat modal untuk input nama tamu
     */
    function createNameModal() {
        const modal = document.createElement('div');
        modal.id = 'nameInputModal';
        modal.className = 'name-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Selamat Datang!</h2>
                    <p>Silahkan masukkan nama Anda</p>
                </div>
                <div class="modal-body">
                    <input 
                        type="text" 
                        id="guestNameInput" 
                        class="name-input" 
                        placeholder="Masukkan nama lengkap Anda" 
                        autocomplete="off"
                        maxlength="100"
                    >
                </div>
                <div class="modal-footer">
                    <button id="submitNameBtn" class="submit-btn">Lanjutkan</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Event listener untuk tombol submit
        document.getElementById('submitNameBtn').addEventListener('click', submitGuestName);
        
        // Event listener untuk Enter key
        document.getElementById('guestNameInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                submitGuestName();
            }
        });
    }
    
    /**
     * Menampilkan modal input nama
     */
    function showNameModal() {
        const modal = document.getElementById('nameInputModal');
        const nameInput = document.getElementById('guestNameInput');
        
        // Kosongkan input field
        nameInput.value = '';
        
        modal.style.display = 'flex';
        
        // Focus pada input
        setTimeout(() => {
            nameInput.focus();
        }, 100);
    }
    
    /**
     * Menyimpan nama tamu dan menutup modal
     */
    function submitGuestName() {
        const nameInput = document.getElementById('guestNameInput');
        const guestName = nameInput.value.trim();
        
        if (!guestName) {
            alert('Mohon masukkan nama Anda');
            nameInput.focus();
            return;
        }
        
        // Format nama (capitalize first letter of each word)
        const formattedName = formatName(guestName);
        
        // Simpan ke localStorage
        localStorage.setItem('guestName', formattedName);
        
        // Update di halaman
        if (guestGreeting) {
            guestGreeting.textContent = formattedName;
        }
        
        // Tutup modal
        const modal = document.getElementById('nameInputModal');
        modal.style.display = 'none';
    }
    
    /**
     * Format nama (capitalize each word)
     */
    function formatName(name) {
        return name
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }
});

/**
 * Fungsi tambahan: Reset undangan (untuk testing)
 * Hapus comment jika ingin menggunakan
 */
function resetInvitation() {
    localStorage.removeItem('guestName');
    location.reload();
}

// Shortcut: Tekan Ctrl+Shift+R untuk reset (optional)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.key === 'R') {
        resetInvitation();
    }
})

// Fungsi untuk Salin Nomor Rekening
document.querySelector('.copy-btn').addEventListener('click', function() {
    const accountNo = "123456789"; // Sesuaikan dengan nomor rekening Anda
    navigator.clipboard.writeText(accountNo).then(() => {
        const originalText = this.innerText;
        this.innerText = "✓ Tersalin!";
        this.style.backgroundColor = "#3d5a44"; // Berubah jadi hijau saat berhasil
        
        setTimeout(() => {
            this.innerText = originalText;
            this.style.backgroundColor = "#6f816a";
        }, 2000);
    });
});

