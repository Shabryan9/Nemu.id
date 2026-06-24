<?php
require_once __DIR__ . '/../config/connection.php';

// Jika sudah login, redirect sesuai role
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: /Nemu.id/admin/index.php');
    } else {
        header('Location: /Nemu.id/user/dashboard.php');
    }
    exit;
}

$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

$page_title = 'Login - Lost & Found Kampus';
$body_class = 'login-page';
include __DIR__ . '/../includes/header.php';
?>

<div class="login-section">
    <div class="login-container">
        <div class="login-card">
            <div class="logo-wrapper">
                <div class="logo-circle">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            </div>
            <h1 class="login-title">Masuk ke Akun</h1>
            <p class="login-subtitle">Sistem Informasi Layanan Barang Hilang & Temuan Kampus</p>

            <?php if ($error): ?>
                <div class="alert-error"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="/Nemu.id/process/login.php">
                <!-- Hidden input dari halaman user -->
                <input type="hidden" name="login_as" value="user">

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nama@kampus.ac.id" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        
                    </div>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <hr class="divider">
            <div class="forgot-password">
                <a href="#">Lupa password? Hubungi admin.</a>
            </div>
            <div class="text-center mt-3">
                <a href="/Nemu.id/admin/login.php" class="btn btn-sm btn-outline-secondary">Login sebagai Admin</a>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../includes/footer.php'; ?>