<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
$id = $_GET['id'] ?? 0;
if ($id) {
    getDB()->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
}
header('Location: /Nemu.id/admin/kategori.php');
exit;