<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Nemu.id/user/lapor-temuan.php');
    exit;
}

$pdo = getDB();
$user_id = currentUserId();

$category_id   = $_POST['category_id'] ?? null;
$item_name     = trim($_POST['item_name'] ?? '');
$description   = trim($_POST['description'] ?? '');
$found_location = trim($_POST['found_location'] ?? '');
$found_datetime = $_POST['found_datetime'] ?? date('Y-m-d H:i:s');
$is_anonymous  = isset($_POST['is_anonymous']) ? 1 : 0;

if (empty($item_name) || empty($description) || empty($found_location)) {
    $_SESSION['flash_error'] = 'Data tidak lengkap.';
    header('Location: /Nemu.id/user/lapor-temuan.php');
    exit;
}

// Foto wajib
if (empty($_FILES['photo']['name'])) {
    $_SESSION['flash_error'] = 'Foto barang wajib diunggah.';
    header('Location: /Nemu.id/user/lapor-temuan.php');
    exit;
}

$targetDir = __DIR__ . '/../assets/uploads/items/';
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photoName = time() . '_' . uniqid() . '.' . $ext;
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photoName)) {
    $_SESSION['flash_error'] = 'Gagal mengunggah foto.';
    header('Location: /Nemu.id/user/lapor-temuan.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO found_items (finder_user_id, category_id, item_name, description, found_location, found_datetime, photo, is_anonymous, status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
$stmt->execute([$user_id, $category_id, $item_name, $description, $found_location, $found_datetime, $photoName, $is_anonymous]);

$_SESSION['flash_success'] = 'Laporan temuan berhasil dikirim dan menunggu verifikasi admin.';
header('Location: /Nemu.id/user/dashboard.php');
exit;