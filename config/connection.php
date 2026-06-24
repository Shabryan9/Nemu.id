<?php
/**
 * Mengambil koneksi PDO tunggal untuk seluruh aplikasi.
 */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db   = "nemu_id";

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    return $pdo;
}


//jalanin prepared statement dan mengembalikan semua baris.
function dbFetchAll(string $sql, array $params = []): array {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


//jalanin prepared statement dan mengembalikan satu nilai kolom.
function dbFetchColumn(string $sql, array $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}


//jalanin prepared statement untuk operasi tulis.
function dbExecute(string $sql, array $params = []): bool {
    $stmt = getDB()->prepare($sql);
    return $stmt->execute($params);
}

//mulai session aplikasi dengan folder session lokal jika belum aktif.
if (session_status() === PHP_SESSION_NONE) {
    $session_path = __DIR__ . '/../sessions';
    if (!is_dir($session_path)) {
        mkdir($session_path, 0777, true);
    }
    session_save_path($session_path);
    session_start();
}
