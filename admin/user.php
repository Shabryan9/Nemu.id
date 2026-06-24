<?php
$active_page = 'users';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
$current_admin_id = currentUserId();
// [AKSI]: Ambil semua user untuk tabel manajemen akun.
$users = dbFetchAll("SELECT * FROM users ORDER BY created_at DESC");
$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
$page_title = 'Manajemen User';
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Manajemen User</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Tambah User</button>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white">
                    <thead><tr><th>Nama</th><th>NIM/NIP</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($u['nim_nip']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= $u['role'] ?></td>
                            <td>
                                <span class="badge bg-<?= $u['is_blocked'] ? 'danger' : 'success' ?>">
                                    <?= $u['is_blocked'] ? 'Diblokir' : 'Aktif' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUser<?= $u['id'] ?>">Edit</button>
                                <?php if ((int) $u['id'] === (int) $current_admin_id): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" disabled>Akun Anda</button>
                                <?php elseif ($u['role'] === 'admin'): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" disabled>Admin</button>
                                <?php else: ?>
                                    <a href="/Nemu.id/process/admin/user-toogle-block.php?id=<?= $u['id'] ?>" class="btn btn-sm <?= $u['is_blocked'] ? 'btn-success' : 'btn-danger' ?>" onclick="return confirm('Yakin?')"><?= $u['is_blocked'] ? 'Buka' : 'Blokir' ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- Modal Edit User -->
                        <div class="modal fade" id="editUser<?= $u['id'] ?>" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="/Nemu.id/process/admin/user-edit.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <div class="modal-header"><h5>Edit User</h5></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Nama</label><input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($u['nama_lengkap']) ?>" required></div>
                                        <div class="mb-3"><label>Role</label><select class="form-control" name="role"><option value="user" <?= $u['role']=='user'?'selected':'' ?>>User</option><option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option></select></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </form>
                            </div></div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="/Nemu.id/process/admin/user-tambah.php" method="POST">
            <div class="modal-header"><h5>Tambah User</h5></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" required></div>
                <div class="mb-3"><label>NIM/NIP</label><input type="text" class="form-control" name="nim_nip" required></div>
                <div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email" required></div>
                <div class="mb-3"><label>Password</label><input type="password" class="form-control" name="password" required></div>
                <div class="mb-3"><label>Role</label><select class="form-control" name="role"><option value="user">User</option><option value="admin">Admin</option></select></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Tambah</button></div>
        </form>
    </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
