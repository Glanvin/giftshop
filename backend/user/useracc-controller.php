<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'"));
$total_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM reservations WHERE user_id = '$user_id'"))['total'];
$active_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as active FROM reservations WHERE user_id = '$user_id' AND status IN ('pending', 'confirmed', 'ready')"))['active'];
$history_query = mysqli_query($con, "SELECT *,
    (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) as item_count
    FROM reservations r
    WHERE user_id = '$user_id'
    ORDER BY created_at DESC");