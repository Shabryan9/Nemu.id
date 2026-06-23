<?php
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

$pdo = getDB();
$nama = trim($_POST['nama_lengkap'] ?? '');
$nim = trim($_POST['nim_nip'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';

function kembaliDenganError(string $pesan) {
    $_SESSION['flash_error'] = $pesan;
    header('Location: /Nemu.id/admin/user.php');
    exit;
}

if (empty($nama) || empty($nim) || empty($email) || empty($password)) {
    kembaliDenganError('Semua field wajib diisi.');
}

// Siapkan query untuk mengecek apakah NIM/NIP atau email sudah dipakai user lain.
$cekDuplikat = $pdo->prepare("SELECT nim_nip, email FROM users WHERE nim_nip = ? OR email = ? LIMIT 1");
// Jalankan pengecekan memakai input NIM/NIP dan email dari form.
$cekDuplikat->execute([$nim, $email]);
// Ambil satu data user yang cocok, jika ada.
$userDuplikat = $cekDuplikat->fetch();

// Jika query menemukan data dengan NIM/NIP atau email yang sama, hentikan proses simpan.
if ($userDuplikat) {
    // Tentukan pesan error sesuai field yang duplikat agar user tahu bagian mana yang harus diisi ulang.
    $fieldDuplikat = $userDuplikat['nim_nip'] === $nim ? 'NIM/NIP' : 'Email';
    // Kirim notifikasi error ke halaman user dan jangan lanjut ke INSERT.
    kembaliDenganError($fieldDuplikat . ' sudah terdaftar. Silakan isi ulang dengan data yang berbeda.');
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, nim_nip, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$nama, $nim, $email, $hash, $role]);
    $_SESSION['flash_success'] = 'User berhasil ditambahkan.';
} catch (PDOException $e) {
    // Kode 23000 atau error code 1062 menandakan data duplikat (Unique Constraint) di MySQL
    if ($e->getCode() == 23000 || strpos($e->getMessage(), '1062') !== false) {
        $_SESSION['flash_error'] = 'Gagal: NIM/NIP atau Email sudah terdaftar di sistem!';
    } else {
        $_SESSION['flash_error'] = 'Gagal menambahkan user: ' . $e->getMessage();
    }
}
header('Location: /Nemu.id/admin/user.php');
exit;
