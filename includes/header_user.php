<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';
requireLogin();
// Tentukan halaman aktif (default 'dashboard')
$active_page = $active_page ?? 'dashboard';

$user_name = currentUserName();
$unread_count = 0;
if (isset($_SESSION['user'])) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user']['id']]);
    $unread_count = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="id" class="<?= $html_class ?? '' ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $page_title ?? 'Nemu.id' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="/Nemu.id/assets/css/custom.css?v=<?= time() ?>"/>
</head>
<body class="<?= $body_class ?? '' ?>">

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-md-4 px-3">
        <div class="navbar-brand-nav d-flex align-items-center">
            <a class="navbar-brand" href="/Nemu.id/user/dashboard.php">
                <span class="brand-logo">nemu.id</span>
            </a>
            <ul class="navbar-nav d-none d-lg-flex mb-0 ms-4">
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'dashboard' ? 'active' : '' ?>" href="/Nemu.id/user/dashboard.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'cari' ? 'active' : '' ?>" href="/Nemu.id/user/cari.php">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'lapor-hilang' ? 'active' : '' ?>" href="/Nemu.id/user/lapor-hilang.php">Lapor Hilang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'lapor-temuan' ? 'active' : '' ?>" href="/Nemu.id/user/lapor-temuan.php">Lapor Temuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'laporan-saya' ? 'active' : '' ?>" href="/Nemu.id/user/laporan-saya.php">Laporan Saya</a>
                </li>
            </ul>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarUser" aria-controls="navbarUser" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarUser">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-lg-none">
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'dashboard' ? 'active' : '' ?>" href="/Nemu.id/user/dashboard.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'cari' ? 'active' : '' ?>" href="/Nemu.id/user/cari.php">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'lapor-hilang' ? 'active' : '' ?>" href="/Nemu.id/user/lapor-hilang.php">Lapor Hilang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'lapor-temuan' ? 'active' : '' ?>" href="/Nemu.id/user/lapor-temuan.php">Lapor Temuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_page == 'laporan-saya' ? 'active' : '' ?>" href="/Nemu.id/user/laporan-saya.php">Laporan Saya</a>
                </li>
            </ul>
            <div class="navbar-icons d-flex align-items-center gap-2 ms-lg-auto">
                <a href="/Nemu.id/user/notifikasi.php" class="icon-link position-relative" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <?php if ($unread_count > 0): ?>
                    <span class="notification-badge"><?= $unread_count ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown">
                    <a class="icon-link dropdown-toggle" href="#" id="userMenu" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><span class="dropdown-item-text fw-bold"><?= htmlspecialchars($user_name) ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if (isAdmin()): ?>
                            <li><a class="dropdown-item" href="/Nemu.id/admin/index.php">Panel Admin</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item logout-link" href="/Nemu.id/process/logout.php">Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid" style="min-height: calc(100vh - 200px);">