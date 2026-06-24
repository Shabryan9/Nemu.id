<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    exit('Akses langsung tidak diizinkan.');
}


//ngecek apakah user sudah login.

function isLoggedIn() {
    return isset($_SESSION['user']);
}


//gecek apakah user saat ini adalah admin.

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}


//ngambil ID user yang sedang login.

function currentUserId() {
    return $_SESSION['user']['id'] ?? null;
}


//Mengambil nama user yang sedang login.

function currentUserName() {
    return $_SESSION['user']['nama_lengkap'] ?? 'User';
}


//ngewajibin user sudah login sebelum mengakses halaman.

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
}


//Mewajibkan user admin sebelum mengakses halaman admin.

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /Nemu.id/admin/login.php');
        exit;
    }
}


//ngewajibin user biasa dan mengalihkan admin ke panel admin.

function requireUser() {
    if (!isLoggedIn()) {
        header('Location: /Nemu.id/public/login.php');
        exit;
    }
    //ngarain admin ke panel admin agar halaman user tetap khusus user.
    if (isAdmin()) {
        header('Location: /Nemu.id/admin/index.php');
        exit;
    }
}
