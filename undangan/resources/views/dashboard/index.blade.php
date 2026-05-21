
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — {{ $namaPria }} &amp; {{ $namaWanita }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --olivine: #6f816a; --olivine-dark: #3d5a44; --olivine-light: #a8bba4;
            --olivine-pale: #f1f5f0; --burgundy: #572932; --burgundy-dark: #361a1f;
            --cream: #fdfbf7; --warm-brown: #4a3728; --sidebar-width: 260px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background-color:var(--olivine-pale); color:#333; min-height:100vh; }

        /* SIDEBAR */
        .sidebar { position:fixed; top:0; left:0; width:var(--sidebar-width); height:100vh;
            background:linear-gradient(160deg, var(--warm-brown) 0%, #2d1f16 100%);
            display:flex; flex-direction:column; z-index:100;
            transition:transform 0.35s cubic-bezier(0.4,0,0.2,1); overflow-y:auto; }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99; backdrop-filter:blur(2px); }
        .sidebar-logo { padding:28px 24px 20px; border-bottom:1px solid rgba(255,255,255,0.1); }
        .sidebar-logo h1 { font-family:'Cinzel',serif; font-size:1.1rem; color:#fff; letter-spacing:0.1em; line-height:1.3; }
        .sidebar-logo .couple-names { font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-style:italic; color:var(--olivine-light); margin-top:2px; }
        .sidebar-badge { display:inline-block; background:var(--burgundy); color:#fff; font-size:0.65rem; font-family:'Poppins',sans-serif;
            font-weight:600; letter-spacing:0.1em; text-transform:uppercase; padding:2px 10px; border-radius:20px; margin-top:6px; }
        .sidebar-nav { padding:20px 0; flex:1; }
        .nav-group-label { font-size:0.65rem; text-transform:uppercase; letter-spacing:0.15em; color:rgba(255,255,255,0.35);
            padding:12px 24px 6px; font-family:'Poppins',sans-serif; font-weight:600; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:11px 24px; color:rgba(255,255,255,0.65);
            text-decoration:none; font-size:0.875rem; font-weight:400; transition:all 0.2s;
            border-left:3px solid transparent; cursor:pointer; }
        .nav-item:hover { background:rgba(255,255,255,0.07); color:#fff; }
        .nav-item.active { background:rgba(111,129,106,0.18); color:var(--olivine-light); border-left-color:var(--olivine-light); font-weight:500; }
        .nav-item i { width:18px; text-align:center; font-size:0.95rem; }
        .sidebar-footer { padding:16px 24px; border-top:1px solid rgba(255,255,255,0.1); }
        .logout-btn { display:flex; align-items:center; gap:10px; color:#f87171; font-size:0.85rem;
            cursor:pointer; padding:8px 0; text-decoration:none; transition:color 0.2s; }
        .logout-btn:hover { color:#fca5a5; }

        /* MAIN */
        .main-wrapper { margin-left:var(--sidebar-width); min-height:100vh; display:flex; flex-direction:column;
            transition:margin-left 0.35s cubic-bezier(0.4,0,0.2,1); }

        /* TOPBAR */
        .topbar { background:#fff; border-bottom:1px solid #e8e8e0; padding:14px 24px;
            display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:50; gap:16px; }
        .hamburger { display:none; background:none; border:none; cursor:pointer; padding:6px; color:var(--warm-brown); font-size:1.2rem; }
        .topbar-title { font-family:'Cinzel',serif; font-size:1rem; color:var(--warm-brown); letter-spacing:0.05em; flex:1; }
        .search-box { display:flex; align-items:center; background:var(--olivine-pale); border:1px solid #ddd;
            border-radius:8px; padding:7px 14px; gap:8px; width:220px; transition:border-color 0.2s; }
        .search-box:focus-within { border-color:var(--olivine); }
        .search-box input { background:none; border:none; outline:none; font-family:'Poppins',sans-serif; font-size:0.85rem; color:#333; width:100%; }
        .search-box i { color:#999; font-size:0.85rem; }
        .topbar-actions { display:flex; align-items:center; gap:14px; }
        .admin-avatar { width:36px; height:36px; background:linear-gradient(135deg, var(--olivine), var(--burgundy));
            border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.8rem; font-weight:600; }

        /* CONTENT */
        .page-content { padding:28px; flex:1; }
        .section-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
        .section-header h2 { font-family:'Cinzel',serif; font-size:1.4rem; color:var(--warm-brown); letter-spacing:0.05em; }
        .section-header p { color:#888; font-size:0.8rem; margin-top:2px; }

        /* STAT CARDS */
        .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:28px; }
        .stat-card { background:#fff; border-radius:14px; padding:22px; display:flex; align-items:flex-start; gap:16px;
            border:1px solid #eee; transition:transform 0.2s,box-shadow 0.2s; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
        .stat-card.green::before { background:linear-gradient(90deg, var(--olivine), var(--olivine-light)); }
        .stat-card.red::before   { background:linear-gradient(90deg, var(--burgundy), #c75a6a); }
        .stat-card.gold::before  { background:linear-gradient(90deg, #b8860b, #d4a017); }
        .stat-card.brown::before { background:linear-gradient(90deg, var(--warm-brown), #7a5e52); }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.09); }
        .stat-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
        .stat-icon.green { background:#eef3ed; color:var(--olivine-dark); }
        .stat-icon.red   { background:#fceef0; color:var(--burgundy); }
        .stat-icon.gold  { background:#fdf7e5; color:#b8860b; }
        .stat-icon.brown { background:#f5ede8; color:var(--warm-brown); }
        .stat-info { flex:1; }
        .stat-label { font-size:0.75rem; color:#999; text-transform:uppercase; letter-spacing:0.08em; font-weight:500; }
        .stat-value { font-size:2rem; font-weight:700; color:#222; line-height:1.1; margin:4px 0 2px; font-family:'Cormorant Garamond',serif; }
        .stat-sub { font-size:0.75rem; color:#aaa; }

        /* TWO-COL */
        .two-col-grid { display:grid; grid-template-columns:1fr 340px; gap:20px; margin-bottom:28px; }

        /* CARDS */
        .card { background:#fff; border-radius:14px; border:1px solid #eee; overflow:hidden; }
        .card-header { padding:18px 22px; border-bottom:1px solid #f0ede8; display:flex; align-items:center; justify-content:space-between; }
        .card-title { font-family:'Cinzel',serif; font-size:0.95rem; color:var(--warm-brown); letter-spacing:0.04em; }
        .card-action { font-size:0.78rem; color:var(--olivine-dark); cursor:pointer; font-weight:500; text-decoration:none; transition:color 0.2s; }
        .card-action:hover { color:var(--warm-brown); }

        /* TABLE */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        thead tr { background:#fdfbf7; }
        thead th { padding:12px 16px; text-align:left; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.1em; font-weight:600; color:#999; white-space:nowrap; }
        tbody tr { border-top:1px solid #f5f5f0; transition:background 0.15s; }
        tbody tr:hover { background:#fdfcf9; }
        tbody td { padding:13px 16px; color:#444; vertical-align:middle; }
        .guest-name { font-weight:600; color:#222; }
        .guest-link { color:#572932; text-decoration:none; font-size:0.78rem; display:inline; max-width:240px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
        .guest-link:hover { text-decoration:underline; }
        .copy-btn { background:none; border:1px solid #ddd; color:#999; padding:3px 8px; border-radius:4px; cursor:pointer; font-size:0.75rem; transition:all 0.2s; }
        .copy-btn:hover { background:#f0ede8; color:#572932; border-color:#572932; }
        .guest-initials { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:600; color:#fff; flex-shrink:0; }
        .guest-cell { display:flex; align-items:center; gap:10px; }
        .badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; white-space:nowrap; }
        .badge-green { background:#e6f4ea; color:#2d6a4f; }
        .badge-red   { background:#fde8ea; color:#9b2335; }
        .badge-gold  { background:#fdf5e0; color:#8a6914; }
        .action-btn { background:none; border:1px solid #ddd; color:#999; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:0.8rem; transition:all 0.2s; }
        .action-btn:hover { background:#f0ede8; color:#572932; border-color:#572932; }
        .action-btn.edit-btn { color:#6f816a; border-color:#6f816a; }
        .action-btn.edit-btn:hover { background:#e6f4ea; color:#2d6a4f; border-color:#2d6a4f; }

        /* DONUT CHART */
        .rsvp-donut-wrap { padding:22px; display:flex; flex-direction:column; align-items:center; gap:20px; }
        .donut-chart { position:relative; width:160px; height:160px; }
        .donut-chart svg { transform:rotate(-90deg); }
        .donut-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; font-family:'Cormorant Garamond',serif; }
        .donut-center .donut-total { font-size:2rem; font-weight:700; color:var(--warm-brown); line-height:1; }
        .donut-center .donut-label { font-size:0.7rem; color:#aaa; font-family:'Poppins',sans-serif; }
        .legend-list { width:100%; }
        .legend-item { display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f5f5f0; font-size:0.82rem; }
        .legend-item:last-child { border-bottom:none; }
        .legend-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
        .legend-left { display:flex; align-items:center; gap:8px; color:#666; }
        .legend-count { font-weight:600; color:#333; }

        /* MESSAGES */
        .messages-list { padding:0 22px 16px; }
        .message-item { padding:14px 0; border-bottom:1px solid #f5f5f0; }
        .message-item:last-child { border-bottom:none; }
        .message-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:5px; }
        .message-author { font-size:0.85rem; font-weight:600; color:#333; }
        .message-time { font-size:0.72rem; color:#bbb; }
        .message-text { font-size:0.82rem; color:#777; font-style:italic; line-height:1.5; }
        .message-attendance { font-size:0.7rem; margin-top:5px; }

        /* EXPORT BTN */
        .export-btn { display:inline-flex; align-items:center; gap:8px; background:var(--olivine-dark); color:#fff;
            padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer;
            border:none; transition:background 0.2s,transform 0.15s; font-family:'Poppins',sans-serif; }
        .export-btn:hover { background:var(--warm-brown); transform:translateY(-1px); }

        /* FILTER ROW */
        .filter-row { display:flex; align-items:center; gap:10px; padding:14px 22px; border-bottom:1px solid #f0ede8; flex-wrap:wrap; }
        .filter-btn { padding:5px 14px; border-radius:20px; font-size:0.78rem; font-weight:500; cursor:pointer;
            border:1px solid #e0ddd5; background:#fff; color:#888; transition:all 0.2s; font-family:'Poppins',sans-serif; }
        .filter-btn.active,.filter-btn:hover { background:var(--warm-brown); color:#fff; border-color:var(--warm-brown); }
        .filter-search { margin-left:auto; display:flex; align-items:center; gap:6px; background:var(--olivine-pale);
            border:1px solid #e0ddd5; border-radius:8px; padding:5px 12px; }
        .filter-search input { border:none; background:none; outline:none; font-size:0.8rem; font-family:'Poppins',sans-serif; width:150px; }

        /* PAGE VIEWS */
        .page-view { display:none; }
        .page-view.active { display:block; }

        /* SETTINGS FORM */
        .settings-input { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px;
            font-size:0.9rem; font-family:'Poppins',sans-serif; transition:border-color 0.2s; }
        .settings-input:focus { outline:none; border-color:var(--olivine); }
        .form-field label { display:block; font-size:0.75rem; font-weight:600; color:#888;
            text-transform:uppercase; letter-spacing:0.06em; margin-bottom:5px; }
        .form-field { margin-bottom:16px; }
        .rsvp-form-preview { padding:20px 22px; background:var(--olivine-pale); margin:16px; border-radius:10px; border:1px solid #dde5db; }

        /* TOAST */
        #toast { position:fixed; bottom:28px; left:50%; transform:translateX(-50%);
            background:var(--warm-brown); color:#fff; padding:12px 28px; border-radius:50px;
            font-size:0.85rem; font-weight:600; box-shadow:0 6px 20px rgba(0,0,0,0.2);
            opacity:0; pointer-events:none; transition:opacity 0.3s; z-index:999; }
        #toast.show { opacity:1; }

        /* DELETE BTN */
        .del-btn { background:none; border:none; color:#ccc; cursor:pointer; transition:color 0.2s; padding:4px; }
        .del-btn:hover { color:#e53e3e; }

        /* PROGRESS */
        .progress-list { padding:8px 22px 22px; }
        .progress-item { margin-bottom:20px; }
        .progress-label { display:flex; justify-content:space-between; margin-bottom:7px; font-size:0.82rem; }
        .progress-label span:first-child { color:#555; font-weight:500; }
        .progress-label span:last-child { color:#888; }
        .progress-bar-bg { height:7px; background:#f0ede8; border-radius:20px; overflow:hidden; }
        .progress-bar-fill { height:100%; border-radius:20px; transition:width 1.2s cubic-bezier(0.4,0,0.2,1); }

        /* RESPONSIVE */
        @media (max-width:1100px) {
            .stats-grid { grid-template-columns:repeat(2,1fr); }
            .two-col-grid { grid-template-columns:1fr; }
        }
        @media (max-width:900px) {
            :root { --sidebar-width:0px; }
            .sidebar { width:260px; transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .sidebar-overlay.show { display:block; }
            .main-wrapper { margin-left:0; }
            .hamburger { display:flex; }
            .search-box { width:160px; }
        }
        @media (max-width:640px) {
            .stats-grid { grid-template-columns:1fr 1fr; gap:12px; }
            .page-content { padding:16px; }
            .stat-value { font-size:1.6rem; }
            .search-box { display:none; }
        }
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .stat-card { animation:fadeUp 0.4s ease both; }
        .stat-card:nth-child(1) { animation-delay:0.05s; }
        .stat-card:nth-child(2) { animation-delay:0.1s; }
        .stat-card:nth-child(3) { animation-delay:0.15s; }
        .stat-card:nth-child(4) { animation-delay:0.2s; }
        .card { animation:fadeUp 0.4s 0.25s ease both; }
        .ucapan-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:16px; }
        .ucapan-card { background:#fff; border-radius:12px; padding:20px; border:1px solid #eee; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <h1>Dashboard Admin</h1>
        <div class="couple-names" id="sb-couple">{{ $namaPria }} &amp; {{ $namaWanita }}</div>
        <span class="sidebar-badge">Wedding 2032</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu Utama</div>
        <a class="nav-item active" id="nav-overview" onclick="showPage('overview',this)">
            <i class="fas fa-chart-line"></i> Overview
        </a>
        <a class="nav-item" id="nav-tamu" onclick="showPage('tamu',this)">
            <i class="fas fa-users"></i> Daftar Tamu
        </a>
        <a class="nav-item" id="nav-ucapan" onclick="showPage('ucapan',this)">
            <i class="fas fa-comment-dots"></i> Ucapan & Doa
        </a>
        <a class="nav-item" id="nav-statistik" onclick="showPage('statistik',this)">
            <i class="fas fa-chart-pie"></i> Statistik RSVP
        </a>
        <a class="nav-item" id="nav-galeri" onclick="showPage('galeri',this)">
            <i class="fas fa-images"></i> Galeri Foto
        </a>
        <div class="nav-group-label">Pengaturan</div>
        <a class="nav-item" id="nav-pengaturan" onclick="showPage('pengaturan',this)">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
        <a class="nav-item" href="{{ route('home') }}" target="_blank">
            <i class="fas fa-external-link-alt"></i> Lihat Undangan
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>
</aside>

<div class="main-wrapper">
    <!-- TOPBAR -->
    <header class="topbar">
        <button class="hamburger" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <span class="topbar-title">Admin Panel <span style="color:#bbb">·</span> <span id="topbar-page-name">Overview</span></span>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari tamu..." id="topbarSearch" oninput="globalSearch(this.value)">
        </div>
        <div class="topbar-actions">
            <div class="admin-avatar">{{ $initials }}</div>
        </div>
    </header>

    <!-- ==================== PAGE: OVERVIEW ==================== -->
    <div class="page-view active" id="page-overview">
        <div class="page-content">
            <div class="section-header">
                <div>
                    <h2>Overview</h2>
                    <p>Pantau semua data tamu undangan secara real-time</p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button class="export-btn" onclick="exportCSV()"><i class="fas fa-download"></i> Export CSV</button>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card green"><div class="stat-icon green"><i class="fas fa-users"></i></div><div class="stat-info"><div class="stat-label">Total Respons</div><div class="stat-value" id="sc-total">–</div><div class="stat-sub">Total RSVP masuk</div></div></div>
                <div class="stat-card green"><div class="stat-icon green"><i class="fas fa-check-circle"></i></div><div class="stat-info"><div class="stat-label">Hadir</div><div class="stat-value" id="sc-hadir">–</div><div class="stat-sub" id="sc-hadir-sub">tamu konfirmasi hadir</div></div></div>
                <div class="stat-card red"><div class="stat-icon red"><i class="fas fa-times-circle"></i></div><div class="stat-info"><div class="stat-label">Tidak Hadir</div><div class="stat-value" id="sc-tidak">–</div><div class="stat-sub">tidak bisa hadir</div></div></div>
                <div class="stat-card gold"><div class="stat-icon gold"><i class="fas fa-clock"></i></div><div class="stat-info"><div class="stat-label">Menunggu</div><div class="stat-value" id="sc-pending">–</div><div class="stat-sub">belum konfirmasi</div></div></div>
            </div>

            <!-- Two-col: Table + Donut -->
            <div class="two-col-grid">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Tamu Terbaru</span>
                        <a class="card-action" onclick="showPage('tamu',document.getElementById('nav-tamu'))">Lihat Semua →</a>
                    </div>
                    <div style="padding:14px 22px 8px;">
                        <div style="display:flex;align-items:center;gap:8px;background:var(--olivine-pale);border:1px solid #ddd;border-radius:8px;padding:6px 12px;">
                            <i class="fas fa-search" style="color:#999;font-size:0.8rem;"></i>
                            <input type="text" placeholder="Cari nama..." oninput="filterTable(this.value)" id="tableSearch"
                                style="border:none;background:none;outline:none;font-family:'Poppins',sans-serif;font-size:0.8rem;width:100%;">
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table id="guestTable">
                            <thead><tr><th>#</th><th>Nama Tamu</th><th>Link Khusus</th><th>Kehadiran</th><th>Jumlah</th><th>Ucapan</th><th>Tanggal</th></tr></thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">RSVP Overview</span></div>
                    <div class="rsvp-donut-wrap">
                        <div class="donut-chart">
                            <svg width="160" height="160" viewBox="0 0 160 160">
                                <circle cx="80" cy="80" r="60" fill="none" stroke="#f0ede8" stroke-width="22"/>
                                <circle id="donut-hadir"  cx="80" cy="80" r="60" fill="none" stroke="#6f816a" stroke-width="22" stroke-dasharray="0 377" stroke-linecap="round"/>
                                <circle id="donut-tidak"  cx="80" cy="80" r="60" fill="none" stroke="#572932" stroke-width="22" stroke-dasharray="0 377" stroke-dashoffset="0" stroke-linecap="round"/>
                                <circle id="donut-pending" cx="80" cy="80" r="60" fill="none" stroke="#b8860b" stroke-width="22" stroke-dasharray="0 377" stroke-dashoffset="0" stroke-linecap="round"/>
                            </svg>
                            <div class="donut-center">
                                <span class="donut-total" id="donut-total">–</span>
                                <span class="donut-label">tamu</span>
                            </div>
                        </div>
                        <div class="legend-list" id="legendList"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Ucapan -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Ucapan Terbaru</span>
                    <a class="card-action" onclick="showPage('ucapan',document.getElementById('nav-ucapan'))">Lihat Semua →</a>
                </div>
                <div class="messages-list" id="recentUcapan"></div>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE: TAMU ==================== -->
    <div class="page-view" id="page-tamu">
        <div class="page-content">
            <div class="section-header">
                <div><h2>Daftar Tamu</h2><p>Seluruh daftar tamu yang telah mengisi konfirmasi kehadiran</p></div>
                <button class="export-btn" onclick="exportCSV()"><i class="fas fa-download"></i> Export CSV</button>
            </div>

            <!-- Form Tambah Tamu -->
            <div class="card" style="margin-bottom:24px;">
                <div class="card-header" style="background:#f9f7f4;">
                    <span class="card-title">Tambah Tamu Baru</span>
                </div>
                <div style="padding:20px;display:flex;gap:10px;align-items:flex-end;">
                    <div style="flex:1;">
                        <label style="font-size:0.8rem;color:#999;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;display:block;margin-bottom:6px;">Nama Tamu</label>
                        <input type="text" id="newGuestName" placeholder="Masukkan nama tamu..." 
                            style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:'Poppins',sans-serif;font-size:0.9rem;">
                    </div>
                    <button onclick="addNewGuest()" style="padding:10px 24px;background:#6f816a;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;transition:background 0.2s;" onmouseover="this.style.background='#5a6954'" onmouseout="this.style.background='#6f816a'">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="filter-row">
                    <button class="filter-btn active" onclick="setFilter('semua',this)">Semua</button>
                    <button class="filter-btn" onclick="setFilter('hadir',this)">Hadir</button>
                    <button class="filter-btn" onclick="setFilter('tidak',this)">Tidak Hadir</button>
                    <button class="filter-btn" onclick="setFilter('pending',this)">Pending</button>
                    <div class="filter-search" style="margin-left:auto;">
                        <i class="fas fa-search" style="color:#999;font-size:0.8rem;"></i>
                        <input type="text" placeholder="Cari nama..." oninput="filterTable(this.value)" id="tableSearch2">
                    </div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>#</th><th>Nama Tamu</th><th>Link Khusus</th><th>Kehadiran</th><th>Jumlah</th><th>Ucapan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                        <tbody id="tableBodyFull"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE: UCAPAN ==================== -->
    <div class="page-view" id="page-ucapan">
        <div class="page-content">
            <div class="section-header">
                <div><h2>Ucapan &amp; Doa</h2><p>Kumpulan pesan indah dari para tamu undangan</p></div>
            </div>
            <div class="ucapan-grid" id="ucapanGrid"></div>
        </div>
    </div>

    <!-- ==================== PAGE: STATISTIK ==================== -->
    <div class="page-view" id="page-statistik">
        <div class="page-content">
            <div class="section-header">
                <div><h2>Statistik RSVP</h2><p>Analisis data kehadiran tamu undangan</p></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="stats-bottom">
                <div class="card">
                    <div class="card-header"><span class="card-title">Persentase Kehadiran</span></div>
                    <div class="progress-list" id="progressList"></div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="card-title">Ringkasan</span></div>
                    <div style="padding:22px;" id="summaryBox"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE: PENGATURAN ==================== -->
    <div class="page-view" id="page-pengaturan">
        <div class="page-content">
            <div class="section-header">
                <div><h2>Pengaturan</h2><p>Kelola informasi acara dan akun admin — perubahan langsung tampil di undangan</p></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:960px;">
                <!-- Informasi Acara -->
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Acara</span></div>
                    <form id="formSettings" style="padding:20px 22px 8px;">
                        <div class="form-field">
                            <label>Nama Mempelai Pria</label>
                            <input class="settings-input" name="nama_pria" id="s_nama_pria" placeholder="Albert" value="">
                        </div>
                        <div class="form-field">
                            <label>Nama Mempelai Wanita</label>
                            <input class="settings-input" name="nama_wanita" id="s_nama_wanita" placeholder="Selviana" value="">
                        </div>
                        <div class="form-field">
                            <label>Tanggal Pernikahan</label>
                            <input class="settings-input" name="tanggal_acara" id="s_tanggal_acara" type="date" value="">
                        </div>
                        <div class="form-field">
                            <label>Waktu Acara (HH:MM)</label>
                            <input class="settings-input" name="waktu_acara" id="s_waktu_acara" type="time" value="">
                        </div>
                        <div class="form-field">
                            <label>Lokasi Acara</label>
                            <input class="settings-input" name="lokasi_acara" id="s_lokasi_acara" placeholder="Alamat lengkap" value="">
                        </div>
                        <div class="form-field">
                            <label>Putra Dari</label>
                            <input class="settings-input" name="putra_dari" id="s_putra_dari" value="">
                        </div>
                        <div class="form-field">
                            <label>Putri Dari</label>
                            <input class="settings-input" name="putri_dari" id="s_putri_dari" value="">
                        </div>
                        <div class="form-field">
                            <label>Dresscode</label>
                            <input class="settings-input" name="dresscode" id="s_dresscode" value="">
                        </div>
                        <div class="form-field">
                            <label>Catatan Tambahan</label>
                            <input class="settings-input" name="catatan_tambahan" id="s_catatan_tambahan" value="">
                        </div>
                    </form>
                </div>
                <!-- Bank & Admin -->
                <div class="card">
                    <div class="card-header"><span class="card-title">Kado Digital &amp; Akun Admin</span></div>
                    <div style="padding:20px 22px 8px;">
                        <div class="form-field">
                            <label>Nama Bank</label>
                            <input class="settings-input" name="bank_name" id="s_bank_name" value="">
                        </div>
                        <div class="form-field">
                            <label>Nomor Rekening</label>
                            <input class="settings-input" name="nomor_rekening" id="s_nomor_rekening" value="">
                        </div>
                        <div style="border-top:1px solid #eee;margin:20px 0;"></div>
                        <div class="form-field">
                            <label>Username Admin</label>
                            <input class="settings-input" name="admin_username" id="s_admin_username" value="">
                        </div>
                        <div class="form-field">
                            <label>Password Admin</label>
                            <input class="settings-input" type="password" name="admin_password" id="s_admin_password" placeholder="Kosongkan jika tidak ingin ubah">
                        </div>
                    </div>
                    <div style="padding:0 22px 22px;">
                        <button class="export-btn" style="background:var(--burgundy);width:100%;" onclick="saveSettings()">
                            <i class="fas fa-save"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                </div>
                
                <!-- Pengaturan Foto -->
                <div class="card" style="grid-column: 1 / -1;">
                    <div class="card-header"><span class="card-title">Pengaturan Foto Web</span></div>
                    <div style="padding:20px 22px;display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:20px;">
                        <div>
                            <label style="font-size:0.75rem;font-weight:600;color:#888;text-transform:uppercase;">Foto Utama (Hero)</label>
                            <input type="file" id="foto_hero" accept="image/*" class="settings-input" style="margin-top:5px;">
                            <button onclick="uploadPhoto('foto_hero')" class="export-btn" style="margin-top:10px;width:100%;"><i class="fas fa-upload"></i> Unggah</button>
                        </div>
                        <div>
                            <label style="font-size:0.75rem;font-weight:600;color:#888;text-transform:uppercase;">Foto Mempelai Pria</label>
                            <input type="file" id="foto_pria" accept="image/*" class="settings-input" style="margin-top:5px;">
                            <button onclick="uploadPhoto('foto_pria')" class="export-btn" style="margin-top:10px;width:100%;"><i class="fas fa-upload"></i> Unggah</button>
                        </div>
                        <div>
                            <label style="font-size:0.75rem;font-weight:600;color:#888;text-transform:uppercase;">Foto Mempelai Wanita</label>
                            <input type="file" id="foto_wanita" accept="image/*" class="settings-input" style="margin-top:5px;">
                            <button onclick="uploadPhoto('foto_wanita')" class="export-btn" style="margin-top:10px;width:100%;"><i class="fas fa-upload"></i> Unggah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== PAGE: GALERI ==================== -->
    <div class="page-view" id="page-galeri">
        <div class="page-content">
            <div class="section-header">
                <div><h2>Galeri Foto</h2><p>Kelola foto-foto pre-wedding untuk ditampilkan di website</p></div>
            </div>
            <div class="card" style="margin-bottom:24px;">
                <div class="card-header" style="background:#f9f7f4;"><span class="card-title">Unggah Foto Baru</span></div>
                <div style="padding:20px;display:flex;gap:10px;align-items:flex-end;">
                    <div style="flex:1;">
                        <input type="file" id="newGalleryPhoto" accept="image/*" class="settings-input">
                    </div>
                    <button onclick="uploadGalleryPhoto()" class="export-btn"><i class="fas fa-upload"></i> Unggah</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><span class="card-title">Daftar Foto Galeri</span></div>
                <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill, minmax(150px, 1fr));gap:15px;" id="galleryGrid">
                    <!-- Gallery Items -->
                </div>
            </div>
        </div>
    </div>
</div><!-- end main-wrapper -->

<div id="toast"></div>

<script>
// ============ STATE ============
let guests = [];
let currentFilter = 'semua';
let searchQuery   = '';

// ============ INIT ============
document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadGuests();
    loadSettings();
    loadGallery();
});

// ============ API HELPERS ============
async function apiGet(action, extra = '') {
    const res  = await fetch(`{{ url('dashboard/api') }}/${action}${extra}`);
    return await res.json();
}
async function apiPost(action, body) {
    const res  = await fetch(`{{ url('dashboard/api') }}/${action}`, { 
        method:'POST', 
        body,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    });
    return await res.json();
}

// ============ LOAD STATS ============
async function loadStats() {
    const d = await apiGet('stats');
    if (!d.success) return;
    document.getElementById('sc-total').textContent   = d.total;
    document.getElementById('sc-hadir').textContent   = d.hadir;
    document.getElementById('sc-hadir-sub').textContent = d.jumlah_total + ' orang konfirmasi hadir';
    document.getElementById('sc-tidak').textContent   = d.tidak;
    document.getElementById('sc-pending').textContent  = d.pending;
    document.getElementById('donut-total').textContent = d.total;
    updateDonut(d.hadir, d.tidak, d.pending, d.total);
    updateLegend(d.hadir, d.tidak, d.pending);
    updateProgress(d.hadir, d.tidak, d.pending, d.total);
    updateSummary(d);
}

function updateDonut(hadir, tidak, pending, total) {
    const circ = 2 * Math.PI * 60; // ≈ 376.99
    if (total === 0) return;
    const dH = (hadir  / total) * circ;
    const dT = (tidak  / total) * circ;
    const dP = (pending/ total) * circ;
    const eH = document.getElementById('donut-hadir');
    const eT = document.getElementById('donut-tidak');
    const eP = document.getElementById('donut-pending');
    eH.setAttribute('stroke-dasharray', `${dH} ${circ - dH}`);
    eH.setAttribute('stroke-dashoffset', '0');
    eT.setAttribute('stroke-dasharray', `${dT} ${circ - dT}`);
    eT.setAttribute('stroke-dashoffset', -dH);
    eP.setAttribute('stroke-dasharray', `${dP} ${circ - dP}`);
    eP.setAttribute('stroke-dashoffset', -(dH + dT));
}

function updateLegend(hadir, tidak, pending) {
    document.getElementById('legendList').innerHTML = `
        <div class="legend-item"><div class="legend-left"><span class="legend-dot" style="background:#6f816a"></span><span>Hadir</span></div><span class="legend-count">${hadir}</span></div>
        <div class="legend-item"><div class="legend-left"><span class="legend-dot" style="background:#572932"></span><span>Tidak Hadir</span></div><span class="legend-count">${tidak}</span></div>
        <div class="legend-item"><div class="legend-left"><span class="legend-dot" style="background:#b8860b"></span><span>Pending</span></div><span class="legend-count">${pending}</span></div>
    `;
}

function updateProgress(hadir, tidak, pending, total) {
    if (!document.getElementById('progressList')) return;
    const pct = v => total > 0 ? Math.round(v/total*100) : 0;
    document.getElementById('progressList').innerHTML = `
        <div class="progress-item"><div class="progress-label"><span>Hadir</span><span>${hadir} tamu (${pct(hadir)}%)</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:${pct(hadir)}%;background:#6f816a;"></div></div></div>
        <div class="progress-item"><div class="progress-label"><span>Tidak Hadir</span><span>${tidak} tamu (${pct(tidak)}%)</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:${pct(tidak)}%;background:#572932;"></div></div></div>
        <div class="progress-item"><div class="progress-label"><span>Pending</span><span>${pending} tamu (${pct(pending)}%)</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:${pct(pending)}%;background:#b8860b;"></div></div></div>
    `;
}

function updateSummary(d) {
    const el = document.getElementById('summaryBox');
    if (!el) return;
    el.innerHTML = `
        <div style="font-size:0.9rem;color:#555;line-height:2;">
            <p>📋 Total respons: <strong>${d.total}</strong></p>
            <p>✅ Konfirmasi hadir: <strong>${d.hadir} orang</strong></p>
            <p>👥 Estimasi tamu hadir: <strong>${d.jumlah_total} orang</strong></p>
            <p>❌ Tidak hadir: <strong>${d.tidak} orang</strong></p>
            <p>⏳ Menunggu konfirmasi: <strong>${d.pending} orang</strong></p>
        </div>
    `;
}

// ============ LOAD GUESTS ============
async function loadGuests() {
    const d = await apiGet('list');
    if (!d.success) return;
    guests = d.data;
    renderTable(getFiltered(), 'tableBody');
    renderTable(getFiltered(), 'tableBodyFull');
    renderUcapan();
    renderRecentUcapan();
}

function getFiltered() {
    return guests.filter(g => {
        const matchFilter = currentFilter === 'semua' || g.kehadiran === currentFilter;
        const matchSearch = searchQuery === '' || g.nama.toLowerCase().includes(searchQuery.toLowerCase());
        return matchFilter && matchSearch;
    });
}

// ============ RENDER TABLE ============
function renderTable(list, bodyId) {
    const showDelete = bodyId === 'tableBodyFull';
    const tbody = document.getElementById(bodyId);
    if (!tbody) return;
    if (list.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:30px;color:#bbb;font-style:italic;">Belum ada data tamu</td></tr>`;
        return;
    }
    tbody.innerHTML = list.map((g, i) => {
        const initials = g.nama.split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase();
        const color    = colorForId(g.id);
        const inviteLink = g.slug ? `{{ url('/undangan') }}?guest=${encodeURIComponent(g.slug)}` : '';
        const linkDisplay = inviteLink ? `<span class="guest-link">${inviteLink.substring(0,50)}...</span>` : '–';
        const copyBtn = inviteLink ? `<button class="copy-btn" onclick="copyGuestLink('${inviteLink.replace(/'/g, "\\'")}', this)" title="Salin link"><i class="fas fa-copy"></i></button>` : '';
        const badge    = g.kehadiran === 'hadir'   ? `<span class="badge badge-green"><i class="fas fa-check-circle"></i> Hadir</span>` :
                         g.kehadiran === 'tidak'   ? `<span class="badge badge-red"><i class="fas fa-times-circle"></i> Tidak</span>` :
                                                     `<span class="badge badge-gold"><i class="fas fa-clock"></i> Pending</span>`;
        const ucapanShort = g.ucapan && g.ucapan.length > 45 ? g.ucapan.substring(0,45)+'...' : (g.ucapan || '<span style="color:#ccc;font-style:italic;">–</span>');
        const tgl = g.created_at ? g.created_at.substring(0,10) : '–';
        const actionBtns = showDelete ? `<td style="display:flex;gap:6px;"><button class="action-btn edit-btn" onclick="openEditGuest(${g.id})" title="Edit"><i class="fas fa-edit"></i></button><button class="del-btn" onclick="deleteGuest(${g.id})" title="Hapus"><i class="fas fa-trash"></i></button></td>` : '';
        return `<tr>
            <td style="color:#bbb;font-size:0.8rem;">${i+1}</td>
            <td><div class="guest-cell"><div class="guest-initials" style="background:${color};">${initials}</div><span class="guest-name">${escHtml(g.nama)}</span></div></td>
            <td style="font-size:0.78rem;display:flex;align-items:center;gap:8px;">${linkDisplay} ${copyBtn}</td>
            <td>${badge}</td>
            <td>${g.jumlah_tamu || 1}</td>
            <td style="font-size:0.78rem;font-style:italic;color:#888;">${ucapanShort}</td>
            <td style="font-size:0.78rem;color:#bbb;">${tgl}</td>
            ${actionBtns}
        </tr>`;
    }).join('');
}

// ============ RENDER UCAPAN ============
function renderUcapan() {
    const grid = document.getElementById('ucapanGrid');
    if (!grid) return;
    const withMsg = guests.filter(g => g.ucapan && g.ucapan.trim());
    if (withMsg.length === 0) {
        grid.innerHTML = '<p style="color:#bbb;font-style:italic;">Belum ada ucapan.</p>';
        return;
    }
    grid.innerHTML = withMsg.map(g => {
        const color = colorForId(g.id);
        const initials = g.nama.split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase();
        const tgl = g.created_at ? g.created_at.substring(0,10) : '';
        return `<div class="ucapan-card">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div class="guest-initials" style="background:${color};">${initials}</div>
                <div><div style="font-size:0.9rem;font-weight:600;">${escHtml(g.nama)}</div>
                <div style="font-size:0.72rem;color:#bbb;">${tgl}</div></div>
            </div>
            <p style="font-style:italic;color:#666;font-size:0.9rem;line-height:1.6;font-family:'Cormorant Garamond',serif;">"${escHtml(g.ucapan)}"</p>
        </div>`;
    }).join('');
}

function renderRecentUcapan() {
    const el = document.getElementById('recentUcapan');
    if (!el) return;
    const withMsg = guests.filter(g => g.ucapan && g.ucapan.trim()).slice(0,5);
    if (withMsg.length === 0) { el.innerHTML = '<p style="padding:20px;color:#bbb;font-style:italic;">Belum ada ucapan.</p>'; return; }
    el.innerHTML = withMsg.map(g => {
        const tgl = g.created_at ? g.created_at.substring(0,10) : '';
        const badge = g.kehadiran === 'hadir' ? `<span class="badge badge-green" style="font-size:0.65rem;">Hadir</span>` :
                      g.kehadiran === 'tidak' ? `<span class="badge badge-red" style="font-size:0.65rem;">Tidak</span>` :
                                                `<span class="badge badge-gold" style="font-size:0.65rem;">Pending</span>`;
        return `<div class="message-item">
            <div class="message-top"><span class="message-author">${escHtml(g.nama)}</span><span class="message-time">${tgl}</span></div>
            <div class="message-attendance">${badge}</div>
            <div class="message-text">"${escHtml(g.ucapan)}"</div>
        </div>`;
    }).join('');
}

// ============ EDIT MODAL ============
function openEditGuest(id) {
    const g = guests.find(x => x.id === id);
    if (!g) return;
    document.getElementById('editGuestId').value = g.id;
    document.getElementById('editGuestName').value = g.nama;
    document.getElementById('editGuestStatus').value = g.kehadiran;
    document.getElementById('editGuestCount').value = g.jumlah_tamu || 1;
    document.getElementById('editGuestMessage').value = g.ucapan || '';
    document.getElementById('editGuestModal').style.display = 'flex';
}

function closeEditGuest() {
    document.getElementById('editGuestModal').style.display = 'none';
}

async function saveEditGuest() {
    const id = document.getElementById('editGuestId').value;
    const nama = document.getElementById('editGuestName').value.trim();
    if (nama === '') {
        showToast('❌ Nama tidak boleh kosong.');
        return;
    }
    const fd = new FormData();
    fd.append('id', id);
    fd.append('nama', nama);
    fd.append('kehadiran', document.getElementById('editGuestStatus').value);
    fd.append('jumlah_tamu', document.getElementById('editGuestCount').value);
    fd.append('ucapan', document.getElementById('editGuestMessage').value);
    
    const d = await apiPost('edit_guest', fd);
    if (d.success) {
        showToast('✅ Data tamu berhasil diperbarui.');
        closeEditGuest();
        loadGuests();
        loadStats();
    } else {
        showToast('❌ ' + (d.message || 'Gagal memperbarui data.'));
    }
}

window.addEventListener('click', (e) => {
    const modal = document.getElementById('editGuestModal');
    if (e.target === modal) modal.style.display = 'none';
});

// ============ ADD NEW GUEST ============
async function addNewGuest() {
    const nameInput = document.getElementById('newGuestName');
    const nama = nameInput.value.trim();
    if (nama === '') {
        showToast('❌ Masukkan nama tamu terlebih dahulu.');
        return;
    }
    const fd = new FormData();
    fd.append('nama', nama);
    const d = await apiPost('add_guest', fd);
    if (d.success) {
        showToast('✅ Tamu berhasil ditambahkan! Link siap dibagikan.');
        nameInput.value = '';
        loadGuests();
        loadStats();
    } else {
        showToast('❌ ' + (d.message || 'Gagal menambahkan tamu.'));
    }
}

// ============ COPY LINK ============
function copyGuestLink(link, btn) {
    navigator.clipboard.writeText(link).then(() => {
        const origText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin';
        btn.style.background = '#e6f4ea';
        btn.style.color = '#2d6a4f';
        btn.style.borderColor = '#2d6a4f';
        setTimeout(() => {
            btn.innerHTML = origText;
            btn.style.background = '';
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 1500);
        showToast('📋 Link berhasil disalin ke clipboard!');
    }).catch(() => {
        showToast('❌ Gagal menyalin link.');
    });
}

// ============ DELETE GUEST ============
async function deleteGuest(id) {
    if (!confirm('Hapus tamu ini dari daftar?')) return;
    const fd = new FormData();
    fd.append('id', id);
    const d = await apiPost('delete', fd);
    if (d.success) {
        showToast('🗑️ Tamu berhasil dihapus.');
        loadGuests();
        loadStats();
    }
}

// ============ FILTER & SEARCH ============
function setFilter(f, btn) {
    currentFilter = f;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderTable(getFiltered(), 'tableBody');
    renderTable(getFiltered(), 'tableBodyFull');
}

function filterTable(q) {
    searchQuery = q;
    renderTable(getFiltered(), 'tableBody');
    renderTable(getFiltered(), 'tableBodyFull');
}

function globalSearch(q) {
    searchQuery = q;
    if (q.length > 0) {
        showPage('tamu', document.getElementById('nav-tamu'));
    }
    renderTable(getFiltered(), 'tableBody');
    renderTable(getFiltered(), 'tableBodyFull');
}

// ============ EXPORT CSV ============
function exportCSV() {
    const rows = [['No','Nama','Kehadiran','Jumlah','Ucapan','Tanggal']];
    getFiltered().forEach((g,i) => {
        rows.push([i+1, g.nama, g.kehadiran, g.jumlah_tamu, g.ucapan||'', g.created_at||'']);
    });
    const csv  = rows.map(r => r.map(v => `"${String(v).replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob(['\uFEFF'+csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = 'buku-tamu-albert-selviana.csv';
    a.click();
    URL.revokeObjectURL(url);
}

// ============ SETTINGS ============
async function loadSettings() {
    const d = await apiGet('get_settings');
    if (!d.success) return;
    const s = d.data;
    const fields = ['nama_pria','nama_wanita','tanggal_acara','waktu_acara','lokasi_acara',
                    'bank_name','nomor_rekening','admin_username','putra_dari','putri_dari','dresscode','catatan_tambahan'];
    fields.forEach(k => {
        const el = document.getElementById('s_'+k);
        if (el && s[k]) el.value = s[k];
    });
}

async function saveSettings() {
    const form = document.getElementById('formSettings');
    const fd   = new FormData(form);
    // Also grab from the second card
    ['bank_name','nomor_rekening','admin_username','admin_password'].forEach(k => {
        const el = document.getElementById('s_'+k);
        if (el && el.value) fd.set(k, el.value);
    });
    const d = await apiPost('save_settings', fd);
    if (d.success) {
        showToast('✅ Pengaturan berhasil disimpan!');
        // Update sidebar names
        const pria   = document.getElementById('s_nama_pria')?.value   || '';
        const wanita = document.getElementById('s_nama_wanita')?.value || '';
        const sbC    = document.getElementById('sb-couple');
        if (sbC && pria && wanita) sbC.textContent = pria + ' & ' + wanita;
    } else {
        showToast('❌ Gagal menyimpan pengaturan.');
    }
}

// ============ PAGE NAVIGATION ============
function showPage(pageId, navEl) {
    document.querySelectorAll('.page-view').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    const page = document.getElementById('page-'+pageId);
    if (page) page.classList.add('active');
    if (navEl) navEl.classList.add('active');
    const names = { overview:'Overview', tamu:'Daftar Tamu', ucapan:'Ucapan & Doa', statistik:'Statistik RSVP', pengaturan:'Pengaturan', galeri:'Galeri Foto' };
    const titleEl = document.getElementById('topbar-page-name');
    if (titleEl) titleEl.textContent = names[pageId] || pageId;
    if (pageId === 'tamu')     { renderTable(getFiltered(), 'tableBodyFull'); }
    if (pageId === 'ucapan')   { renderUcapan(); }
    if (pageId === 'statistik') { loadStats(); }
    if (pageId === 'pengaturan') { loadSettings(); }
    if (pageId === 'galeri') { loadGallery(); }
    closeSidebar();
}

// ============ SIDEBAR ============
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
}

// ============ TOAST ============
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

// ============ GALERI & FOTO ============
async function uploadPhoto(key) {
    const input = document.getElementById(key);
    if (!input.files || input.files.length === 0) {
        return showToast('❌ Pilih foto terlebih dahulu.');
    }
    const fd = new FormData();
    fd.append('key', key);
    fd.append('photo', input.files[0]);

    const btn = event.currentTarget;
    const oldHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunggah...';

    const d = await apiPost('upload_photo', fd);
    btn.innerHTML = oldHtml;

    if (d.success) {
        showToast('✅ Foto berhasil diunggah.');
        input.value = '';
    } else {
        showToast('❌ ' + (d.message || 'Gagal mengunggah foto.'));
    }
}

async function uploadGalleryPhoto() {
    const input = document.getElementById('newGalleryPhoto');
    if (!input.files || input.files.length === 0) {
        return showToast('❌ Pilih foto terlebih dahulu.');
    }
    const fd = new FormData();
    fd.append('photo', input.files[0]);

    const btn = event.currentTarget;
    const oldHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunggah...';

    const d = await apiPost('upload_gallery', fd);
    btn.innerHTML = oldHtml;

    if (d.success) {
        showToast('✅ Foto galeri berhasil ditambahkan.');
        input.value = '';
        loadGallery();
    } else {
        showToast('❌ ' + (d.message || 'Gagal menambahkan foto.'));
    }
}

async function loadGallery() {
    const d = await apiGet('get_gallery');
    if (!d.success) return;
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;
    
    if (d.data.length === 0) {
        grid.innerHTML = '<p style="color:#bbb;font-style:italic;grid-column:1/-1;">Belum ada foto di galeri.</p>';
        return;
    }
    
    grid.innerHTML = d.data.map(item => `
        <div style="position:relative;border-radius:10px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.1);background:#eee;aspect-ratio:1/1;">
            <img src="{{ url('/') }}/${item.foto}" style="width:100%;height:100%;object-fit:cover;">
            <button onclick="deleteGalleryPhoto(${item.id})" style="position:absolute;top:8px;right:8px;background:rgba(255,0,0,0.7);color:#fff;border:none;border-radius:50%;width:30px;height:30px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" title="Hapus foto">
                <i class="fas fa-trash" style="font-size:0.8rem;"></i>
            </button>
        </div>
    `).join('');
}

async function deleteGalleryPhoto(id) {
    if (!confirm('Hapus foto ini dari galeri?')) return;
    const fd = new FormData();
    fd.append('id', id);
    const d = await apiPost('delete_gallery', fd);
    if (d.success) {
        showToast('🗑️ Foto galeri dihapus.');
        loadGallery();
    } else {
        showToast('❌ Gagal menghapus foto.');
    }
}

// ============ HELPERS ============
const PALETTE = ['#6f816a','#572932','#4a7c59','#7a5e52','#b8860b','#3d5a44','#a8bba4','#4a3728'];
function colorForId(id) { return PALETTE[(id-1) % PALETTE.length]; }

function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ============ AUTO-REFRESH setiap 30 detik ============
setInterval(() => {
    loadGuests();
    loadStats();
}, 30000);
</script>

<!-- EDIT GUEST MODAL -->
<div id="editGuestModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:28px;max-width:500px;width:90%;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:1.2rem;color:#572932;font-family:'Cinzel',serif;margin:0;">Edit Data Tamu</h3>
            <button onclick="closeEditGuest()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
        </div>
        <div style="display:contents;">
            <input type="hidden" id="editGuestId">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.8rem;color:#999;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:6px;">Nama Tamu</label>
                <input type="text" id="editGuestName" placeholder="Nama tamu" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:'Poppins',sans-serif;font-size:0.9rem;box-sizing:border-box;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:0.8rem;color:#999;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:6px;">Status</label>
                    <select id="editGuestStatus" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:'Poppins',sans-serif;font-size:0.9rem;box-sizing:border-box;">
                        <option value="pending">Pending</option>
                        <option value="hadir">Hadir</option>
                        <option value="tidak">Tidak Hadir</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;color:#999;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:6px;">Jumlah Orang</label>
                    <input type="number" id="editGuestCount" min="1" max="20" placeholder="Jumlah" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:'Poppins',sans-serif;font-size:0.9rem;box-sizing:border-box;">
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.8rem;color:#999;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;margin-bottom:6px;">Ucapan/Pesan</label>
                <textarea id="editGuestMessage" placeholder="Ucapan dari tamu" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-family:'Poppins',sans-serif;font-size:0.9rem;box-sizing:border-box;resize:vertical;min-height:80px;"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeEditGuest()" style="padding:10px 20px;background:#f0ede8;color:#666;border:1px solid #ddd;border-radius:6px;cursor:pointer;font-weight:600;transition:all 0.2s;" onmouseover="this.style.background='#e6dfd5'" onmouseout="this.style.background='#f0ede8'">Batal</button>
                <button type="button" onclick="saveEditGuest()" style="padding:10px 20px;background:#6f816a;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;transition:all 0.2s;" onmouseover="this.style.background='#5a6954'" onmouseout="this.style.background='#6f816a'">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
