<?php
session_start();
include('../connection.php');

// Only accept requests from the cart form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Grab the cart from the form and turn it into an array
$cartJson = $_POST['cart_data_json'] ?? '[]';
$cart     = json_decode($cartJson, true);

// this stops if the cart is empty
if (empty($cart)) {
    $_SESSION['cart_error'] = "Your cart is empty.";
    header('Location: cart.php');
    exit;
}

$userId     = (int) $_SESSION['user_id'];
$finalTotal = (float) ($_POST['final_total'] ?? 0);
$notes      = trim($_POST['notes'] ?? '');

// this will create a unique reservation code
$reservationCode = 'RES' . date('Ymd') . rand(1000, 9999);

do {
    $checkStmt = $con->prepare("SELECT id FROM reservations WHERE reservation_code = ?");
    $checkStmt->bind_param("s", $reservationCode);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
        $reservationCode = 'RES' . date('Ymd') . rand(1000, 9999);
    } else {
        break;
    }
    $checkStmt->close();
} while (true);
$checkStmt->close();
// this sets the reservation expiry date and initial status
$expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
$status     = 'pending';

$con->begin_transaction();

try {
    // Insert into reservations
    $stmt = $con->prepare(
        "INSERT INTO reservations 
            (user_id, reservation_code, total_amount, status, notes, expiry_date, created_at)
         VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );
    $stmt->bind_param("isdsss", $userId, $reservationCode, $finalTotal, $status, $notes, $expiryDate);
    $stmt->execute();
    $reservationId = $con->insert_id;
    $stmt->close();

    // Inserting each cart item and the deducted stock
    foreach ($cart as $item) {
        $productId = (int)   ($item['id']    ?? 0);
        $qty       = (int)   ($item['qty']   ?? 1);
        $price     = (float) ($item['price'] ?? 0);
        $size      = $item['size'] ?? '';

        if ($productId <= 0) continue;

        // Check stock availability
        $stockStmt = $con->prepare("SELECT stock_quantity FROM products WHERE id = ?");
        $stockStmt->bind_param("i", $productId);
        $stockStmt->execute();
        $stockStmt->bind_result($currentStock);
        $stockStmt->fetch();
        $stockStmt->close();

        if ($currentStock < $qty) {
            throw new Exception("Not enough stock for one of the items. Please update your cart.");
        }

        // Insert reservation item
        $itemStmt = $con->prepare(
            "INSERT INTO reservation_items 
                (reservation_id, product_id, quantity, price_at_time)
             VALUES (?, ?, ?, ?)"
        );
        $itemStmt->bind_param("iiid", $reservationId, $productId, $qty, $price);
        $itemStmt->execute();
        $itemStmt->close();

        // Deduct stock
        $deductStmt = $con->prepare(
            "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?"
        );
        $deductStmt->bind_param("ii", $qty, $productId);
        $deductStmt->execute();
        $deductStmt->close();
    }

    $con->commit();

    // Pass success info to the cart
    $_SESSION['reservation_success'] = true;
    $_SESSION['reservation_code']    = $reservationCode;

    header('Location: cart.php');
    exit;
  // if something went wrong, this will undo everything
} catch (Exception $e) {
    $con->rollback();
    $_SESSION['cart_error'] = "Reservation failed: " . $e->getMessage();
    header('Location: cart.php');
    exit;
}