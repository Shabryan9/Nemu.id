<?php
// 1. Mulai session (wajib sebelum akses $_SESSION)
session_start();

// 2. Load koneksi database
require_once __DIR__ . '/../config/connection.php';

// 3. Hanya terima metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

// 4. Ambil input dari form
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// 5. Validasi sederhana
if (empty($email) || empty($password)) {
    $_SESSION['flash_error'] = 'Email dan password wajib diisi.';
    header('Location: ../public/login.php');
    exit;
}

try {
    // 6. Cari user di database (hanya yang tidak diblokir)
    $pdo  = getDB();
    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ? AND is_blocked = 0"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 7. Verifikasi password
    if ($user && password_verify($password, $user['password_hash'])) {
        // Hapus password hash dari session demi keamanan
        unset($user['password_hash']);

        // Simpan data user ke session
        $_SESSION['user'] = $user;

        // 8. Redirect sesuai role
        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../user/dashboard.php');
        }
        exit;
    } else {
        // Login gagal
        $_SESSION['flash_error'] = 'Email atau password salah.';
        header('Location: ../public/login.php');
        exit;
    }
} catch (PDOException $e) {
    // Error database
    $_SESSION['flash_error'] = 'Terjadi kesalahan server. Silakan coba lagi.';
    header('Location: ../public/login.php');
    exit;
}