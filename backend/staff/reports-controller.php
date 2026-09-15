<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$totalRevenue = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT SUM(total_amount) as revenue FROM reservations WHERE status = 'completed'"
))['revenue'] ?? 0;

$totalRes = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT COUNT(*) as c FROM reservations"
))['c'];

$pendingRevenue = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT SUM(total_amount) as revenue FROM reservations WHERE status = 'pending'"
))['revenue'] ?? 0;

$topProducts = mysqli_query($con,
    "SELECT p.name, p.image_url, SUM(ri.quantity) as total_reserved
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     GROUP BY ri.product_id
     ORDER BY total_reserved DESC
     LIMIT 5"
);

$monthlyData = mysqli_query($con,
    "SELECT DATE_FORMAT(created_at, '%b %Y') as month,
            DATE_FORMAT(created_at, '%Y-%m') as month_key,
            COUNT(*) as total,
            SUM(total_amount) as revenue
     FROM reservations
     GROUP BY month_key
     ORDER BY month_key DESC
     LIMIT 6"
);
$months = []; $monthlyCounts = []; $monthlyRevenue = [];
while ($row = mysqli_fetch_assoc($monthlyData)) {
    array_unshift($months,        $row['month']);
    array_unshift($monthlyCounts, (int) $row['total']);
    array_unshift($monthlyRevenue,(float) $row['revenue']);
}

$stockOk  = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 5"))['c'];
$stockLow = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
$stockOut = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity <= 0"))['c'];