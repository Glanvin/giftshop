<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$id      = (int) $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT p.*, c.name AS category_name
                                                   FROM products p
                                                   LEFT JOIN categories c ON p.category_id = c.id
                                                   WHERE p.id = $id"));

if (!$product) {
    echo "Product not found.";
    exit;
}

$maxStock = (int) $product['stock_quantity'];