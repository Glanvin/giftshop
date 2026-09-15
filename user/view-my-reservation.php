<?php
require __DIR__ . '/../backend/user/view-my-reservation-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation <?php echo $r['reservation_code']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-user">
    <?php include('../includes/header.php'); ?>

    <div class="page-wrap">

        <a href="my-reservations.php" class="btn-back mb-4 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Back to My Reservations
        </a>

        <div class="receipt">

            <div class="receipt-header">
                <div class="shop-name">CU Official Giftshop</div>
                <div class="shop-sub">Capitol University · Reservation Receipt</div>
                <div class="receipt-code"><?php echo $r['reservation_code']; ?></div>
                <span class="s-badge s-<?php echo $r['status']; ?>">
                    <?php echo ucfirst($r['status']); ?>
                </span>
            </div>

            <div class="receipt-body">

                <!-- Status message -->
                <?php if ($r['status'] == 'pending'): ?>
                    <div class="status-strip strip-pending">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <span>Please <strong>pay at the CU Cashier</strong> and present this code at the Giftshop before the expiry date.</span>
                    </div>
                <?php elseif ($r['status'] == 'confirmed'): ?>
                    <div class="status-strip strip-confirmed">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Your reservation is <strong>confirmed</strong>. Staff is preparing your items.</span>
                    </div>
                <?php elseif ($r['status'] == 'ready'): ?>
                    <div class="status-strip strip-ready">
                        <i class="bi bi-bag-check-fill fs-5"></i>
                        <span>Your order is <strong>ready for pickup!</strong> Please visit the Giftshop.</span>
                    </div>
                <?php elseif ($r['status'] == 'cancelled'): ?>
                    <div class="status-strip strip-cancelled">
                        <i class="bi bi-x-circle-fill fs-5"></i>
                        <span>This reservation has been <strong>cancelled</strong>.</span>
                    </div>
                <?php elseif ($r['status'] == 'completed'): ?>
                    <div class="status-strip strip-completed">
                        <i class="bi bi-patch-check-fill fs-5"></i>
                        <span>This reservation is <strong>completed</strong>. Thank you!</span>
                    </div>
                <?php endif; ?>

                <!-- Info -->
                <div class="info-grid">
                    <div class="info-cell">
                        <div class="info-label"><i class="bi bi-calendar-date me-1"></i>Date Placed</div>
                        <div class="info-value"><?php echo date('M d, Y h:i A', strtotime($r['created_at'])); ?></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-label"><i class="bi bi-clock me-1"></i>Expiry Date</div>
                        <div class="info-value <?php echo $isExpired ? 'text-danger' : ($daysLeft <= 2 ? 'text-warning' : ''); ?>">
                            <?php echo date('M d, Y', $expiry); ?>
                            <?php if ($isExpired): ?>
                                <span class="text-danger small">(Expired)</span>
                            <?php elseif ($daysLeft <= 2): ?>
                                <span class="text-warning small">(<?php echo $daysLeft; ?> day(s) left)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($r['notes']): ?>
                    <div class="info-cell" style="grid-column: span 2;">
                        <div class="info-label"><i class="bi bi-chat-right-text me-1"></i>Notes</div>
                        <div class="info-value"><?php echo htmlspecialchars($r['notes']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <hr class="dashed-divider">

                <!-- Items -->
                <h6 class="fw-bold mb-3" style="color:#6d1223;">
                    <i class="bi bi-box-seam me-2"></i>Reserved Items
                </h6>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:55px;"></th>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <tr>
                            <td>
                                <?php if ($item['image_url']): ?>
                                    <img src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $item['image_url'])); ?>" class="item-img" alt="">
                                <?php else: ?>
                                    <div class="item-img d-flex align-items-center justify-content-center" style="background:#f0f0f0;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><span class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></span></td>
                            <td class="text-center">
                                <span class="badge rounded-pill" style="background:#6d1223; font-size:12px;">
                                    <?php echo $item['quantity']; ?>
                                </span>
                            </td>
                            <td class="text-end">₱<?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-end fw-bold">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="4" class="text-end">Total Amount:</td>
                            <td class="text-end">₱<?php echo number_format($r['total_amount'], 2); ?></td>
                        </tr>
                    </tfoot>
                </table>

            </div>

            <div class="receipt-footer">
                <i class="bi bi-geo-alt me-1"></i> Capitol University Giftshop &nbsp;·&nbsp;
                <i class="bi bi-clock me-1"></i> Mon–Fri 8:00 AM – 5:00 PM &nbsp;·&nbsp;
                Present this code at the counter for pickup.
            </div>

        </div>

    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>