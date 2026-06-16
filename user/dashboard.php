<?php
require_once __DIR__ . '/../config/connection.php'; // Ensure session is started and DB is available
require_once __DIR__ . '/../includes/auth.php';

requireLogin(); // Pastikan sudah login

$pdo = getDB();
$user_id = currentUserId();
$user_name = currentUserName();

// Ambil laporan hilang user
$lost = $pdo->prepare("SELECT * FROM lost_items WHERE user_id = ? ORDER BY created_at DESC");
$lost->execute([$user_id]);
$lost_items = $lost->fetchAll();

// Ambil laporan temuan user
$found = $pdo->prepare("SELECT * FROM found_items WHERE finder_user_id = ? ORDER BY created_at DESC");
$found->execute([$user_id]);
$found_items = $found->fetchAll();

// Ambil klaim yang diajukan user
$claims = $pdo->prepare(
    "SELECT c.*, f.item_name AS found_item_name, f.status AS item_status 
     FROM claims c 
     JOIN found_items f ON c.found_item_id = f.id 
     WHERE c.claimant_user_id = ? 
     ORDER BY c.created_at DESC"
);
$claims->execute([$user_id]);
$user_claims = $claims->fetchAll();

// Ambil notifikasi user
$notifs = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$notifs->execute([$user_id]);
$notifications = $notifs->fetchAll();

// Notifikasi belum dibaca
$unread = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$unread->execute([$user_id]);
$unread_count = $unread->fetchColumn();

$page_title = 'Dashboard - ' . htmlspecialchars($user_name);
include __DIR__ . '/../includes/header_user.php';
?>
  <div class="container py-4">
    <div class="d-flex justify-content-end mb-3">
    </div>

    <!-- Hero -->
    <div class="hero p-4 p-md-5 mb-4 d-flex flex-column flex-md-row align-items-start justify-content-between">
      <div>
        <h1 class="hero-title">Selamat Datang, <?= htmlspecialchars($user_name) ?></h1>
        <p class="hero-sub">Katalog Reuka terpadu untuk integritas aset kampus. Temukan kembali barang Anda dengan sistem pelacakan berbasis kepercayaan.</p>
        <div class="d-flex gap-2 mt-3">
          <a href="../public/report.php" class="btn btn-success btn-lg">Lapor Barang Hilang</a>
          <a href="#" class="btn btn-success btn-lg">Lapor Temuan Barang</a>
        </div>
      </div>
      <div class="mt-3 mt-md-0 text-end">
        <!-- subtle shield icon -->
        <svg width="84" height="84" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="hero-icon">
          <path d="M12 2l7 3v5c0 5-3.5 9.8-7 12-3.5-2.2-7-7-7-12V5l7-3z" fill="rgba(255,255,255,0.12)"/>
        </svg>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-8">
        <div class="card p-3">
          <h5>Panduan</h5>
          <p class="mb-0 text-muted">Platform digital resmi Universitas untuk membantu pencarian dan penemuan barang hilang di lingkungan kampus. Sistem ini memfasilitasi seluruh civitas akademika untuk melaporkan barang hilang maupun barang yang ditemukan, kemudian melakukan pencocokan data secara otomatis guna mempercepat proses pengembalian kepada pemiliknya.</p>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="stats-card p-3 text-white h-100 d-flex flex-column justify-content-center align-items-start">
          <div class="small text-uppercase opacity-75">Statistik Hari Ini</div>
          <div class="display-6 fw-bold">124</div>
          <div class="mt-2 small">Barang berhasil dikembalikan minggu ini</div>
          <div class="mt-3 w-100 d-flex justify-content-between align-items-center opacity-75 small">
            <div>Indeks Kepercayaan</div>
            <div>98%</div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Katalog Barang Temuan Terkini</h4>
      <a href="#" class="small text-decoration-none">Lihat Semua Katalog →</a>
    </div>

    <div class="row g-3">
      <?php
      $items = [
        [
          'img' => 'https://picsum.photos/seed/1/800/450',
          'category' => 'Perangkat Elektronik',
          'title' => "MacBook Pro 14' Space Gray",
          'meta' => 'Gedung Perpustakaan Pusat, Lt. 2 • Ditemukan: 24 Okt 2023'
        ],
        [
          'img' => 'https://picsum.photos/seed/2/800/450',
          'category' => 'Aksesori Pribadi',
          'title' => 'Dompet Kulit Coklat',
          'meta' => 'Kantin Fakultas Teknik • Ditemukan: 23 Okt 2023'
        ],
        [
          'img' => 'https://picsum.photos/seed/3/800/450',
          'category' => 'Kendaraan & Kunci',
          'title' => 'Gantungan Kunci Honda',
          'meta' => 'Parkir Barat (Samping ATM) • Ditemukan: 22 Okt 2023'
        ],
      ];

      foreach ($items as $it):
      ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card item-card h-100">
          <div class="position-relative">
            <img src="<?= $it['img'] ?>" class="card-img-top" alt="<?= htmlspecialchars($it['title']) ?>">
            <span class="badge bg-success badge-available">Tersedia</span>
          </div>
          <div class="card-body d-flex flex-column">
            <small class="text-muted text-uppercase"><?= htmlspecialchars($it['category']) ?></small>
            <h5 class="card-title mt-1"><?= htmlspecialchars($it['title']) ?></h5>
            <p class="card-text text-muted small mb-3"><?= htmlspecialchars($it['meta']) ?></p>
            <div class="mt-auto">
              <a href="#" class="btn btn-primary w-100">Klaim Barang Ini</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
<?php include __DIR__ . '/../includes/footer.php'; ?>