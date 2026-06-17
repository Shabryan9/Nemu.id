<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pdo = getDB();
// Menghitung jumlah pending untuk badge
$pending_temuan = $pdo->query("SELECT COUNT(*) FROM found_items WHERE status = 'pending'")->fetchColumn();
$pending_klaim  = $pdo->query("SELECT COUNT(*) FROM claims WHERE status = 'pending'")->fetchColumn();
?>
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-navy sidebar collapse show">
    <div class="position-sticky pt-3">
        <div class="px-3 mb-4">
            <h5 class="text-white">Admin Panel</h5>
            <small class="text-white-50"><?= htmlspecialchars(currentUserName()) ?></small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= $active_page == 'dashboard' ? 'active' : '' ?>" href="/Nemu.id/admin/index.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $active_page == 'temuan' ? 'active' : '' ?>" href="/Nemu.id/admin/temuan.php">
                    <i class="bi bi-inbox me-2"></i> Verifikasi Temuan
                    <?php if ($pending_temuan > 0): ?>
                        <span class="badge bg-warning text-dark ms-2"><?= $pending_temuan ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $active_page == 'klaim' ? 'active' : '' ?>" href="/Nemu.id/admin/klaim.php">
                    <i class="bi bi-journal-check me-2"></i> Manajemen Klaim
                    <?php if ($pending_klaim > 0): ?>
                        <span class="badge bg-warning text-dark ms-2"><?= $pending_klaim ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $active_page == 'users' ? 'active' : '' ?>" href="/Nemu.id/admin/users.php">
                    <i class="bi bi-people me-2"></i> Manajemen User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $active_page == 'kategori' ? 'active' : '' ?>" href="/Nemu.id/admin/kategori.php">
                    <i class="bi bi-tags me-2"></i> Kategori
                </a>
            </li>
        </ul>
        <hr class="text-white-50">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="/Nemu.id/process/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>