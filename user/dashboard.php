<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireUser();

$pdo = getDB();
$user_id = currentUserId();
$user_name = currentUserName();

// Laporan hilang user
$lost = $pdo->prepare("SELECT * FROM lost_items WHERE user_id = ? ORDER BY created_at DESC");
$lost->execute([$user_id]);
$lost_items = $lost->fetchAll();

// Laporan temuan user
$found = $pdo->prepare("SELECT * FROM found_items WHERE finder_user_id = ? ORDER BY created_at DESC");
$found->execute([$user_id]);
$found_items = $found->fetchAll();

// Klaim user
$claims = $pdo->prepare("SELECT c.*, f.item_name AS found_item_name
                         FROM claims c
                         JOIN found_items f ON c.found_item_id = f.id
                         WHERE c.claimant_user_id = ?
                         ORDER BY c.created_at DESC");
$claims->execute([$user_id]);
$user_claims = $claims->fetchAll();

// Katalog terbaru (barang temuan tersedia)
$catalog = $pdo->query("SELECT f.*, c.name AS category_name
                        FROM found_items f
                        LEFT JOIN categories c ON f.category_id = c.id
                        WHERE f.status = 'tersedia'
                        ORDER BY f.created_at DESC LIMIT 3")->fetchAll();

$page_title = 'Dashboard - ' . htmlspecialchars($user_name);
$active_page = 'dashboard';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <div class="hero p-4 p-md-5 mb-4 d-flex flex-column flex-md-row align-items-start justify-content-between">
        <div>
            <h1 class="hero-title">Selamat Datang, <?= htmlspecialchars($user_name) ?></h1>
            <p class="hero-sub">Katalog Reuka terpadu untuk integritas aset kampus.</p>
            <div class="d-flex gap-2 mt-3">
                <a href="/Nemu.id/user/lapor-hilang.php" class="btn btn-success btn-lg">Lapor Barang Hilang</a>
                <a href="/Nemu.id/user/lapor-temuan.php" class="btn btn-outline-light btn-lg">Lapor Temuan Barang</a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3"><h5>Laporan Hilang</h5><h2><?= count($lost_items) ?></h2></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Laporan Temuan</h5><h2><?= count($found_items) ?></h2></div>
        </div>
        <div class="col-md-4">
            <div class="card p-3"><h5>Klaim Diajukan</h5><h2><?= count($user_claims) ?></h2></div>
        </div>
    </div>

    <!-- Katalog -->
    <h4 class="mb-3">Katalog Barang Temuan Terkini</h4>
    <div class="row g-3">
        <?php foreach ($catalog as $item): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card item-card h-100">
                <div class="position-relative">
                    <?php if ($item['photo']): ?>
                        <img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['item_name']) ?>">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center py-5"><i class="bi bi-image" style="font-size:3rem;"></i></div>
                    <?php endif; ?>
                    <span class="badge bg-success badge-available">Tersedia</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted text-uppercase"><?= htmlspecialchars($item['category_name'] ?? 'Umum') ?></small>
                    <h5 class="card-title mt-1"><?= htmlspecialchars($item['item_name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($item['found_location']) ?> • <?= date('d M Y', strtotime($item['found_datetime'])) ?></p>
                    <a href="/Nemu.id/user/detail-temuan.php?id=<?= $item['id'] ?>" class="btn btn-primary mt-auto">Lihat Detail</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>