<?php
/**
 * Backend query laporan pribadi user dengan filter.
 * Dipanggil dari user/laporan-saya.php
 * Menghasilkan variabel $items dan $total_items
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pdo = getDB();
$user_id = currentUserId();

// Variabel $type, $status, $search sudah didefinisikan di file pemanggil
// tetapi kita pastikan default jika tidak ada
$type   = $type ?? 'all';
$status = $status ?? '';
$search = $search ?? '';

// Bangun query UNION ALL
$sqlLost = "SELECT 'lost' AS type, id, item_name, description, last_location AS location, lost_datetime AS event_date, photo, status, created_at
            FROM lost_items WHERE user_id = :user_id_lost";
$sqlFound = "SELECT 'found' AS type, id, item_name, description, found_location AS location, found_datetime AS event_date, photo, status, created_at
             FROM found_items WHERE finder_user_id = :user_id_found";

$parts = [];
$params = [];

if ($type === 'all' || $type === 'lost') {
    $parts[] = $sqlLost;
    $params['user_id_lost'] = $user_id;
}
if ($type === 'all' || $type === 'found') {
    $parts[] = $sqlFound;
    $params['user_id_found'] = $user_id;
}

if (empty($parts)) {
    $items = [];
    $total_items = 0;
    return;
}

$unionSQL = implode(' UNION ALL ', $parts);
$finalSQL = "SELECT * FROM ($unionSQL) AS combined WHERE 1=1";

if (!empty($status)) {
    $finalSQL .= " AND status = :status";
    $params['status'] = $status;
}
if (!empty($search)) {
    $finalSQL .= " AND item_name LIKE :search";
    $params['search'] = "%$search%";
}

$finalSQL .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($finalSQL);
$stmt->execute($params);
$items = $stmt->fetchAll();
$total_items = count($items);