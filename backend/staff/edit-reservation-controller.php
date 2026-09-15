<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (isset($_POST['update_status'])) {
    $newStatus = mysqli_real_escape_string($con, $_POST['new_status']);
    mysqli_query($con, "UPDATE reservations SET status='$newStatus' WHERE id=$id");
    $_SESSION['res_message'] = "Reservation status updated to <strong>" . ucfirst($newStatus) . "</strong>.";
    header("Location: reservation.php");
    exit;
}

$res = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT r.*, u.full_name
     FROM reservations r
     JOIN users u ON r.user_id = u.id
     WHERE r.id = $id"
));

if (!$res) {
    echo "Reservation not found.";
    exit;
}