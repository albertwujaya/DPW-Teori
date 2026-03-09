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
    document.getElementById("days").innerHTML = days;
    document.getElementById("hours").innerHTML = hours;
    document.getElementById("minutes").innerHTML = minutes;
    document.getElementById("seconds").innerHTML = seconds;

    // Jika waktu habis
    if (distance < 0) {
        clearInterval(countdownTask);
        document.querySelector(".countdown").innerHTML = "ACARA SEDANG BERLANGSUNG";
    }
}, 1000);