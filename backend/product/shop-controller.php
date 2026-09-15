<?php
require __DIR__ . '/../connection.php';

$search = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort']     ?? 'az';

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

$result = mysqli_query($con, $query);
$categories = [1 => "Textbook", 2 => "Uniform", 3 => "PE Uniform", 4 => "Merchandise"];