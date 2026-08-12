<?php
session_start();
include('../connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

if (!isset($_POST['reservation_id']) || !isset($_FILES['receipt_image'])) {
    header('Location: my-reservations.php');
    exit;
}

$reservationId = (int) $_POST['reservation_id'];
$userId        = $_SESSION['user_id'];

// Make sure this reservation belongs to the logged-in user
$check = mysqli_query($con, "SELECT id, status FROM reservations WHERE id = $reservationId AND user_id = $userId");
if (mysqli_num_rows($check) == 0) {
    $_SESSION['receipt_error'] = "Invalid reservation.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

$row = mysqli_fetch_assoc($check);
if ($row['status'] == 'completed' || $row['status'] == 'cancelled') {
    $_SESSION['receipt_error'] = "You cannot upload a receipt for a " . $row['status'] . " reservation.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

// Validate file
$file     = $_FILES['receipt_image'];
$allowed  = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
$maxSize  = 5 * 1024 * 1024; // 5MB

if ($file['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['receipt_error'] = "Upload failed. Please try again.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

if (!in_array($file['type'], $allowed)) {
    $_SESSION['receipt_error'] = "Only JPG, PNG, and GIF images are allowed.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

if ($file['size'] > $maxSize) {
    $_SESSION['receipt_error'] = "File is too large. Maximum size is 5MB.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

// Save file
$uploadDir  = '../Receipts/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename  = 'receipt_' . $reservationId . '_' . time() . '.' . $ext;
$savePath  = $uploadDir . $filename;
$dbPath    = 'Receipts/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $savePath)) {
    $_SESSION['receipt_error'] = "Could not save the file. Please try again.";
    header("Location: view-my-reservation.php?id=$reservationId");
    exit;
}

// Save to DB
$dbPathSafe = mysqli_real_escape_string($con, $dbPath);
mysqli_query($con, "UPDATE reservations SET receipt_image = '$dbPathSafe' WHERE id = $reservationId");

$_SESSION['receipt_success'] = "Receipt uploaded successfully!";
header("Location: view-my-reservation.php?id=$reservationId");
exit;
?>