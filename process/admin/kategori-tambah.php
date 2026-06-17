<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
$name = trim($_POST['name'] ?? '');
if ($name) {
    getDB()->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
}
header('Location: /Nemu.id/admin/kategori.php');
exit;