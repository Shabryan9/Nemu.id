<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

/**
 * Mengarahkan kembali ke halaman manajemen kategori.
 */
function redirectKategori(): void {
    header('Location: /Nemu.id/admin/kategori.php');
    exit;
}

$pdo = getDB();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// [AKSI]: Tambahkan kategori baru dari form admin.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $name = trim($_POST['name'] ?? '');

    if ($name !== '') {
        dbExecute("INSERT INTO categories (name) VALUES (?)", [$name]);
    }

    redirectKategori();
}

// [AKSI]: Perbarui nama kategori dari modal edit.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    if ($id > 0 && $name !== '') {
        dbExecute("UPDATE categories SET name = ? WHERE id = ?", [$name, $id]);
    }

    redirectKategori();
}

// [AKSI]: Hapus kategori setelah relasi laporan diamankan menjadi kategori umum.
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($id > 0) {
        dbExecute("UPDATE lost_items SET category_id = NULL WHERE category_id = ?", [$id]);
        dbExecute("UPDATE found_items SET category_id = NULL WHERE category_id = ?", [$id]);
        dbExecute("DELETE FROM categories WHERE id = ?", [$id]);
    }

    redirectKategori();
}

redirectKategori();
