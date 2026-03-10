// script.js
let dataNegara = {};

document.addEventListener('DOMContentLoaded', function() {
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            dataNegara = data;
            tampilkanForm();
        })
        .catch(error => {
            console.error('Error loading data:', error);
            document.getElementById('content').innerHTML = '<p>Gagal memuat data</p>';
        });
});

function tampilkanForm() {
    const container = document.getElementById('content');
    
    let html = `
        <div class="form-section">
            <label>Halaman ini menyatakan</label>
            <p style="font-size: 0.95rem; color: #666; margin-bottom: 1rem;">Tuliskan nama Kamu</p>
            <div class="input-group">
                <input type="text" id="namaInput" placeholder="">
                <button id="simpanBtn" onclick="handleSubmit()">Oke</button>
                <button style="background: white; color: #0066cc; border: 2px solid #0066cc; border-radius: 60px; padding: 12px 30px; font-weight: 600; cursor: pointer; font-size: 1rem;" onclick="resetForm()">Batal</button>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
    document.getElementById('namaInput').focus();
}

function handleSubmit() {
    const nama = document.getElementById('namaInput').value.trim();
    
    if (nama === '') {
        alert('Silakan masukkan nama Anda');
        return;
    }
    
    tampilkanKonten(nama);
}

function resetForm() {
    document.getElementById('namaInput').value = '';
    document.getElementById('namaInput').focus();
}

function tampilkanKonten(nama) {
    const container = document.getElementById('content');
    
    let html = `
        <div class="greeting">
            <strong>Nama saya ${nama}, saya <strong>akan mengamalkan Pancasila dan UUD 1945 sebagai Dasar Negara.</strong>
        </div>
        
        <h1>${dataNegara.judul}</h1>
        
        <h2>${dataNegara.pembukaan.judul}</h2>
    `;
    
    // Tampilkan setiap alinea dengan highlight hanya pada baris pertama
    dataNegara.pembukaan.alinea.forEach((alinea, index) => {
        let className = '';
        
        if (index === 0) {
            className = 'alinea highlight-pink';
        } else if (index === 1) {
            className = 'alinea highlight-red';
        } else if (index === 2) {
            className = 'alinea highlight-green';
        } else if (index === 3) {
            className = 'alinea highlight-blue';
        }
        
        html += `<p class="${className}">${alinea}</p>`;
    });
    
    // Tampilkan Pancasila
    html += `
        <h2>${dataNegara.pancasila.judul}</h2>
        <ol class="pancasila-list">
    `;
    
    dataNegara.pancasila.butir.forEach(butir => {
        html += `<li>${butir}</li>`;
    });
    
    html += `</ol>`;
    
    container.innerHTML = html;
}