<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Penolakan dengan alasan
    $id = $_POST['id'] ?? 0;
    $action = $_POST['action'] ?? '';
    $admin_note = $_POST['admin_note'] ?? '';

    if ($action === 'reject' && $id) {
        $stmt = $pdo->prepare("UPDATE found_items SET status = 'ditolak_admin', verif_admin_id = ? WHERE id = ?");
        $stmt->execute([currentUserId(), $id]);
        // Notifikasi ke penemu
        $item = $pdo->prepare("SELECT finder_user_id, item_name FROM found_items WHERE id = ?");
        $item->execute([$id]);
        $item = $item->fetch();
        if ($item) {
            $notif = $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
            $notif->execute([$item['finder_user_id'], 'Laporan temuan Anda ditolak: ' . $admin_note, '/Nemu.id/user/dashboard.php']);
        }
        $_SESSION['flash_success'] = 'Laporan ditolak.';
        header('Location: /Nemu.id/admin/temuan.php');
        exit;
    }
} else {
    // GET untuk verifikasi
    $id = $_GET['id'] ?? 0;
    $action = $_GET['action'] ?? '';
    if ($action === 'verify' && $id) {
        $stmt = $pdo->prepare("UPDATE found_items SET status = 'tersedia', verif_admin_id = ?, verified_at = NOW() WHERE id = ?");
        $stmt->execute([currentUserId(), $id]);
        // Notifikasi
        $item = $pdo->prepare("SELECT finder_user_id, item_name FROM found_items WHERE id = ?");
        $item->execute([$id]);
        $item = $item->fetch();
        if ($item) {
            $notif = $pdo->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
            $notif->execute([$item['finder_user_id'], 'Laporan temuan Anda telah diverifikasi.', '/Nemu.id/user/dashboard.php']);
        }
        $_SESSION['flash_success'] = 'Laporan diverifikasi.';
        header('Location: /Nemu.id/admin/temuan.php');
        exit;
    }
}
header('Location: /Nemu.id/admin/temuan.php');
exit;