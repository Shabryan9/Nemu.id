<?php 
?>
<!DOCTYPE html>
<html lang="id" class="<?= $html_class ?? '' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $page_title ?? 'Nemu.id' ?></title>
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/Nemu.id/assets/css/custom.css?v=<?php echo time(); ?>"/>
</head>
<body class="<?= $body_class ?? '' ?>">

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-md-4 px-3">
        <!-- Brand + Navigation Links (Desktop) -->
        <div class="navbar-brand-nav d-flex align-items-center">
            <a class="navbar-brand" href="/Nemu.id/user/dashboard.php">
                <span class="brand-logo">nemu.id</span>
            </a>
            
            <!-- Navigation Links (Hidden on mobile) -->
            <ul class="navbar-nav d-none d-lg-flex mb-0 ms-4">
                <li class="nav-item">
                    <a class="nav-link active" href="/Nemu.id/user/dashboard.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#katalog">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#laporan">Laporan Saya</a>
                </li>
            </ul>
        </div>

        <!-- Toggler untuk mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarContent" aria-controls="navbarContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Content (Mobile Collapse) -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Mobile Navigation Links -->
            <ul class="navbar-nav ms-auto mb-3 mb-lg-0 d-lg-none">
                <li class="nav-item">
                    <a class="nav-link active" href="/Nemu.id/user/dashboard.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#katalog">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#laporan">Laporan Saya</a>
                </li>
            </ul>

            <!-- Right Icons & User Menu -->
            <div class="navbar-icons d-flex align-items-center gap-2 ms-lg-auto">
                <!-- Notification Icon -->
                <a href="#notifikasi" class="icon-link" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </a>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <a class="icon-link dropdown-toggle" href="#" id="userMenu" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#profil"><?= htmlspecialchars($user_name) ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item logout-link" href="/Nemu.id/process/logout.php">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid" style="min-height: calc(100vh - 200px);">
    <!-- Konten halaman di sini -->