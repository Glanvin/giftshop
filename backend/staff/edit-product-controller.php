<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['staff', 'admin'], true)) {
    header('Location: ../login.php');
    exit;
}

$id = (int) ($_GET['edit'] ?? 0);

$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id='$id'"));
$categories = mysqli_query($con, "SELECT * FROM categories");

if (isset($_POST['update'])) {
    $name        = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock_quantity'];
    $sku         = $_POST['sku'];
    $size        = $_POST['size'];
    $color       = $_POST['color'];
    $status      = $_POST['status'];

    $sql_image = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['product_image']['name'];
        $target     = __DIR__ . '/../../product-images/' . $image_name;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target);
        $sql_image = ", image_url='product-images/" . $image_name . "'";
    }

    $sql = "UPDATE products SET
        name='$name',
        category_id='$category_id',
        price='$price',
        stock_quantity='$stock',
        sku='$sku',
        size='$size',
        color='$color',
        status='$status'
        $sql_image
        WHERE id='$id'";

    mysqli_query($con, $sql);
    $_SESSION['message'] = "Product Updated Successfully";
    header("Location: inventory.php");
    exit;
}