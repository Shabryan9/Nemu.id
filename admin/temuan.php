<?php
$active_page = 'temuan';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
$filter = $_GET['filter'] ?? 'pending';
$statusMap = [
    'pending' => 'pending',
    'diverifikasi' => 'tersedia',
    'ditolak' => 'ditolak_admin',
];
$statusFilter = $statusMap[$filter] ?? null;
$where = $statusFilter ? 'WHERE f.status = ?' : '';
$params = $statusFilter ? [$statusFilter] : [];


$stmt = $pdo->prepare("SELECT f.*, u.nama_lengkap AS finder_name, c.name AS category_name
                       FROM found_items f
                       JOIN users u ON f.finder_user_id = u.id
                       LEFT JOIN categories c ON f.category_id = c.id
                       $where ORDER BY f.created_at DESC");
$stmt->execute($params);
$items = $stmt->fetchAll();

$page_title = 'Verifikasi Temuan';
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
            <h2>Verifikasi Laporan Temuan</h2>
            <div class="btn-group mb-3">
                <a href="?filter=pending" class="btn btn-outline-primary <?= $filter=='pending'?'active':'' ?>">Pending</a>
                <a href="?filter=diverifikasi" class="btn btn-outline-primary <?= $filter=='diverifikasi'?'active':'' ?>">Diverifikasi</a>
                <a href="?filter=ditolak" class="btn btn-outline-primary <?= $filter=='ditolak'?'active':'' ?>">Ditolak</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Nama Barang</th><th>Penemu</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= htmlspecialchars($item['finder_name']) ?></td>
                            <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                            <td><span class="badge bg-<?= $item['status']=='pending'?'warning':($item['status']=='tersedia'?'success':'danger') ?>"><?= $item['status'] ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $item['id'] ?>">Detail</button>
                                <?php if ($item['status'] == 'pending'): ?>
                                    <a href="/Nemu.id/process/admin/verifikasi-temuan.php?id=<?= $item['id'] ?>&action=verify" class="btn btn-sm btn-success">Verifikasi</a>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal<?= $item['id'] ?>">Tolak</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal<?= $item['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg"><div class="modal-content">
                                <div class="modal-header"><h5 class="modal-title">Detail Temuan #<?= $item['id'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php if ($item['photo']): ?><img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="img-fluid"><?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Nama:</strong> <?= htmlspecialchars($item['item_name']) ?></p>
                                            <!-- Kategori sekarang muncul karena sudah di-query -->
                                            <p><strong>Kategori:</strong> <?= htmlspecialchars($item['category_name'] ?? '-') ?></p>
                                            <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($item['description'])) ?></p>
                                            <p><strong>Lokasi:</strong> <?= htmlspecialchars($item['found_location']) ?></p>
                                            <p><strong>Waktu:</strong> <?= $item['found_datetime'] ?></p>
                                            <p><strong>Penemu:</strong> <?= htmlspecialchars($item['finder_name']) ?><?= $item['is_anonymous'] ? ' (anonim)' : '' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div></div>
                        </div>
                        <!-- Modal Tolak -->
                        <div class="modal fade" id="tolakModal<?= $item['id'] ?>" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="/Nemu.id/process/admin/verifikasi-temuan.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <div class="modal-header"><h5>Tolak Laporan</h5></div>
                                    <div class="modal-body">
                                        <textarea class="form-control" name="admin_note" placeholder="Alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>