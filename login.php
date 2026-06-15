
<?php 
// Login page identifier
define('IS_LOGIN_PAGE', true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Nemu.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="/Nemu.id/assets/css/custom.css?v=<?php echo time(); ?>"/>
</head>
<body class="login-page">

<?php include __DIR__ . '/includes/header.php'; ?>

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

            <!-- Alert Messages -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    Email atau password salah. Silakan coba lagi.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="/Nemu.id/process/login.php">
            <!-- Email Input -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="nama@reuka.ac.id"
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

<?php include __DIR__ . '/includes/footer.php'; ?>