<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$res = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT r.*, u.full_name, u.email
     FROM reservations r
     JOIN users u ON r.user_id = u.id
     WHERE r.id = $id"
));

if (!$res) {
    echo "Reservation not found.";
    exit;
}

$items = mysqli_query($con,
    "SELECT ri.quantity, ri.price_at_time AS price,
            p.name AS product_name, p.image_url, p.sku
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     WHERE ri.reservation_id = $id"
);