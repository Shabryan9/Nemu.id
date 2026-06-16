<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pdo = getDB();
$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$results = [];
if (!empty($keyword) || !empty($category) || !empty($status)) {
    $sql = "SELECT 'lost' AS type, l.id, l.item_name, l.description, l.last_location AS location, l.lost_datetime AS event_date, l.photo, c.name AS category_name, l.status
            FROM lost_items l
            LEFT JOIN categories c ON l.category_id = c.id
            WHERE l.status = 'hilang'";
    $params = [];
    if (!empty($keyword)) {
        $sql .= " AND (l.item_name LIKE ? OR l.description LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }
    if (!empty($category)) {
        $sql .= " AND l.category_id = ?";
        $params[] = $category;
    }
    $sql .= " UNION ALL
              SELECT 'found' AS type, f.id, f.item_name, f.description, f.found_location AS location, f.found_datetime AS event_date, f.photo, c.name AS category_name, f.status
              FROM found_items f
              LEFT JOIN categories c ON f.category_id = c.id
              WHERE f.status = 'tersedia'";
    if (!empty($keyword)) {
        $sql .= " AND (f.item_name LIKE ? OR f.description LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }
    if (!empty($category)) {
        $sql .= " AND f.category_id = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY event_date DESC LIMIT 20";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$page_title = 'Pencarian';
$active_page = 'cari';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <h2 class="mb-4">Cari Barang Hilang atau Temuan</h2>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="keyword" class="form-control" placeholder="Kata kunci..." value="<?= htmlspecialchars($keyword) ?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="hilang" <?= $status == 'hilang' ? 'selected' : '' ?>>Hilang</option>
                <option value="tersedia" <?= $status == 'tersedia' ? 'selected' : '' ?>>Tersedia (Temuan)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-navy w-100">Cari</button>
        </div>
    </form>

    <?php if (!empty($results)): ?>
        <div class="row g-3">
            <?php foreach ($results as $item): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card item-card h-100">
                    <div class="position-relative">
                        <?php if ($item['photo']): ?>
                            <img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                        <span class="badge bg-<?= $item['type'] == 'lost' ? 'warning' : 'success' ?> badge-available"><?= $item['type'] == 'lost' ? 'Hilang' : 'Tersedia' ?></span>
                    </div>
                    <div class="card-body">
                        <small class="text-muted"><?= htmlspecialchars($item['category_name'] ?? 'Umum') ?></small>
                        <h5 class="card-title"><?= htmlspecialchars($item['item_name']) ?></h5>
                        <p class="card-text small text-muted"><?= htmlspecialchars($item['location']) ?> • <?= date('d M Y', strtotime($item['event_date'])) ?></p>
                        <?php if ($item['type'] == 'found'): ?>
                            <a href="/Nemu.id/user/detail-temuan.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Detail</a>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['keyword']) || isset($_GET['category']))) : ?>
        <div class="alert alert-info">Tidak ada hasil ditemukan.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>