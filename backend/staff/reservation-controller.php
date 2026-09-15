<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$filter = $_GET['filter'] ?? 'all';

foreach (['pending','confirmed','ready','completed','cancelled'] as $s) {
    $r = mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status='$s'");
    $counts[$s] = mysqli_fetch_assoc($r)['c'];
}

if ($filter !== 'all') {
    $safe   = mysqli_real_escape_string($con, $filter);
    $result = mysqli_query($con, "SELECT r.*, u.full_name, u.email,
                (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) AS item_count
                FROM reservations r JOIN users u ON r.user_id = u.id
                WHERE r.status = '$safe' ORDER BY r.created_at DESC");
} else {
    $result = mysqli_query($con, "SELECT r.*, u.full_name, u.email,
                (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) AS item_count
                FROM reservations r JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC");
}