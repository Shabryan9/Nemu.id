<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
        <a class="navbar-brand" href="/Nemu.id/public/index.php">
            <span class="brand-logo">nemu.id</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarPublic" aria-controls="navbarPublic" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPublic">
            <ul class="navbar-nav ms-auto mb-0">
                <li class="nav-item"><a class="nav-link" href="/Nemu.id/public/index.php">Beranda</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/Nemu.id/user/dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="/Nemu.id/process/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-masuk" href="/Nemu.id/public/login.php">Masuk</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid" style="min-height: calc(100vh - 200px);">