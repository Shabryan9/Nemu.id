<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$pdo = getDB();

$item = $pdo->prepare("SELECT f.*, c.name AS category_name, u.nama_lengkap AS finder_name
                       FROM found_items f
                       LEFT JOIN categories c ON f.category_id = c.id
                       LEFT JOIN users u ON f.finder_user_id = u.id
                       WHERE f.id = ?");
$item->execute([$id]);
$item = $item->fetch();

if (!$item) {
    echo "Barang tidak ditemukan.";
    exit;
}

// Cek apakah user sudah mengajukan klaim sebelumnya
$existing_claim = $pdo->prepare("SELECT id FROM claims WHERE found_item_id = ? AND claimant_user_id = ?");
$existing_claim->execute([$id, currentUserId()]);
$already_claimed = $existing_claim->fetchColumn();

$page_title = 'Detail Temuan';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/Nemu.id/user/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Detail Temuan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <?php if ($item['photo']): ?>
                <img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="img-fluid rounded">
            <?php else: ?>
                <div class="bg-secondary text-white text-center py-5"><i class="bi bi-image" style="font-size:5rem;"></i></div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($item['item_name']) ?></h2>
            <span class="badge bg-<?= $item['status'] == 'tersedia' ? 'success' : 'warning' ?> mb-3"><?= $item['status'] ?></span>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($item['category_name'] ?? '-') ?></p>
            <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($item['description'])) ?></p>
            <p><strong>Lokasi Ditemukan:</strong> <?= htmlspecialchars($item['found_location']) ?></p>
            <p><strong>Tanggal Ditemukan:</strong> <?= date('d M Y H:i', strtotime($item['found_datetime'])) ?></p>
            <?php if (!$item['is_anonymous']): ?>
                <p><strong>Penemu:</strong> <?= htmlspecialchars($item['finder_name']) ?></p>
            <?php else: ?>
                <p><em>Penemu anonim</em></p>
            <?php endif; ?>

            <?php if ($item['status'] == 'tersedia' && !$already_claimed): ?>
                <hr>
                <h5>Ajukan Klaim</h5>
                <form action="/Nemu.id/process/klaim.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="found_item_id" value="<?= $item['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Alasan Klaim (ciri khusus)</label>
                        <textarea class="form-control" name="claim_reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Kepemilikan (foto)</label>
                        <input type="file" class="form-control" name="evidence_photo" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajukan Klaim</button>
                </form>
            <?php elseif ($already_claimed): ?>
                <div class="alert alert-info">Anda sudah mengajukan klaim untuk barang ini.</div>
            <?php else: ?>
                <div class="alert alert-secondary">Barang ini sedang dalam proses klaim atau sudah dikembalikan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>