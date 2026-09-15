<?php
session_start();
require __DIR__ . '/../connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $productId = (int) $_POST['product_id'];
    $qty       = (int) $_POST['quantity'];
    $product   = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id = $productId"));

    if ($product) {
        $imageUrl = str_replace('Product-Images', 'product-images', (string) $product['image_url']);
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$productId] = [
                'name'      => $product['name'],
                'price'     => $product['price'],
                'image_url' => $imageUrl,
                'quantity'  => $qty
            ];
        }
    }

    header('Location: cart.php');
    exit;
}

if (isset($_GET['increase'])) {
    $pid = (int) $_GET['increase'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] += 1;
    }
    header('Location: cart.php');
    exit;
}

if (isset($_GET['decrease'])) {
    $pid = (int) $_GET['decrease'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] -= 1;
        if ($_SESSION['cart'][$pid]['quantity'] <= 0) {
            unset($_SESSION['cart'][$pid]);
        }
    }
    header('Location: cart.php');
    exit;
}

if (isset($_GET['remove'])) {
    $pid = (int) $_GET['remove'];
    unset($_SESSION['cart'][$pid]);
    header('Location: cart.php');
    exit;
}

if (isset($_POST['place_reservation'])) {

    $selected = $_POST['selected_items'] ?? [];
    if (empty($selected)) {
        $error = "Please check at least one item to reserve.";
    } else {
        $userId = (int) $_SESSION['user_id'];
        $notes  = mysqli_real_escape_string($con, $_POST['notes'] ?? '');
        $total  = 0;

        foreach ($selected as $pid) {
            $pid = (int) $pid;
            if (isset($_SESSION['cart'][$pid])) {
                $total += $_SESSION['cart'][$pid]['price'] * $_SESSION['cart'][$pid]['quantity'];
            }
        }

        $resCode = 'RES' . date('Ymd') . rand(1000, 9999);
        $expiry  = date('Y-m-d H:i:s', strtotime('+7 days'));

        mysqli_query($con, "INSERT INTO reservations (user_id, reservation_code, total_amount, status, notes, expiry_date, created_at)
                            VALUES ('$userId', '$resCode', '$total', 'pending', '$notes', '$expiry', NOW())");

        $reservationId = mysqli_insert_id($con);

        foreach ($selected as $pid) {
            $pid = (int) $pid;
            if (!isset($_SESSION['cart'][$pid])) continue;

            $qty   = (int)   $_SESSION['cart'][$pid]['quantity'];
            $price = (float) $_SESSION['cart'][$pid]['price'];

            mysqli_query($con, "INSERT INTO reservation_items (reservation_id, product_id, quantity, price_at_time)
                                VALUES ('$reservationId', '$pid', '$qty', '$price')");

            mysqli_query($con, "UPDATE products SET stock_quantity = stock_quantity - $qty WHERE id = $pid");
            mysqli_query($con, "UPDATE products SET status = 'out_of_stock' WHERE id = $pid AND stock_quantity <= 0");

            unset($_SESSION['cart'][$pid]);
        }

        $_SESSION['reservation_success'] = true;
        $_SESSION['reservation_code']    = $resCode;

        header('Location: cart.php');
        exit;
    }
}

$grandTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $grandTotal += $item['price'] * $item['quantity'];
}