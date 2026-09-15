<?php
require __DIR__ . '/../backend/user/cart-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-user">
    <?php include('../includes/header.php'); ?>

    <div class="page-wrap">

        <!-- Success Message -->
        <?php if (isset($_SESSION['reservation_success'])): ?>
            <div class="alert alert-success d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm"
                 style="background:#f1f8f1; border-left:5px solid #28a745; border-radius:8px;">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                    <div>
                        <span class="fw-bold text-success d-block">Reservation created successfully!</span>
                        <span class="text-muted small">Your reservation code: </span>
                        <span class="badge bg-success fs-6"><?php echo $_SESSION['reservation_code']; ?></span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="my-reservations.php" class="btn btn-sm btn-success">View My Reservations</a>
                    <a href="Shopuser.php" class="btn btn-sm btn-outline-secondary">Continue Shopping</a>
                </div>
            </div>
            <?php unset($_SESSION['reservation_success']); unset($_SESSION['reservation_code']); ?>
        <?php endif; ?>

        <!-- Error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h1 class="title">Shopping Cart</h1>
            <p class="text-muted">Check the items you want to reserve, then click Submit Reservation</p>
            <hr class="w-25 mx-auto" style="border: 2px solid #d4af37; opacity: 1;">
        </div>

        <form method="POST" action="cart.php">
            <div class="row g-4 align-items-start">

                <!-- Cart Table -->
                <div class="col-lg-8">

                    <?php if (empty($_SESSION['cart'])): ?>
                        <div class="cart-table empty-cart">
                            <i class="bi bi-cart-x"></i>
                            <p class="fs-5">Your cart is empty.</p>
                            <a href="Shopuser.php" class="btn px-4 fw-bold mt-2" style="background:#6d1223; color:white; border-radius:8px;">
                                Go to Shop
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="cart-table">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">
                                            <input type="checkbox" class="form-check-input" id="checkAll" title="Select all">
                                        </th>
                                        <th style="width:80px;"></th>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $pid => $item): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input item-check"
                                                   name="selected_items[]" value="<?php echo $pid; ?>" checked>
                                        </td>
                                        <td>
                                            <?php if ($item['image_url']): ?>
                                                <img src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $item['image_url'])); ?>" class="cart-img" alt="">
                                            <?php else: ?>
                                                <div class="cart-img d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-image fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold" style="color:#6d1223;"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <small class="text-muted">₱<?php echo number_format($item['price'], 2); ?> each</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="qty-wrap justify-content-center">
                                                <a href="cart.php?decrease=<?php echo $pid; ?>" class="qty-btn">−</a>
                                                <span class="qty-num"><?php echo $item['quantity']; ?></span>
                                                <a href="cart.php?increase=<?php echo $pid; ?>" class="qty-btn">+</a>
                                            </div>
                                        </td>
                                        <td class="text-end">₱<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-end fw-bold" style="color:#6d1223;">
                                            ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="cart.php?remove=<?php echo $pid; ?>"
                                               class="btn-remove"
                                               onclick="return confirm('Remove this item from cart?')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background:#fdf8f8;">
                                        <td colspan="5" class="text-end fw-bold" style="color:#6d1223;">Cart Total:</td>
                                        <td class="text-end fw-bold fs-5" style="color:#6d1223;">
                                            ₱<?php echo number_format($grandTotal, 2); ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h4 class="fw-bold mb-4 text-maroon">Order Summary</h4>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Cart Total:</span>
                            <span class="fw-bold">₱<?php echo number_format($grandTotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3 mb-4">
                            <h5 class="text-maroon">To Reserve:</h5>
                            <h5 class="text-maroon">₱<?php echo number_format($grandTotal, 2); ?></h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-maroon small fw-bold">
                                <i class="bi bi-chat-right-text me-1"></i> Special Instructions (Optional)
                            </label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Any special requests or notes..."></textarea>
                        </div>

                        <button type="submit" name="place_reservation" class="btn-reserve"
                            <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>
                            <i class="bi bi-check2-circle me-2"></i> Submit Reservation
                        </button>

                        <div class="note-box mt-3">
                            <i class="bi bi-info-circle-fill me-1 text-warning"></i>
                            <strong>Note:</strong> Only <u>checked</u> items will be reserved.
                            Payment must be made at the CU Cashier within 7 days.
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/shopuser.js"></script>
</body>
</html>