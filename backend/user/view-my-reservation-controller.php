<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$id     = (int) ($_GET['id'] ?? 0);

$r = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT * FROM reservations WHERE id = $id AND user_id = $userId"
));

if (!$r) {
    echo "Reservation not found.";
    exit;
}

$items = mysqli_query($con,
    "SELECT ri.quantity, ri.price_at_time AS price,
            p.name AS product_name, p.image_url
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     WHERE ri.reservation_id = $id"
);

$expiry    = strtotime($r['expiry_date']);
$daysLeft  = ceil(($expiry - time()) / 86400);
$isExpired = ($expiry < time());