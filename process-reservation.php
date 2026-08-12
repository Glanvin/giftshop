<?php
session_start();
include('connection.php');
// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_data = json_decode($_POST['cart_data_json'], true);
    $final_total = (float)$_POST['final_total'];
    $user_id = $_SESSION['user_id'] ?? 0; 
    $res_code = "RES" . date("Ymd") . rand(100, 999);
    $expiry = date('Y-m-d H:i:s', strtotime('+3 days'));

    $q = "INSERT INTO reservations (user_id, reservation_code, total_amount, status, expiry_date) 
          VALUES ('$user_id', '$res_code', '$final_total', 'pending', '$expiry')";
    
    if (mysqli_query($con, $q)) {
        $res_id = mysqli_insert_id($con);

        foreach ($cart_data as $item) {
            $p_id = (int)$item['productId'];
            $qty = (int)$item['quantity'];
            $price = (float)$item['price'];

            $iq = "INSERT INTO reservation_items (reservation_id, product_id, quantity, price_at_time) 
                   VALUES ('$res_id', '$p_id', '$qty', '$price')";
            mysqli_query($con, $iq);
        }
        $_SESSION['reservation_success'] = true;
        $_SESSION['reservation_code'] = $res_code;
        header("Location: cart.php");
        exit();
    }
}
?>