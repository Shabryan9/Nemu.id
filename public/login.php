<?php
// Include connection.php to start session and get DB connection
require_once __DIR__ . '/../config/connection.php';
 
// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../user/dashboard.php');
    }
    exit;
}

// Ambil flash message dari session (jika ada)
$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;

// Hapus flash message setelah diambil
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Lost & Found Kampus</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css?v=<?= time() ?>"/>
</head>
<body class="login-page">

<main class="login-section">
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
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toggle Password Script -->
<script>
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

<?php include __DIR__ . '/../includes/footer.php'; ?>