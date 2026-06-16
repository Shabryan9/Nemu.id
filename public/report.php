<?php 
// Pastikan variabel ini tetap ada agar menu di header aktif
$active_page = 'laporan'; 
require_once __DIR__ . '/../includes/header_user.php'; 
?>

<div class="container my-4">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <!-- Sesuaikan link ke folder user/dashboard.php -->
            <li class="breadcrumb-item"><a href="../user/dashboard.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lapor Barang Hilang</li>
        </ol>
    </nav>

    <!-- Judul Halaman -->
    <h2 class="fw-bold mb-1">Laporkan Barang Hilang</h2>
    <p class="text-muted mb-4">Berikan detail lengkap untuk membantu komunitas menemukan barang Anda.</p>
    
    <!-- ... sisa form kamu tetap di sini ... -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Barang Hilang - Nemu.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-area { border: 2px dashed #ccc; padding: 40px; text-align: center; border-radius: 10px; background: #f6f4f4; cursor: pointer; }
        .tips-box { background-color: #1E3A8A; color: #ffffff; padding: 20px; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="fw-bold mb-1">Laporkan Barang Hilang</h2>
    <p class="text-muted mb-4">Berikan detail lengkap untuk membantu komunitas menemukan barang Anda.</p>

    <div class="row">
        <div class="col-lg-8">
            <form action="proses_lapor.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="kategori" required>
                            <option selected disabled>Pilih Kategori</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="buku">Buku/Alat Tulis</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" placeholder="Contoh: Laptop Lenovo Hitam" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="4" placeholder="Jelaskan ciri khusus, casing, atau tanda pengenal lainnya..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lokasi Terakhir</label>
                        <input type="text" class="form-control" name="lokasi" placeholder="Gedung C, Lantai 2">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal & Waktu</label>
                        <input type="date" class="form-control" name="tanggal">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Photo Upload</label>
                    <div class="upload-area">
                        <p>Klik untuk unggah foto<br><small class="text-muted">PNG, JPG up to 5MB (Maksimal 3 foto)</small></p>
                        <input type="file" name="foto" class="d-none" id="fotoInput">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Kirim Laporan</button>
            </form>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">STATUS AWAL</h6>
                    <span class="badge bg-danger-subtle text-danger border border-danger">● Dilaporkan Hilang</span>
                    <p class="small text-muted mt-2">Laporan Anda akan segera terlihat di katalog publik setelah verifikasi admin kampus.</p>
                </div>
            </div>

            <div class="tips-box shadow-sm">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.upload-area').onclick = () => document.getElementById('fotoInput').click();
</script>
</body>
</html>



<?php 
require_once __DIR__ . '/../includes/footer.php'; 
?>