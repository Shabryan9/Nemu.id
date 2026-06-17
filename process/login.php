<?php
require_once __DIR__ . '/../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Nemu.id/public/login.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$login_as = $_POST['login_as'] ?? 'user'; // default user

if (empty($email) || empty($password)) {
    $_SESSION['flash_error'] = 'Email dan password wajib diisi.';
    // redirect ke halaman asal sesuai login_as
    $redirect = ($login_as === 'admin') ? '/Nemu.id/admin/login.php' : '/Nemu.id/public/login.php';
    header('Location: ' . $redirect);
    exit;
}

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_blocked = 0");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Validasi role berdasarkan halaman login
        if ($login_as === 'admin' && $user['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Akun Anda bukan administrator. Silakan gunakan halaman login user.';
            header('Location: /Nemu.id/admin/login.php');
            exit;
        }
        if ($login_as === 'user' && $user['role'] !== 'user') {
            $_SESSION['flash_error'] = 'Akun Anda adalah admin. Silakan gunakan halaman login admin.';
            header('Location: /Nemu.id/public/login.php');
            exit;
        }

        unset($user['password_hash']);
        $_SESSION['user'] = $user;

        // Redirect sesuai role (sama seperti sebelumnya)
        if ($user['role'] === 'admin') {
            header('Location: /Nemu.id/admin/index.php');
        } else {
            header('Location: /Nemu.id/user/dashboard.php');
        }
        exit;
    } else {
        $_SESSION['flash_error'] = 'Email atau password salah.';
        $redirect = ($login_as === 'admin') ? '/Nemu.id/admin/login.php' : '/Nemu.id/public/login.php';
        header('Location: ' . $redirect);
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Terjadi kesalahan server. Silakan coba lagi.';
    $redirect = ($login_as === 'admin') ? '/Nemu.id/admin/login.php' : '/Nemu.id/public/login.php';
    header('Location: ' . $redirect);
    exit;
}