<?php
require __DIR__ . '/../backend/user/my-reservations-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Reservations – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-user">
    <?php include('../includes/header.php'); ?>

    <div class="page-wrap">

        <div class="text-center mb-5">
            <h1 class="title">My Reservations</h1>
            <p class="text-muted">Track all your reservations and their status</p>
            <hr class="w-25 mx-auto border-danger opacity-50">
        </div>

        <?php if (mysqli_num_rows($reservations) == 0): ?>

            <div class="empty-state">
                <i class="bi bi-clipboard-x"></i>
                <h4>No Reservations Yet</h4>
                <p>You haven't made any reservations yet. Start shopping!</p>
                <a href="Shopuser.php" class="btn mt-2 px-4 fw-bold"
                   style="background:#6d1223; color:white; border-radius:8px;">
                    <i class="bi bi-bag me-2"></i>Go to Shop
                </a>
            </div>

        <?php else: ?>

            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                <?php while ($r = mysqli_fetch_assoc($reservations)):

                    // Expiry
                    $expiry    = strtotime($r['expiry_date']);
                    $daysLeft  = ceil(($expiry - time()) / 86400);
                    $isExpired = ($expiry < time());

                    if ($isExpired && $r['status'] == 'pending') {
                        $expiryClass = 'expiry-expired';
                        $expiryText  = '<i class="bi bi-clock me-1"></i>Expired';
                    } elseif ($daysLeft <= 2 && !$isExpired) {
                        $expiryClass = 'expiry-warn';
                        $expiryText  = '<i class="bi bi-exclamation-triangle me-1"></i>Expires in ' . $daysLeft . ' day(s)';
                    } else {
                        $expiryClass = 'expiry-ok';
                        $expiryText  = '<i class="bi bi-calendar me-1"></i>Expires ' . date('M d, Y', $expiry);
                    }

                    // Count total items in the reservations
                    $resId     = $r['id'];
                    $itemCount = mysqli_fetch_assoc(mysqli_query($con,
                        "SELECT COUNT(*) as total FROM reservation_items WHERE reservation_id = $resId"
                    ))['total'];
                ?>

                <div class="col">
                    <div class="res-card">

                        <div class="res-card-top top-<?php echo $r['status']; ?>"></div>

                        <div class="res-card-body">

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="res-code">
                                    <i class="bi bi-ticket-perforated me-1"></i>
                                    <?php echo $r['reservation_code']; ?>
                                </div>
                                <span class="s-badge s-<?php echo $r['status']; ?>">
                                    <?php echo ucfirst($r['status']); ?>
                                </span>
                            </div>

                            <!-- Total -->
                            <div class="card-total">
                                ₱<?php echo number_format($r['total_amount'], 2); ?>
                            </div>

                            <!-- Information -->
                            <div class="card-info">
                                <div class="card-info-row">
                                    <span class="card-info-label"><i class="bi bi-calendar-date me-1"></i>Date</span>
                                    <span class="card-info-value"><?php echo date('M d, Y', strtotime($r['created_at'])); ?></span>
                                </div>
                                <div class="card-info-row">
                                    <span class="card-info-label"><i class="bi bi-box-seam me-1"></i>Items</span>
                                    <span class="card-info-value"><?php echo $itemCount; ?> item(s)</span>
                                </div>
                                <div class="card-info-row">
                                    <span class="card-info-label"><i class="bi bi-clock me-1"></i>Expiry</span>
                                    <span class="<?php echo $expiryClass; ?>"><?php echo $expiryText; ?></span>
                                </div>
                            </div>

                            <!-- Status message -->
                            <?php if ($r['status'] == 'pending'): ?>
                                <div style="background:#fff8f0; border-left:3px solid #e65100; border-radius:6px; padding:8px 12px; font-size:12px; color:#e65100; margin-bottom:14px;">
                                    <i class="bi bi-info-circle me-1"></i> Pay at CU Cashier within the expiry date.
                                </div>
                            <?php elseif ($r['status'] == 'confirmed'): ?>
                                <div style="background:#f0f4ff; border-left:3px solid #1565c0; border-radius:6px; padding:8px 12px; font-size:12px; color:#1565c0; margin-bottom:14px;">
                                    <i class="bi bi-check2-circle me-1"></i> Confirmed! We're preparing your items.
                                </div>
                            <?php elseif ($r['status'] == 'ready'): ?>
                                <div style="background:#f1f8f1; border-left:3px solid #2e7d32; border-radius:6px; padding:8px 12px; font-size:12px; color:#2e7d32; margin-bottom:14px;">
                                    <i class="bi bi-check-circle me-1"></i> Ready for pickup! Visit the Giftshop.
                                </div>
                            <?php elseif ($r['status'] == 'cancelled'): ?>
                                <div style="background:#fff5f7; border-left:3px solid #ad1457; border-radius:6px; padding:8px 12px; font-size:12px; color:#ad1457; margin-bottom:14px;">
                                    <i class="bi bi-x-circle me-1"></i> This reservation was cancelled.
                                </div>
                            <?php else: ?>
                                <div style="margin-bottom:14px;"></div>
                            <?php endif; ?>

                            <!-- View Button -->
                            <a href="view-my-reservation.php?id=<?php echo $r['id']; ?>" class="btn-view-res">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>

                        </div>
                    </div>
                </div>

                <?php endwhile; ?>

            </div>

            <div class="text-center mt-5">
                <a href="Shopuser.php" class="btn px-4 fw-bold"
                   style="background:#6d1223; color:white; border-radius:8px;">
                    <i class="bi bi-bag me-2"></i>Continue Shopping
                </a>
            </div>

        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>