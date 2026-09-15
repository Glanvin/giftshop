<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$categories_query = mysqli_query($con, "SELECT * FROM categories");
$categories = [];
while ($cat = mysqli_fetch_assoc($categories_query)) {
    $categories[] = $cat;
}

$filter = $_GET['filter'] ?? 'all';

if ($filter == 'active') {
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active' AND p.stock_quantity > 5";
} elseif ($filter == 'low') {
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock_quantity > 0 AND p.stock_quantity <= 5";
} elseif ($filter == 'out') {
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock_quantity <= 0 OR p.status = 'out_of_stock'";
} else {
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
}

$products_result = mysqli_query($con, $sql);

$countAll    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products"))['c'];
$countActive = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE status = 'active' AND stock_quantity > 5"))['c'];
$countLow    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
$countOut    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity <= 0 OR status = 'out_of_stock'"))['c'];