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

// Katalog terbaru (barang temuan & hilang milik user lain)
$catalog_query = $pdo->prepare("
    (SELECT
        f.id, f.item_name, f.description, f.photo, f.found_location AS location, f.found_datetime AS item_date, f.created_at,
        c.name AS category_name, 'found' AS item_type
    FROM found_items f
    LEFT JOIN categories c ON f.category_id = c.id
    WHERE f.status = 'tersedia' AND f.finder_user_id <> ?)
    UNION ALL
    (SELECT
        l.id, l.item_name, l.description, l.photo, l.last_location AS location, l.lost_datetime AS item_date, l.created_at,
        c.name AS category_name, 'lost' AS item_type
    FROM lost_items l
    LEFT JOIN categories c ON l.category_id = c.id
    WHERE l.status = 'hilang' AND l.user_id <> ?)
    ORDER BY created_at DESC
    LIMIT 3
");
$catalog_query->execute([$user_id, $user_id]);
$catalog = $catalog_query->fetchAll();

// Ambil flash message dari session jika ada
$success = $_SESSION['flash_success'] ?? null;
$error   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$page_title = 'Dashboard - ' . htmlspecialchars($user_name);
$active_page = 'dashboard';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <!-- Flash message -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

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
    <h4 class="mb-3">Katalog Barang Temuan & Hilang Terkini</h4>
    <div class="row g-3">
        <?php if (empty($catalog)): ?>
            <div class="col">
                <div class="card p-4 text-center">
                    <p class="mb-0">Belum ada barang temuan atau hilang yang bisa ditampilkan.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php foreach ($catalog as $item): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card item-card h-100">
                <div class="position-relative">
                    <?php if ($item['photo']): ?>
                        <img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['item_name']) ?>">
                    <?php else: ?>
                        <div class="bg-secondary text-white text-center py-5"><i class="bi bi-image" style="font-size:3rem;"></i></div>
                    <?php endif; ?>

                    <?php if ($item['item_type'] === 'found'): ?>
                        <span class="badge bg-success badge-available">Tersedia</span>
                    <?php else: ?>
                        <span class="badge bg-danger badge-available">Hilang</span>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted text-uppercase"><?= htmlspecialchars($item['category_name'] ?? 'Umum') ?></small>
                    <h5 class="card-title mt-1"><?= htmlspecialchars($item['item_name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($item['location']) ?> • <?= date('d M Y', strtotime($item['item_date'])) ?></p>
                    
                    <!-- Deskripsi HANYA untuk barang hilang -->
                    <?php if ($item['item_type'] === 'lost'): ?>
                        <p class="card-text small"><?= htmlspecialchars(mb_strimwidth($item['description'] ?? '', 0, 60, '...')) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($item['item_type'] === 'found'): ?>
                        <a href="/Nemu.id/user/detail-temuan.php?id=<?= $item['id'] ?>" class="btn btn-primary mt-auto">Lihat Detail</a>
                    <?php else: ?>
                        <a href="/Nemu.id/user/lapor-temuan.php" class="btn btn-info mt-auto">Menemukan ini? Lapor</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>