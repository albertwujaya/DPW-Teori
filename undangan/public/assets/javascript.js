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