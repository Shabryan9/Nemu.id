<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$pdo = getDB();

// Pastikan laporan milik user
$stmt = $pdo->prepare("SELECT id FROM lost_items WHERE id = ? AND user_id = ?");
$stmt->execute([$id, currentUserId()]);
if ($stmt->fetch()) {
    $update = $pdo->prepare("UPDATE lost_items SET status = 'ditemukan_sendiri' WHERE id = ?");
    $update->execute([$id]);
    $_SESSION['flash_success'] = 'Laporan berhasil ditutup.';
} else {
    $_SESSION['flash_error'] = 'Laporan tidak ditemukan.';
}
header('Location: /Nemu.id/user/laporan-saya.php?type=lost');
exit;