<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

// Auth check
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$db     = getDB();
$action = $_GET['action'] ?? 'list';

switch ($action) {

    // ---- Daftar tamu / RSVP ----
    case 'list':
        $filter = $_GET['filter'] ?? 'semua';
        $sql    = "SELECT * FROM rsvp";
        if (in_array($filter, ['hadir','tidak','pending'], true)) {
            $sql .= " WHERE kehadiran = " . $db->quote($filter);
        }
        $sql .= " ORDER BY created_at DESC";
        $rows = $db->query($sql)->fetchAll();
        echo json_encode(['success' => true, 'data' => $rows, 'total' => count($rows)]);
        break;

    // ---- Statistik RSVP ----
    case 'stats':
        $total   = (int)$db->query("SELECT COUNT(*) FROM rsvp")->fetchColumn();
        $hadir   = (int)$db->query("SELECT COUNT(*) FROM rsvp WHERE kehadiran='hadir'")->fetchColumn();
        $tidak   = (int)$db->query("SELECT COUNT(*) FROM rsvp WHERE kehadiran='tidak'")->fetchColumn();
        $pending = (int)$db->query("SELECT COUNT(*) FROM rsvp WHERE kehadiran='pending'")->fetchColumn();
        $jumlahTotal = (int)$db->query("SELECT COALESCE(SUM(jumlah_tamu),0) FROM rsvp WHERE kehadiran='hadir'")->fetchColumn();
        echo json_encode([
            'success'      => true,
            'total'        => $total,
            'hadir'        => $hadir,
            'tidak'        => $tidak,
            'pending'      => $pending,
            'jumlah_total' => $jumlahTotal,
        ]);
        break;

    // ---- Hapus satu tamu ----
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
        $id   = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM rsvp WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'deleted' => $stmt->rowCount()]);
        break;

    // ---- Baca semua pengaturan ----
    case 'get_settings':
        $rows = $db->query("SELECT `key`, `value` FROM pengaturan")->fetchAll();
        $map  = [];
        foreach ($rows as $r) $map[$r['key']] = $r['value'];
        echo json_encode(['success' => true, 'data' => $map]);
        break;

    // ---- Simpan pengaturan ----
    case 'save_settings':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
        $allowed = [
            'nama_pria','nama_wanita','tanggal_acara','waktu_acara','lokasi_acara',
            'bank_name','nomor_rekening','admin_username','admin_password',
            'putra_dari','putri_dari','dresscode','catatan_tambahan',
        ];
        $stmt = $db->prepare("INSERT INTO pengaturan (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        $saved = 0;
        foreach ($allowed as $key) {
            if (isset($_POST[$key])) {
                $stmt->execute([$key, trim($_POST[$key])]);
                $saved++;
            }
        }
        echo json_encode(['success' => true, 'saved' => $saved]);
        break;

    // ---- Edit tamu ----
    case 'edit_guest':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
        $id   = (int)($_POST['id'] ?? 0);
        $nama = trim(strip_tags($_POST['nama'] ?? ''));
        if ($nama === '' || $id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
            exit;
        }
        try {
            $kehadiran = in_array($_POST['kehadiran'] ?? '', ['hadir','tidak','pending'], true) ? $_POST['kehadiran'] : 'pending';
            $jumlah = max(1, min(20, (int)($_POST['jumlah_tamu'] ?? 1)));
            $ucapan = trim(strip_tags($_POST['ucapan'] ?? ''));
            
            $stmt = $db->prepare("UPDATE rsvp SET nama = ?, kehadiran = ?, jumlah_tamu = ?, ucapan = ? WHERE id = ?");
            $stmt->execute([$nama, $kehadiran, $jumlah, $ucapan, $id]);
            
            echo json_encode(['success' => true, 'message' => 'Data tamu berhasil diperbarui.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui tamu: ' . $e->getMessage()]);
        }
        break;

    // ---- Tambah tamu baru (manual dari admin) ----
    case 'add_guest':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
        $nama = trim(strip_tags($_POST['nama'] ?? ''));
        if ($nama === '') {
            echo json_encode(['success' => false, 'message' => 'Nama tamu tidak boleh kosong.']);
            exit;
        }
        try {
            $slug = generateGuestSlug($nama);
            $check = $db->prepare("SELECT COUNT(*) FROM rsvp WHERE slug = ?");
            do {
                $check->execute([$slug]);
            } while ((int)$check->fetchColumn() > 0 && $slug = generateGuestSlug($nama));

            $stmt = $db->prepare("INSERT INTO rsvp (nama, slug, kehadiran, jumlah_tamu, ucapan) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $slug, 'pending', 1, '']);
            
            $inviteLink = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI'], 2) . '/undangan.php?guest=' . urlencode($slug);
            echo json_encode([
                'success' => true,
                'message' => 'Tamu berhasil ditambahkan.',
                'guest' => [
                    'id' => $db->lastInsertId(),
                    'nama' => $nama,
                    'slug' => $slug,
                    'kehadiran' => 'pending',
                    'jumlah_tamu' => 1,
                    'ucapan' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'invite_link' => $inviteLink
                ]
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan tamu: ' . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}
