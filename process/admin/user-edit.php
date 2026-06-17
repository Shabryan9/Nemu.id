<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$id = $_POST['id'] ?? 0;
$nama = trim($_POST['nama_lengkap'] ?? '');
$role = $_POST['role'] ?? 'user';

$pdo = getDB();
$stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, role = ? WHERE id = ?");
$stmt->execute([$nama, $role, $id]);
$_SESSION['flash_success'] = 'User diperbarui.';
header('Location: /Nemu.id/admin/users.php');
exit;