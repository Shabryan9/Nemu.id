    <?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$id = $_GET['id'] ?? 0;
$pdo = getDB();
$user = $pdo->prepare("SELECT is_blocked FROM users WHERE id = ?");
$user->execute([$id]);
$user = $user->fetch();
if ($user) {
    $new = $user['is_blocked'] ? 0 : 1;
    $pdo->prepare("UPDATE users SET is_blocked = ? WHERE id = ?")->execute([$new, $id]);
}
header('Location: /Nemu.id/admin/users.php');
exit;