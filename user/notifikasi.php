<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pdo = getDB();
$user_id = currentUserId();

// Tandai semua dibaca jika diminta
if (isset($_GET['mark_read'])) {
    $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);
    header('Location: /Nemu.id/user/notifikasi.php');
    exit;
}

$notifs = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$notifs->execute([$user_id]);
$notifications = $notifs->fetchAll();

$page_title = 'Notifikasi';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Notifikasi</h2>
        <a href="?mark_read=1" class="btn btn-sm btn-outline-secondary">Tandai Semua Dibaca</a>
    </div>
    <?php if (count($notifications) > 0): ?>
        <div class="list-group">
            <?php foreach ($notifications as $n): ?>
                <a href="<?= htmlspecialchars($n['link'] ?? '#') ?>" class="list-group-item list-group-item-action <?= $n['is_read'] ? '' : 'list-group-item-info' ?>">
                    <div class="d-flex w-100 justify-content-between">
                        <p class="mb-1"><?= htmlspecialchars($n['message']) ?></p>
                        <small><?= date('d M H:i', strtotime($n['created_at'])) ?></small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Belum ada notifikasi.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>