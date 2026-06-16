<?php
// Include connection.php to start session and get DB connection
require_once __DIR__ . '/../config/connection.php';
 
// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: /Nemu.id/admin/index.php');
    } else {
        header('Location: /Nemu.id/user/dashboard.php');
    }
    exit;
}

// Ambil flash message dari session (jika ada)
$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;

// Hapus flash message setelah diambil
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>
<?php 
$page_title = 'Login - Lost & Found Kampus';
$body_class = 'login-page'; // Set body class for header.php
include __DIR__ . '/../includes/header.php';
?>
<div class="login-section">
    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-wrapper">
                <div class="logo-circle">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            </div>

            <!-- Title & Subtitle -->
            <h1 class="login-title">Masuk ke Akun</h1>
            <p class="login-subtitle">
                Sistem Informasi Layanan Barang Hilang & Temuan Kampus
            </p>

            <!-- Flash Error -->
            <?php if ($error): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Flash Success -->
            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="../process/login.php">
                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="nama@kampus.ac.id"
                        required
                    />
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                        />
                        <span class="toggle-icon" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggle-icon"></i>
                        </span>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <!-- Divider -->
            <hr class="divider">

            <!-- Forgot Password Link -->
            <div class="forgot-password">
                <a href="#">Lupa password? Hubungi admin.</a>
            </div>
        </div>
    </div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script>
    // Toggle Password Script
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>