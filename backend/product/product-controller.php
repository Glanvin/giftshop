<?php
require __DIR__ . '/../connection.php';

$id = (int) ($_GET['id'] ?? 0);
$product = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT p.*, c.name AS category_name
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.id = $id AND p.status = 'active'"
));

if (!$product) {
    echo "Product not found.";
    exit;
}

$stock = (int) $product['stock_quantity'];

$imgUrl = str_replace('Product-Images', 'product-images', (string) ($product['image_url'] ?? ''));