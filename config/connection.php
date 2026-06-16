<?php
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db   = "nemu_id";   // Nama database dari temanmu

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
            die("Request Time Out: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Session management: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    $session_path = __DIR__ . '/../sessions';
    if (!is_dir($session_path)) {
        mkdir($session_path, 0777, true);
    }
    session_save_path($session_path);
    session_start();
}
?>