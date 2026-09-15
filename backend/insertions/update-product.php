<?php
session_start();
require __DIR__ . '/../connection.php';

// checks if form was submitted using the post method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $sku = $_POST['sku'];
    $low_stock_threshold = $_POST['low_stock_threshold'];
    $image_url = $_POST['image_url'];
// checking to see if uploading an image without any error
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $upload_dir = "../uploads/";
        $image_url  = $upload_dir . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $image_url);
    }

    // sql inserting product to database 
    $sql = "INSERT INTO products (category_id, name, description, price, stock_quantity, size, color, sku, low_stock_threshold, image_url)
            VALUES ('$category_id', '$name', '$description', '$price', '$stock_quantity', '$size', '$color', '$sku', '$low_stock_threshold', '$image_url')";

// executing the query to see if its successful or not
    if (mysqli_query($con, $sql)) {
        header("Location: ../../staff/inventory.php?success=Product added successfully!");
    } else {
        header("Location: ../../staff/inventory.php?error=" . mysqli_error($con));
    }
    exit();
}
?>