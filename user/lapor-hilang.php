<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireUser();

$pdo = getDB();
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$page_title = 'Lapor Barang Hilang';
$active_page = 'lapor-hilang';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/Nemu.id/user/dashboard.php">Beranda</a></li>
            <li class="breadcrumb-item active">Lapor Barang Hilang</li>
        </ol>
    </nav>
    <h2 class="fw-bold mb-1">Laporkan Barang Hilang</h2>
    <p class="text-muted mb-4">Berikan detail lengkap untuk membantu komunitas menemukan barang Anda.</p>

    <div class="row">
        <div class="col-lg-8">
            <form action="/Nemu.id/process/lapor-hilang.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 card-ui rounded">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category_id" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="item_name" placeholder="Contoh: Laptop Lenovo Hitam" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Jelaskan ciri khusus, casing, atau tanda pengenal lainnya..." required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi Terakhir</label>
                        <input type="text" class="form-control" name="last_location" placeholder="Gedung C, Lantai 2" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal & Waktu Kehilangan</label>
                        <input type="datetime-local" class="form-control" name="lost_datetime" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Foto (opsional)</label>
                    <input type="file" class="form-control" name="photo" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success w-100">Kirim Laporan</button>
            </form>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card card-ui mb-3">
                <div class="card-body">
                    <h6 class="text-muted">STATUS AWAL</h6>
                    <span class="badge bg-danger-subtle text-danger border border-danger">● Dilaporkan Hilang</span>
                    <p class="small text-muted mt-2">Laporan Anda akan segera terlihat di katalog publik setelah verifikasi admin kampus.</p>
                </div>
            </div>
            <div class="tips-box card-ui bg-navy text-white p-3">
                <h5>Tips Pencarian</h5>
                <ul class="small ps-3">
                    <li>Deskripsikan ciri khas seperti stiker atau goresan.</li>
                    <li>Lampirkan foto asli barang jika tersedia.</li>
                    <li>Cek berkala menu 'Dashboard' untuk update status.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
