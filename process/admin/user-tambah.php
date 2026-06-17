<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$pdo = getDB();
$nama = trim($_POST['nama_lengkap'] ?? '');
$nim = trim($_POST['nim_nip'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';

if (empty($nama) || empty($nim) || empty($email) || empty($password)) {
    $_SESSION['flash_error'] = 'Semua field wajib diisi.';
    header('Location: /Nemu.id/admin/users.php');
    exit;
}
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, nim_nip, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$nama, $nim, $email, $hash, $role]);
    $_SESSION['flash_success'] = 'User berhasil ditambahkan.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Gagal: ' . $e->getMessage();
}
header('Location: /Nemu.id/admin/users.php');
exit;