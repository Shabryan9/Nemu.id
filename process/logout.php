<?php
// Pastikan session sudah dimulai (melalui connection.php)
require_once __DIR__ . '/../config/connection.php';

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header('Location: /Nemu.id/public/login.php');
exit;