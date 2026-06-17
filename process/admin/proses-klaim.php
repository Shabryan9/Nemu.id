<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $action = $_POST['action'] ?? '';
    $admin_note = $_POST['admin_note'] ?? '';

    if ($action === 'reject' && $id) {
        $claim = $pdo->prepare("SELECT * FROM claims WHERE id = ?");
        $claim->execute([$id]);
        $claim = $claim->fetch();
        if ($claim) {
            $pdo->prepare("UPDATE claims SET status = 'ditolak', admin_id = ?, admin_note = ?, processed_at = NOW() WHERE id = ?")
                ->execute([currentUserId(), $admin_note, $id]);
            $pdo->prepare("UPDATE found_items SET status = 'tersedia' WHERE id = ?")
                ->execute([$claim['found_item_id']]);
            $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")
                ->execute([$claim['claimant_user_id'], 'Klaim Anda ditolak: ' . $admin_note, '/Nemu.id/user/dashboard.php']);
        }
        $_SESSION['flash_success'] = 'Klaim ditolak.';
        header('Location: /Nemu.id/admin/klaim.php');
        exit;
    }
} else {
    $id = $_GET['id'] ?? 0;
    $action = $_GET['action'] ?? '';
    if ($action === 'approve' && $id) {
        $claim = $pdo->prepare("SELECT * FROM claims WHERE id = ?");
        $claim->execute([$id]);
        $claim = $claim->fetch();
        if ($claim) {
            $pdo->prepare("UPDATE claims SET status = 'disetujui', admin_id = ?, processed_at = NOW() WHERE id = ?")
                ->execute([currentUserId(), $id]);
            $pdo->prepare("UPDATE found_items SET status = 'dikembalikan' WHERE id = ?")
                ->execute([$claim['found_item_id']]);
            $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)")
                ->execute([$claim['claimant_user_id'], 'Klaim Anda disetujui.', '/Nemu.id/user/dashboard.php']);
        }
        $_SESSION['flash_success'] = 'Klaim disetujui.';
        header('Location: /Nemu.id/admin/klaim.php');
        exit;
    }
}
header('Location: /Nemu.id/admin/klaim.php');
exit;