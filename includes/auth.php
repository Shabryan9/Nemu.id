<?php
// Mencegah akses langsung ke file ini
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    exit('Akses langsung tidak diizinkan.');
}

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

// Dapatkan ID user yang sedang login
function currentUserId() {
    return $_SESSION['user']['id'] ?? null;
}

// Dapatkan nama lengkap user yang sedang login
function currentUserName() {
    return $_SESSION['user']['nama_lengkap'] ?? 'User';
}

// Dapatkan field spesifik dari data user di session
function currentUserField($field) {
    return $_SESSION['user'][$field] ?? null;
}

// Proteksi halaman: harus login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
}

// Proteksi halaman: harus admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
}