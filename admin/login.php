<?php
require_once __DIR__ . '/../config/connection.php';

// Jika sudah login, redirect sesuai role
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: index.php');
        exit;
    } else {
        header('Location: /Nemu.id/user/dashboard.php');
        exit;
    }
}

$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

$page_title = 'Login Admin - Nemu.id';
$body_class = 'login-page';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Nemu.id/assets/css/custom.css">
</head>
<body class="login-page">
<div class="login-section">
    <div class="login-container">
        <div class="login-card">
            <div class="logo-wrapper">
                <div class="logo-circle">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            </div>
            <h1 class="login-title">Login Admin</h1>
            <p class="login-subtitle">Hanya untuk administrator sistem</p>

            <?php if ($error): ?>
                <div class="alert-error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="../process/login.php">
                <!-- Hidden input untuk menandai login dari halaman admin -->
                <input type="hidden" name="login_as" value="admin">
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@kampus.ac.id" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        <span class="toggle-icon" onclick="togglePassword()"><i class="bi bi-eye" id="toggle-icon"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <hr class="divider">
            <div class="text-center mt-3">
                <a href="/Nemu.id/public/login.php">← Kembali ke Login User</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const el = document.getElementById('password');
    const icon = document.getElementById('toggle-icon');
    if (el.type === 'password') {
        el.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        el.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
</body>
</html>