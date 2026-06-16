<?php include __DIR__ . '/../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard - Nemu.id</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css?v=<?= time() ?>"/>
</head>
<body>

<!-- HERO SECTION -->
<section class="hero-section py-5">
    <div class="hero-content">
        <div class="container-lg px-4">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="badge-hero mb-3">
                        <span>🛡️ SISTEM KEAMANAN UNIVERSITAS REUKA</span>
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
<section class="stats-section py-5 ">
    <div class="container-lg px-4">
        <div class="row g-4">
            <!-- Card 1: Berhasil Kembali -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-icon mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5 class="stats-value">95% Berhasil Kembali</h5>
                    <p class="stats-desc">Tingkat keberhasilan pengembalian barang yang tinggi melalui sistem kami.</p>
                </div>
            </div>

            <!-- Card 2: Barang Ditemukan -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-label">BARANG DITEMUKAN</div>
                    <h2 class="stats-number">1,248</h2>
                </div>
            </div>

            <!-- Card 3: Laporan Aktif -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stats-card">
                    <div class="stats-label">LAPORAN AKTIF</div>
                    <h2 class="stats-number">342</h2>
                    <p class="stats-time mt-2">📊 laporan operatif, fitur yang lain</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS SECTION -->
<section class="how-works-section py-5 ">
    <div class="container-lg px-4">
        <div class="text-center mb-5">
            <h2 class="section-title">Pengembalian Mudah</h2>
            <p class="section-desc">Tiga langkah mudah untuk melakukan pencarian</p>
        </div>

        <div class="row g-4">
            <!-- Step 1 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <h5 class="step-title">1. Pelaporan</h5>
                    <p class="step-desc">Laporkan barang hilang Anda lengkap dengan detail, foto, dan perkiraan lokasi di kampus. Sistem kami akan memudahkan Anda.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <h5 class="step-title">2. Menghubungkan</h5>
                    <p class="step-desc">Sistem pencocokan kami akan menghubungkan Anda dengan mereka yang menemukan barang yang dicari Anda di kampus.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                    </div>
                    <h5 class="step-title">3. Pengembalian</h5>
                    <p class="step-desc">Verifikasi kepemilikan melalui keamanan digital Anda. Ambil barang Anda di kantor pusat kami atau di digital Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
