<?php
// ============================================================
// config.php — Konfigurasi Undangan & Koneksi Database Laragon
// ============================================================

// ---- Database (Laragon default) ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'undangan_pernikahan');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---- Informasi Acara (bisa diedit via dashboard) ----
define('NAMA_MEMPELAI_PRIA',  'Albert');
define('NAMA_MEMPELAI_WANITA','Selviana');
define('TANGGAL_ACARA',  '2032-08-02');
define('WAKTU_ACARA',    '14:00');
define('LOKASI_ACARA',   '123 Anywhere St., Any City, ST 12345');

// ---- Info Bank ----
define('BANK_NAME',      'BCA');
define('NOMOR_REKENING', '123456789');
define('ATAS_NAMA', NAMA_MEMPELAI_PRIA . ' / ' . NAMA_MEMPELAI_WANITA);

// ---- Website ----
define('SITE_TITLE', 'Undangan Pernikahan ' . NAMA_MEMPELAI_PRIA . ' & ' . NAMA_MEMPELAI_WANITA);
define('DEFAULT_GUEST_NAME', 'Tamu Undangan');

// ---- Admin ----
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

// ============================================================
// Koneksi Database (PDO)
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

function slugifyGuest(string $name): string {
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug === '' ? 'tamu-undangan' : substr($slug, 0, 64);
}

function generateGuestSlug(string $name): string {
    $base = slugifyGuest($name);
    $suffix = bin2hex(random_bytes(4));
    return substr($base . '-' . $suffix, 0, 128);
}

// ============================================================
// Inisialisasi Tabel (jalankan sekali saat pertama kali akses)
// ============================================================
function initDatabase(): void {
    $db = getDB();

    // Tabel RSVP / tamu
    $db->exec("CREATE TABLE IF NOT EXISTS rsvp (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        nama        VARCHAR(150)  NOT NULL,
        slug        VARCHAR(128)  NOT NULL UNIQUE,
        kehadiran   ENUM('hadir','tidak','pending') NOT NULL DEFAULT 'pending',
        jumlah_tamu TINYINT UNSIGNED NOT NULL DEFAULT 1,
        ucapan      TEXT,
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Jika kolom slug belum tersedia pada instalasi lama, tambahkan dan isi nilai unik
    $col = $db->query("SHOW COLUMNS FROM rsvp LIKE 'slug'")->fetch();
    if (!$col) {
        $db->exec("ALTER TABLE rsvp ADD COLUMN slug VARCHAR(128) NOT NULL UNIQUE AFTER nama");
    }
    $stmtSel = $db->query("SELECT id, nama FROM rsvp WHERE COALESCE(slug,'') = ''");
    $stmtUpd = $db->prepare("UPDATE rsvp SET slug = ? WHERE id = ?");
    while ($row = $stmtSel->fetch()) {
        $slug = generateGuestSlug((string)$row['nama']);
        while ($db->query("SELECT 1 FROM rsvp WHERE slug = " . $db->quote($slug))->fetch()) {
            $slug = generateGuestSlug((string)$row['nama']);
        }
        $stmtUpd->execute([$slug, (int)$row['id']]);
    }

    // Tabel pengaturan undangan (key-value)
    $db->exec("CREATE TABLE IF NOT EXISTS pengaturan (
        `key`   VARCHAR(100) PRIMARY KEY,
        `value` TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Isi nilai default jika tabel pengaturan kosong
    $count = $db->query("SELECT COUNT(*) FROM pengaturan")->fetchColumn();
    if ((int)$count === 0) {
        $defaults = [
            'nama_pria'        => NAMA_MEMPELAI_PRIA,
            'nama_wanita'      => NAMA_MEMPELAI_WANITA,
            'tanggal_acara'    => TANGGAL_ACARA,
            'waktu_acara'      => WAKTU_ACARA,
            'lokasi_acara'     => LOKASI_ACARA,
            'bank_name'        => BANK_NAME,
            'nomor_rekening'   => NOMOR_REKENING,
            'admin_username'   => ADMIN_USERNAME,
            'admin_password'   => ADMIN_PASSWORD,
            'putra_dari'       => 'Mr. Darcy Esquire & Mrs. Elizabeth Bennet',
            'putri_dari'       => 'Mr. George William & Mrs. Sophia Charlotte',
            'dresscode'        => 'Pakaian Rapi & Sopan',
            'catatan_tambahan' => 'Mohon doa restu agar acara diberkati',
        ];
        $stmt = $db->prepare("INSERT INTO pengaturan (`key`, `value`) VALUES (?, ?)");
        foreach ($defaults as $k => $v) {
            $stmt->execute([$k, $v]);
        }
    }

    // Tabel admin session
    $db->exec("CREATE TABLE IF NOT EXISTS admin_sessions (
        token      VARCHAR(64) PRIMARY KEY,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

// Jalankan inisialisasi
initDatabase();

// ============================================================
// Baca Pengaturan dari DB
// ============================================================
function getSetting(string $key, string $default = ''): string {
    try {
        $db   = getDB();
        $stmt = $db->prepare("SELECT `value` FROM pengaturan WHERE `key` = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

// ============================================================
// Helper Functions
// ============================================================
function hitung_hari_tersisa(): int {
    $target   = strtotime(getSetting('tanggal_acara', TANGGAL_ACARA));
    $sekarang = strtotime(date('Y-m-d'));
    $selisih  = (int)ceil(($target - $sekarang) / 86400);
    return max(0, $selisih);
}

function format_tanggal_indonesia(string $tanggal): string {
    $bulan = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];
    $ts  = strtotime($tanggal);
    return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function salam_waktu(): string {
    $jam = (int)date('H');
    if ($jam < 12) return 'Selamat pagi';
    if ($jam < 15) return 'Selamat siang';
    if ($jam < 18) return 'Selamat sore';
    return 'Selamat malam';
}

// ============================================================
// Auth Helper (session-based, sederhana)
// ============================================================
function isAdminLoggedIn(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: ../dashboard/login.php');
        exit;
    }
}
?>
