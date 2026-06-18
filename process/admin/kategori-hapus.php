<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
$id = $_GET['id'] ?? 0;
if ($id) {
    $pdo = getDB();
    
    // 1. Amankan tabel lost_items: Ubah semua barang hilang dengan kategori ini menjadi NULL (Umum)
    $pdo->prepare("UPDATE lost_items SET category_id = NULL WHERE category_id = ?")->execute([$id]);
    
    // 2. Amankan tabel found_items: Ubah semua barang temuan dengan kategori ini menjadi NULL (Umum)
    $pdo->prepare("UPDATE found_items SET category_id = NULL WHERE category_id = ?")->execute([$id]);
    
    // 3. Setelah relasinya aman, baru hapus kategorinya dari database
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
}
header('Location: /Nemu.id/admin/kategori.php');
exit;