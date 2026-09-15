<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$reservations = mysqli_query($con, "SELECT * FROM reservations WHERE user_id = $userId ORDER BY created_at DESC");