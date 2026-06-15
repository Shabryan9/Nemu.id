<?php 
session_save_path(__DIR__ . '/../sessions');
if (!is_dir(session_save_path())) {
    mkdir(session_save_path(), 0777, true);
}
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Nemu.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="./assets/css/custom.css?v=<?php echo time(); ?>"/>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom px-3">
<!-- Brand -->
    <a class="navbar-brand" href="#">Nemu.id</a>

<!-- Toggler untuk mobile -->
    <button class="navbar-toggler border-0" type="button"
    data-bs-toggle="collapse" data-bs-target="#navbarContent"
    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

<!-- Nav Links -->
<div class="collapse navbar-collapse" id="navbarContent">
    <ul class="navbar-nav me-auto mb-0">
        <li class="nav-item">
        <a class="nav-link active" href="#">Beranda</a>
        </li>
    </ul>

    <!-- Tombol Masuk -->
    <button class="btn btn-masuk">Masuk</button>
</div>
</nav>

<main class="container-fluid" style="min-height: calc(100vh - 200px);">
    <!-- Konten halaman di sini -->