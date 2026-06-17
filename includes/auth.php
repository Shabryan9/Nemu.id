<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    exit('Akses langsung tidak diizinkan.');
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function currentUserId() {
    return $_SESSION['user']['id'] ?? null;
}

function currentUserName() {
    return $_SESSION['user']['nama_lengkap'] ?? 'User';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
}

function requireUser() {
    if (!isLoggedIn()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
    // Jika admin, redirect ke halaman admin
    if (isAdmin()) {
        header('Location: /Nemu.id/admin/index.php');
        exit;
    }
}