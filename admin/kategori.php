<?php
$active_page = 'kategori';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$page_title = 'Manajemen Kategori';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Nemu.id/assets/css/custom.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2>Manajemen Kategori</h2>
            <form action="/Nemu.id/process/admin/kategori-tambah.php" method="POST" class="row g-2 mb-4">
                <div class="col-auto"><input type="text" name="name" class="form-control" placeholder="Nama Kategori" required></div>
                <div class="col-auto"><button type="submit" class="btn btn-primary">Tambah</button></div>
            </form>
            <table class="table table-bordered bg-white">
                <thead><tr><th>Nama</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCat<?= $cat['id'] ?>">Edit</button>
                            <a href="/Nemu.id/process/admin/kategori-hapus.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori?')">Hapus</a>
                        </td>
                    </tr>
                    <div class="modal fade" id="editCat<?= $cat['id'] ?>" tabindex="-1">
                        <div class="modal-dialog"><div class="modal-content">
                            <form action="/Nemu.id/process/admin/kategori-edit.php" method="POST">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <div class="modal-header"><h5>Edit Kategori</h5></div>
                                <div class="modal-body">
                                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
                                </div>
                                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                            </form>
                        </div></div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>