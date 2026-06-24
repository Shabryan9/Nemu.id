<?php
require_once __DIR__ . '/../config/connection.php';
$pdo = getDB();

// [AKSI]: Ambil statistik publik dengan prepared statement.
$total_tersedia = dbFetchColumn("SELECT COUNT(*) FROM found_items WHERE status IN ('tersedia','dalam_proses_klaim')");
$laporan_aktif = dbFetchColumn("SELECT COUNT(*) FROM lost_items WHERE status = 'hilang'");
$dikembalikan = dbFetchColumn("SELECT COUNT(*) FROM found_items WHERE status = 'dikembalikan'");
// [AKSI]: Hitung persentase keberhasilan dan hindari pembagian nol.
$total_selesai = dbFetchColumn("SELECT COUNT(*) FROM found_items WHERE status IN ('dikembalikan','ditolak_admin','kadaluarsa')");
$persen_berhasil = $total_selesai > 0 ? round(($dikembalikan / $total_selesai) * 100) : 95;

// [AKSI]: Ambil 3 barang temuan terbaru untuk katalog halaman depan.
$found_items = dbFetchAll("SELECT f.*, c.name AS category_name
                           FROM found_items f
                           LEFT JOIN categories c ON f.category_id = c.id
                           WHERE f.status = 'tersedia'
                           ORDER BY f.created_at DESC LIMIT 3");

$page_title = 'Beranda - Nemu.id';
$body_class = '';
$hide_home_nav = true;
include __DIR__ . '/../includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero-section py-5">
    <div class="hero-content">
        <div class="container-lg px-4">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="badge-hero mb-3">
                        <span> SISTEM KEAMANAN UNIVERSITAS REUKA</span>
                    </div>
                    <h1 class="hero-title mb-3">Platform Pelaporan dan Penemuan UREUKA</h1>
                    <p class="hero-desc mb-4">Temukan barang yang hilang atau laporkan penemuan Anda dengan sistem pelacakan kami.</p>
                    <a href="/Nemu.id/public/login.php" class="btn btn-success btn-lg">
                        <i class="bi bi-search"></i> Cari & Temukan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section py-5">
    <div class="container-lg px-4">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-icon mb-3"><i class="bi bi-check-circle-fill"></i></div>
                    <h5 class="stats-value"><?= $persen_berhasil ?>% Berhasil Kembali</h5>
                    <p class="stats-desc">Tingkat keberhasilan pengembalian barang yang tinggi melalui sistem kami.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-label">BARANG DITEMUKAN</div>
                    <h2 class="stats-number"><?= $total_tersedia ?></h2>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-label">LAPORAN AKTIF</div>
                    <h2 class="stats-number"><?= $laporan_aktif ?></h2>
                    <p class="stats-time mt-2"> laporan operatif, fitur yang lain</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS SECTION -->
<section class="how-works-section py-5">
    <div class="container-lg px-4">
        <div class="text-center mb-5">
            <h2 class="section-title">Pengembalian Mudah</h2>
            <p class="section-desc">Tiga langkah mudah untuk melakukan pencarian</p>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon"><i class="bi bi-megaphone-fill"></i></div>
                    <h5 class="step-title">1. Pelaporan</h5>
                    <p class="step-desc">Laporkan barang hilang Anda lengkap dengan detail, foto, dan perkiraan lokasi di kampus.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <h5 class="step-title">2. Menghubungkan</h5>
                    <p class="step-desc">Sistem pencocokan kami akan menghubungkan Anda dengan mereka yang menemukan barang yang dicari.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                    <h5 class="step-title">3. Pengembalian</h5>
                    <p class="step-desc">Verifikasi kepemilikan melalui keamanan digital Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KATALOG TERBARU -->
<?php if (count($found_items) > 0): ?>
<section class="py-5">
    <div class="container-lg px-4">
        <h3 class="mb-4">Barang Temuan Terbaru</h3>
        <div class="row g-3">
            <?php foreach ($found_items as $item): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card item-card h-100">
                    <div class="position-relative">
                        <?php if ($item['photo']): ?>
                            <img src="/Nemu.id/assets/uploads/items/<?= htmlspecialchars($item['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['item_name']) ?>">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5"><i class="bi bi-image" style="font-size: 3rem;"></i></div>
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
</section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
