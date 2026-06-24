<?php
$active_page = 'klaim';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
$filter = $_GET['filter'] ?? 'pending';
$statusMap = [
    'pending' => 'pending',
    'disetujui' => 'disetujui',
    'ditolak' => 'ditolak',
];
$statusFilter = $statusMap[$filter] ?? null;
$where = $statusFilter ? 'WHERE c.status = ?' : '';
$params = $statusFilter ? [$statusFilter] : [];

// [AKSI]: Ambil klaim sesuai filter yang diizinkan.
$stmt = $pdo->prepare("SELECT c.*, u.nama_lengkap AS claimant, f.item_name, f.status AS item_status
    FROM claims c
    JOIN users u ON c.claimant_user_id = u.id
    JOIN found_items f ON c.found_item_id = f.id
    $where ORDER BY c.created_at DESC");
$stmt->execute($params);
$claims = $stmt->fetchAll();

$page_title = 'Manajemen Klaim';
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
            <h2>Manajemen Klaim</h2>
            <div class="btn-group mb-3">
                <a href="?filter=pending" class="btn btn-outline-primary <?= $filter=='pending'?'active':'' ?>">Pending</a>
                <a href="?filter=disetujui" class="btn btn-outline-primary <?= $filter=='disetujui'?'active':'' ?>">Disetujui</a>
                <a href="?filter=ditolak" class="btn btn-outline-primary <?= $filter=='ditolak'?'active':'' ?>">Ditolak</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white">
                    <thead><tr><th>ID</th><th>Pengklaim</th><th>Barang</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($claims as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['claimant']) ?></td>
                            <td><?= htmlspecialchars($c['item_name']) ?></td>
                            <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                            <td><span class="badge bg-<?= $c['status']=='pending'?'warning':($c['status']=='disetujui'?'success':'danger') ?>"><?= $c['status'] ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailClaim<?= $c['id'] ?>">Detail</button>
                                <?php if ($c['status'] == 'pending'): ?>
                                    <a href="/Nemu.id/process/admin/proses-klaim.php?id=<?= $c['id'] ?>&action=approve" class="btn btn-sm btn-success">Setujui</a>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectClaim<?= $c['id'] ?>">Tolak</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailClaim<?= $c['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg"><div class="modal-content">
                                <div class="modal-header"><h5>Detail Klaim #<?= $c['id'] ?></h5></div>
                                <div class="modal-body">
                                    <p><strong>Pengklaim:</strong> <?= htmlspecialchars($c['claimant']) ?></p>
                                    <p><strong>Barang:</strong> <?= htmlspecialchars($c['item_name']) ?></p>
                                    <p><strong>Alasan:</strong> <?= nl2br(htmlspecialchars($c['claim_reason'])) ?></p>
                                    <p><strong>Bukti:</strong><br><img src="/Nemu.id/assets/uploads/evidence/<?= htmlspecialchars($c['evidence_photo']) ?>" class="img-fluid" style="max-height:300px;"></p>
                                    <?php if ($c['admin_note']): ?><p><strong>Catatan Admin:</strong> <?= htmlspecialchars($c['admin_note']) ?></p><?php endif; ?>
                                </div>
                            </div></div>
                        </div>
                        <!-- Modal Tolak -->
                        <div class="modal fade" id="rejectClaim<?= $c['id'] ?>" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="/Nemu.id/process/admin/proses-klaim.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <div class="modal-header"><h5>Tolak Klaim</h5></div>
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
