<?php
$active_page = 'dashboard';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
$pending_temuan = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'pending'")->fetchColumn();
$pending_klaim  = $pdo->query("SELECT COUNT(*) FROM claims WHERE status = 'pending'")->fetchColumn();
$dikembalikan   = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'dikembalikan'")->fetchColumn();
$total_user     = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Aktivitas terbaru (5 klaim terbaru)
$recent = $pdo->query("SELECT c.id, c.created_at, u.nama_lengkap AS claimant, f.item_name 
                       FROM claims c 
                       JOIN users u ON c.claimant_user_id = u.id 
                       JOIN found_items f ON c.found_item_id = f.id 
                       ORDER BY c.created_at DESC LIMIT 5")->fetchAll();

$page_title = 'Admin Dashboard';
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
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>
            <!-- Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body"><h5 class="card-title"><?= $pending_temuan ?></h5><p class="card-text">Temuan Pending</p></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body"><h5 class="card-title"><?= $pending_klaim ?></h5><p class="card-text">Klaim Pending</p></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body"><h5 class="card-title"><?= $dikembalikan ?></h5><p class="card-text">Dikembalikan</p></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body"><h5 class="card-title"><?= $total_user ?></h5><p class="card-text">Total User</p></div>
                    </div>
                </div>
            </div>
            <!-- Aktivitas Terbaru -->
            <h4>Aktivitas Klaim Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead><tr><th>ID</th><th>Pengklaim</th><th>Barang</th><th>Tanggal</th></tr></thead>
                    <tbody>
                        <?php foreach ($recent as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['claimant']) ?></td>
                            <td><?= htmlspecialchars($r['item_name']) ?></td>
                            <td><?= date('d M H:i', strtotime($r['created_at'])) ?></td>
                        </tr>
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