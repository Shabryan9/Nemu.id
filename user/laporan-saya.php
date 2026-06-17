<?php
$active_page = 'laporan-saya';
$page_title = 'Laporan Saya - Nemu.id';

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireUser();

// Definisikan variabel filter dengan default dari GET
$type   = $_GET['type'] ?? 'all';
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Include file backend query (akan menghasilkan $items dan $total_items)
include __DIR__ . '/../process/laporan-saya.php';

include __DIR__ . '/../includes/header_user.php';
?>

<div class="container py-4">
    <h2 class="fw-bold mb-3">Laporan Saya</h2>
    
    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4 bg-white p-3 card-ui">
        <div class="col-md-3">
            <label class="form-label">Jenis Laporan</label>
            <select name="type" class="form-select">
                <option value="all" <?= $type == 'all' ? 'selected' : '' ?>>Semua</option>
                <option value="lost" <?= $type == 'lost' ? 'selected' : '' ?>>Barang Hilang</option>
                <option value="found" <?= $type == 'found' ? 'selected' : '' ?>>Barang Temuan</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="" <?= $status == '' ? 'selected' : '' ?>>Semua Status</option>
                <!-- Status lost_items -->
                <optgroup label="Hilang">
                    <option value="hilang" <?= $status == 'hilang' ? 'selected' : '' ?>>Hilang</option>
                    <option value="ditemukan_sendiri" <?= $status == 'ditemukan_sendiri' ? 'selected' : '' ?>>Ditemukan Sendiri</option>
                    <option value="ditutup_admin" <?= $status == 'ditutup_admin' ? 'selected' : '' ?>>Ditutup Admin</option>
                </optgroup>
                <!-- Status found_items -->
                <optgroup label="Temuan">
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="tersedia" <?= $status == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="dalam_proses_klaim" <?= $status == 'dalam_proses_klaim' ? 'selected' : '' ?>>Dalam Proses Klaim</option>
                    <option value="dikembalikan" <?= $status == 'dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                    <option value="ditolak_admin" <?= $status == 'ditolak_admin' ? 'selected' : '' ?>>Ditolak Admin</option>
                    <option value="kadaluarsa" <?= $status == 'kadaluarsa' ? 'selected' : '' ?>>Kadaluarsa</option>
                </optgroup>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Cari Nama Barang</label>
            <input type="text" name="search" class="form-control" placeholder="Kata kunci..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-navy w-100">Filter</button>
        </div>
    </form>

    <!-- Hasil -->
    <?php if (empty($items)): ?>
        <div class="alert alert-info">Tidak ada laporan ditemukan.</div>
    <?php else: ?>
        <p class="text-muted">Menampilkan <?= $total_items ?> laporan</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['type'] === 'lost'): ?>
                                <span class="badge bg-danger">Hilang</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Temuan</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['location']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($item['event_date'])) ?></td>
                        <td>
                            <?php
                            $status = $item['status'];
                            $badgeClass = [
                                'hilang' => 'bg-warning text-dark',
                                'ditemukan_sendiri' => 'bg-secondary',
                                'ditutup_admin' => 'bg-dark',
                                'pending' => 'bg-warning text-dark',
                                'tersedia' => 'bg-success',
                                'dalam_proses_klaim' => 'bg-info',
                                'dikembalikan' => 'bg-primary',
                                'ditolak_admin' => 'bg-danger',
                                'kadaluarsa' => 'bg-light text-dark'
                            ];
                            $badge = $badgeClass[$status] ?? 'bg-secondary';
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                        <td>
                            <?php if ($item['type'] === 'found'): ?>
                                <a href="/Nemu.id/user/detail-temuan.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                            <?php else: ?>
                                <!-- Bisa tambahkan aksi untuk laporan hilang, misal tutup -->
                                <?php if ($item['status'] === 'hilang'): ?>
                                    <a href="/Nemu.id/process/tutup-laporan.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tutup laporan ini?')">Tutup</a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>