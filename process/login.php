<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nemu.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-box">

        <!-- Header -->
        <div class="login-header">
            <div class="login-brand">nemu.id</div>
            <div class="login-univ">Universitas Reuka</div>
        </div>

        <!-- Card Form -->
        <div class="login-card">
            <h2 class="login-title">Selamat Datang</h2>
            <p class="login-subtitle">Masuk untuk melaporkan barang hilang &amp; temuan<br>di area kampus.</p>

            <!-- Divider -->
            <div class="login-divider">
                <span>GUNAKAN EMAIL</span>
            </div>

            <form action="" method="POST">

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label-custom">Email Akademik</label>
                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        placeholder="nama@reuka.ac.id"
                        required
                    >
                    <small class="form-hint">Hanya mendukung email berakhiran @reuka.ac.id</small>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="label-row">
                        <label class="form-label-custom">Kata Sandi</label>
                        <a href="#" class="lupa-pass">Lupa password?</a>
                    </div>
                    <div class="input-password-wrap">
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            class="form-input"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="toggle-pass" onclick="togglePassword()">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#9ca3af" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="remember-row">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <label for="rememberMe">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-masuk-full">Masuk</button>

            </form>
        </div>

        <!-- Footer note -->
        <div class="login-footer-note">
            Sistem Terintegrasi dengan Layanan Keamanan Kampus
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>