<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireUser();

$pdo = getDB();
//ambil daftar kategori untuk pilihan laporan temuan.
$categories = dbFetchAll("SELECT * FROM categories ORDER BY name");
$page_title = 'Lapor Barang Temuan';
$active_page = 'lapor-temuan';
include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/Nemu.id/user/dashboard.php">Beranda</a></li>
            <li class="breadcrumb-item active">Lapor Barang Temuan</li>
        </ol>
    </nav>
    <h2 class="fw-bold mb-1">Laporkan Barang Temuan</h2>
    <p class="text-muted mb-4">Bantu pemilik menemukan barangnya dengan melaporkan temuan Anda.</p>

    <div class="row">
        <div class="col-12 col-lg-8">
            <form action="/Nemu.id/process/lapor-temuan.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 card-ui rounded">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category_id" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="item_name" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" rows="4" required></textarea>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Lokasi Ditemukan</label>
                        <input type="text" class="form-control" name="found_location" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Tanggal & Waktu Ditemukan</label>
                        <input type="datetime-local" class="form-control" name="found_datetime" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Foto Barang (wajib)</label>
                    <input type="file" class="form-control" name="photo" accept="image/*" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="anonymousCheck">
                    <label class="form-check-label" for="anonymousCheck">Sembunyikan identitas saya dari publik</label>
                </div>
                <button type="submit" class="btn btn-success w-100">Kirim Laporan</button>
            </form>
        </div>

        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card card-ui mb-3">
                <div class="card-body">
                    <h6 class="text-muted">STATUS AWAL</h6>
                    <span class="badge bg-warning text-dark">● Menunggu Verifikasi</span>
                    <p class="small text-muted mt-2">Laporan Anda akan diverifikasi oleh admin sebelum tampil di katalog.</p>
                </div>
            </div>

            <div class="card card-ui mb-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-shield-lock-fill text-success fs-4 me-2"></i>
                        <h6 class="mb-0">Privasi Terjamin</h6>
                    </div>
                    <p class="small text-muted mb-0">
                        Identitas Anda dilindungi. Pilih opsi <strong>"Sembunyikan identitas"</strong> untuk tetap anonim di mata publik. 
                        Data pribadi hanya dapat diakses oleh admin kampus.
                    </p>
                </div>
            </div>

            <div class="card card-ui">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-2"></i>
                        <h6 class="mb-0">Panduan Pelaporan</h6>
                    </div>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Pastikan foto barang jelas dan terbaca.</li>
                        <li>Deskripsikan ciri khas (warna, merek, goresan, dll).</li>
                        <li>Catat lokasi dan waktu penemuan dengan tepat.</li>
                        <li>Jangan memberikan barang kepada pengklaim sebelum diverifikasi admin.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
