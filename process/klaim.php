<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Nemu.id/user/dashboard.php');
    exit;
}

$pdo = getDB();
$user_id = currentUserId();
$found_item_id = $_POST['found_item_id'] ?? 0;
$claim_reason = trim($_POST['claim_reason'] ?? '');

// Validasi
if (empty($claim_reason) || empty($_FILES['evidence_photo']['name'])) {
    $_SESSION['flash_error'] = 'Alasan dan bukti wajib diisi.';
    header('Location: /Nemu.id/user/detail-temuan.php?id=' . $found_item_id);
    exit;
}

// Cek status barang
$item = $pdo->prepare("SELECT status, item_name, finder_user_id FROM found_items WHERE id = ?");
$item->execute([$found_item_id]);
$item = $item->fetch();
if (!$item || $item['status'] !== 'tersedia') {
    $_SESSION['flash_error'] = 'Barang tidak dapat diklaim.';
    header('Location: /Nemu.id/user/dashboard.php');
    exit;
}

if ((int) $item['finder_user_id'] === (int) $user_id) {
    $_SESSION['flash_error'] = 'Anda tidak dapat mengklaim laporan temuan sendiri.';
    header('Location: /Nemu.id/user/detail-temuan.php?id=' . $found_item_id);
    exit;
}

$activeClaim = $pdo->prepare("SELECT id FROM claims WHERE found_item_id = ? AND claimant_user_id = ? AND status IN ('pending', 'disetujui')");
$activeClaim->execute([$found_item_id, $user_id]);
if ($activeClaim->fetchColumn()) {
    $_SESSION['flash_error'] = 'Anda sudah memiliki klaim aktif untuk barang ini.';
    header('Location: /Nemu.id/user/detail-temuan.php?id=' . $found_item_id);
    exit;
}

// Upload bukti
$targetDir = __DIR__ . '/../assets/uploads/evidence/';
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
$ext = pathinfo($_FILES['evidence_photo']['name'], PATHINFO_EXTENSION);
$evidenceName = 'evidence_' . time() . '_' . uniqid() . '.' . $ext;
move_uploaded_file($_FILES['evidence_photo']['tmp_name'], $targetDir . $evidenceName);

// Insert klaim
$stmt = $pdo->prepare("INSERT INTO claims (found_item_id, claimant_user_id, claim_reason, evidence_photo, status)
                       VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$found_item_id, $user_id, $claim_reason, $evidenceName]);

// Update status barang
$update = $pdo->prepare("UPDATE found_items SET status = 'dalam_proses_klaim' WHERE id = ?");
$update->execute([$found_item_id]);

//Kirim notifikasi klaim baru ke seluruh admin.
$admins = dbFetchAll("SELECT id FROM users WHERE role = 'admin'");
if ($admins) {
    foreach ($admins as $admin) {
        $notif = $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
        $notif->execute([$admin['id'], 'Klaim baru untuk barang: ' . $item['item_name'], '/Nemu.id/admin/klaim.php']);
    }
}

$_SESSION['flash_success'] = 'Klaim berhasil diajukan. Admin akan memproses.';
header('Location: /Nemu.id/user/dashboard.php');
exit;
