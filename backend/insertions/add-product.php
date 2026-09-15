<?php
session_start();
require __DIR__ . '/../connection.php';

if (isset($_POST['save'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $category = mysqli_real_escape_string($con, $_POST['category_id']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $stock = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
    $sku = mysqli_real_escape_string($con, $_POST['sku']);
    $size = mysqli_real_escape_string($con, $_POST['size']);
    $color = mysqli_real_escape_string($con, $_POST['color']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $lowstock = mysqli_real_escape_string($con, $_POST['low_stock_threshold']);

    // Check duplicate SKU (Product Code)
    $checkSku = mysqli_query($con, "SELECT sku FROM products WHERE sku = '$sku'");
    if (mysqli_num_rows($checkSku) > 0) {
        $_SESSION['message'] = "Error: SKU '$sku' already exists!";
        header("Location: ../../staff/inventory.php");
        exit();
    }

    // image upload
    $image_url = '';

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $file = $_FILES['product_image'];
        $filename = time() . '_' . basename($file['name']);
        $uploadDir = '../../product-images/';

        // Create folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Store path relative to project root so img src="product-images/..." works
            $image_url = 'product-images/' . $filename;
        } else {
            $_SESSION['message'] = "Error: Could not upload image.";
            header("Location: ../../staff/inventory.php");
            exit();
        }
    }

    // Insert product
    $sql = "INSERT INTO products (name, category_id, description, price, stock_quantity, sku, size, color, status, low_stock_threshold, image_url)
            VALUES ('$name', '$category', '$description', '$price', '$stock', '$sku', '$size', '$color', '$status', '$lowstock', '$image_url')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['message'] = "Product Added Successfully!";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($con);
    }

    header("Location: ../../staff/inventory.php");
    exit();
}
?>