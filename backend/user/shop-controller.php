<?php
session_start();
require __DIR__ . '/../connection.php';

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $productId = (int) $_POST['product_id'];
    $product   = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id = $productId"));

    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$productId] = [
                'name'      => $product['name'],
                'price'     => $product['price'],
                'image_url' => str_replace('Product-Images', 'product-images', (string) $product['image_url']),
                'quantity'  => 1
            ];
        }
        $_SESSION['cart_toast'] = $product['name'];
    }

    $query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header("Location: Shopuser.php$query");
    exit;
}

$search   = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort']     ?? 'az';

$query = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";

if ($search) {
    $s = mysqli_real_escape_string($con, $search);
    $query .= " AND (p.name LIKE '%$s%' OR p.description LIKE '%$s%')";
}

if ($category && $category != "All Categories") {
    $cat = mysqli_real_escape_string($con, $category);
    $query .= " AND p.category_id = '$cat'";
}

switch ($sort) {
    case "za":   $query .= " ORDER BY p.name DESC"; break;
    case "low":  $query .= " ORDER BY p.price ASC"; break;
    case "high": $query .= " ORDER BY p.price DESC"; break;
    default:     $query .= " ORDER BY p.name ASC";
}

$result     = mysqli_query($con, $query);
$categories = [1 => "Textbook", 2 => "Uniform", 3 => "PE Uniform", 4 => "Merchandise"];

$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}