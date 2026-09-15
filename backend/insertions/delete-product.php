<?php
session_start();
require __DIR__ . '/../connection.php';

$id = $_GET['delete'];

$sql = "DELETE FROM products WHERE id='$id'";

mysqli_query($con,$sql);

$_SESSION['message'] = "Product Deleted";

header("Location: ../../staff/inventory.php");
?>
