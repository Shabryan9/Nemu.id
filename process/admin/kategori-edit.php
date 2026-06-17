<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
$id = $_POST['id'] ?? 0;
$name = trim($_POST['name'] ?? '');
if ($id && $name) {
    getDB()->prepare("UPDATE categories SET name = ? WHERE id = ?")->execute([$name, $id]);
}
header('Location: /Nemu.id/admin/kategori.php');
exit;