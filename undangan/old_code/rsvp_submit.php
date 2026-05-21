<?php
// rsvp_submit.php — Endpoint menerima data RSVP dari tamu
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Ambil & bersihkan input
$nama       = trim(strip_tags($_POST['name']     ?? ''));
$kehadiran  = trim($_POST['attendance'] ?? '');
$jumlah     = (int)($_POST['guests']    ?? 1);
$ucapan     = trim(strip_tags($_POST['notes']    ?? ''));

// Validasi
if ($nama === '') {
    echo json_encode(['success' => false, 'message' => 'Nama tidak boleh kosong.']);
    exit;
}
$allowedKehadiran = ['hadir', 'tidak', 'pending', 'ya'];
if (!in_array($kehadiran, $allowedKehadiran, true)) {
    echo json_encode(['success' => false, 'message' => 'Pilihan kehadiran tidak valid.']);
    exit;
}

// Normalisasi nilai kehadiran (form pakai "ya" / "tidak")
if ($kehadiran === 'ya') $kehadiran = 'hadir';
if ($jumlah < 1) $jumlah = 1;
if ($jumlah > 20) $jumlah = 20;

try {
    $db   = getDB();
    $slug = generateGuestSlug($nama);
    $check = $db->prepare("SELECT COUNT(*) FROM rsvp WHERE slug = ?");
    do {
        $check->execute([$slug]);
    } while ((int)$check->fetchColumn() > 0 && $slug = generateGuestSlug($nama));

    $stmt = $db->prepare(
        "INSERT INTO rsvp (nama, slug, kehadiran, jumlah_tamu, ucapan) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nama, $slug, $kehadiran, $jumlah, $ucapan]);

    echo json_encode([
        'success' => true,
        'message' => 'Konfirmasi berhasil dikirim! Terima kasih, ' . htmlspecialchars($nama) . '.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
}
?>
