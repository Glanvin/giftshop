<?php
include('../connection.php');
// Get the reservation ID from the URL and ensure it's an integer
$resId = (int)$_GET['id'];

// fetch reservation items with the product details
$sql = "SELECT ri.*, p.name, p.image_url 
        FROM reservation_items ri 
        JOIN products p ON ri.product_id = p.id 
        WHERE ri.reservation_id = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $resId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($items)) exit("<p class='text-center'>No items found.</p>");

echo '<table class="table table-bordered">
        <thead class="table-light">
            <tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
        </thead><tbody>';

foreach ($items as $item) {
    $sub = $item['quantity'] * $item['price'];
    echo "<tr>
            <td><img src='../{$item['image_url']}' width='30'> {$item['name']}</td>
            <td>{$item['size']}</td>
            <td>{$item['quantity']}</td>
            <td>₱" . number_format($item['price'], 2) . "</td>
            <td>₱" . number_format($sub, 2) . "</td>
          </tr>";
}
echo '</tbody></table>'; </php>