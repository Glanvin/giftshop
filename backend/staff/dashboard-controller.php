<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$totalProducts = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products"))['c'];
$lowStock      = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
$pending       = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status = 'pending'"))['c'];
$completed     = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status = 'completed'"))['c'];
$recentRes = mysqli_query($con, "SELECT r.id, r.reservation_code, r.total_amount, r.status, r.created_at, u.full_name
                                  FROM reservations r
                                  JOIN users u ON r.user_id = u.id
                                  ORDER BY r.created_at DESC LIMIT 5");