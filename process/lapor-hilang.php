<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Nemu.id/user/lapor-hilang.php');
    exit;
}

$pdo = getDB();
$user_id = currentUserId();

$category_id  = $_POST['category_id'] ?? null;
$item_name    = trim($_POST['item_name'] ?? '');
$description  = trim($_POST['description'] ?? '');
$last_location = trim($_POST['last_location'] ?? '');
$lost_datetime = $_POST['lost_datetime'] ?? date('Y-m-d H:i:s');

// Validasi sederhana
if (empty($item_name) || empty($description) || empty($last_location)) {
    $_SESSION['flash_error'] = 'Semua field wajib diisi.';
    header('Location: /Nemu.id/user/lapor-hilang.php');
    exit;
}

// Upload foto (opsional)
$photoName = null;
if (!empty($_FILES['photo']['name'])) {
    $targetDir = __DIR__ . '/../assets/uploads/items/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photoName = time() . '_' . uniqid() . '.' . $ext;
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photoName)) {
        $_SESSION['flash_error'] = 'Gagal mengunggah foto.';
        header('Location: /Nemu.id/user/lapor-hilang.php');
        exit;
    }
}

$stmt = $pdo->prepare("INSERT INTO lost_items (user_id, category_id, item_name, description, last_location, lost_datetime, photo, status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, 'hilang')");
$stmt->execute([$user_id, $category_id, $item_name, $description, $last_location, $lost_datetime, $photoName]);

$_SESSION['flash_success'] = 'Laporan berhasil dikirim.';
header('Location: /Nemu.id/user/dashboard.php');
exit;