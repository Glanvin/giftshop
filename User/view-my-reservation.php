<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}
include('../connection.php');

$userId = $_SESSION['user_id'];
$id     = (int) ($_GET['id'] ?? 0);
    
// Fetch reservation — make sure it belongs to this user
$r = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT * FROM reservations WHERE id = $id AND user_id = $userId"
));

if (!$r) {
    echo "Reservation not found.";
    exit;
}

// Fetch items
$items = mysqli_query($con,
    "SELECT ri.quantity, ri.price_at_time AS price,
            p.name AS product_name, p.image_url
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     WHERE ri.reservation_id = $id"
);

// Expiry logic
$expiry    = strtotime($r['expiry_date']);
$daysLeft  = ceil(($expiry - time()) / 86400);
$isExpired = ($expiry < time());
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
    <style>
            body { 
                background: #faf9f6; 
                font-family: 'Poppins', sans-serif; 
                margin: 0; 
            }
            .page-wrap { 
                padding: 50px 180px; 
            }
            .receipt {
                background: white;
                border-radius: 16px;
                box-shadow: 0 6px 30px rgba(0,0,0,0.09);
                max-width: 750px;
                margin: 0 auto;
                overflow: hidden;
            }
            .receipt-header {
                background: #6d1223;
                color: white;
                padding: 30px 35px;
                text-align: center;
            }
            .receipt-header .shop-name {
                font-family: 'Playfair Display', serif;
                font-size: 22px;
                margin-bottom: 4px;
            }
            .receipt-header .shop-sub {
                font-size: 12px;
                opacity: 0.75;
                text-transform: uppercase;
                letter-spacing: 2px;
            }
            .receipt-code {
                font-family: monospace;
                font-size: 28px;
                font-weight: 900;
                letter-spacing: 3px;
                margin: 16px 0 10px;
                border: 2px dashed rgba(255,255,255,0.4);
                display: inline-block;
                padding: 8px 24px;
                border-radius: 8px;
            }
            .s-badge { 
                padding: 6px 18px; 
                border-radius: 20px; 
                font-size: 13px; 
                font-weight: 700; 
            }
            .s-pending { 
                background: #fff3e0; 
                color: #e65100; 
            }
            .s-confirmed { 
                background: #e3f2fd; 
                color: #1565c0; 
            }
            .s-ready { 
                background: #e8f5e9; 
                color: #2e7d32; 
            }
            .s-completed { 
                background: #ede7f6; 
                color: #4527a0; 
            }
            .s-cancelled { 
                background: #fce4ec; 
                color: #ad1457; 
            }
            .receipt-body { 
                padding: 30px 35px; 
            }
            .info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0;
                border: 1px solid #eee;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 25px;
            }
            .info-cell {
                padding: 14px 18px;
                border-bottom: 1px solid #eee;
                border-right: 1px solid #eee;
            }
            .info-cell:nth-child(even) { 
                border-right: none; 
            }
            .info-cell:nth-last-child(-n+2) { 
                border-bottom: none; 
            }
            .info-label { 
                font-size: 11px; 
                text-transform: uppercase; 
                letter-spacing: 0.5px; 
                color: #aaa; 
                margin-bottom: 4px; 
            }
            .info-value { 
                font-size: 14px; 
                font-weight: 600; 
                color: #333; 
            }
            .dashed-divider {
                border: none;
                border-top: 2px dashed #eee;
                margin: 20px 0;
            }
            .items-table { 
                width: 100%; 
                border-collapse: collapse; 
            }
            .items-table thead tr { 
                border-bottom: 2px solid #6d1223; 
            }
            .items-table thead th { 
                font-size: 11px; 
                text-transform: uppercase; 
                letter-spacing: 0.5px; 
                color: #999; 
                padding: 8px 10px; 
                font-weight: 600; 
            }
            .items-table tbody td { 
                padding: 12px 10px; 
                border-bottom: 1px solid #f5f5f5; 
                font-size: 14px; 
                vertical-align: middle; 
            }
            .items-table tbody tr:last-child td { 
                border-bottom: none; 
            }
            .item-img { 
                width: 45px; 
                height: 45px; 
                object-fit: cover; 
                border-radius: 8px; 
                border: 1px solid #eee; 
            }
            .item-name { 
                font-weight: 600; 
                color: #6d1223; 
            }
            .total-row { 
                background: #fdf8f8; 
            }
            .total-row td { 
                padding: 14px 10px; 
                font-weight: 900; 
                font-size: 16px; 
                color: #6d1223; 
            }
            .status-strip {
                border-radius: 8px;
                padding: 12px 16px;
                font-size: 13px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .strip-pending { 
                background: #fff8f0; 
                color: #e65100; 
                border-left: 4px solid #e65100; 
            }
            .strip-confirmed { 
                background: #f0f4ff; 
                color: #1565c0; 
                border-left: 4px solid #1565c0; 
            }
            .strip-ready { 
                background: #f1f8f1; 
                color: #2e7d32; 
                border-left: 4px solid #2e7d32; 
            }
            .strip-cancelled { 
                background: #fff5f7; 
                color: #ad1457; 
                border-left: 4px solid #ad1457; 
            }
            .strip-completed { 
                background: #f3f0ff; 
                color: #4527a0; 
                border-left: 4px solid #4527a0; 
            }
            .btn-back { 
                border: 2px solid #6d1223; 
                color: #6d1223; 
                background: white; 
                border-radius: 8px; 
                padding: 8px 20px; 
                font-weight: 600; 
                text-decoration: none; 
                display: inline-flex; 
                align-items: center; 
                gap: 6px; 
                font-size: 14px; 
            }
            .btn-back:hover { 
                background: #6d1223; 
                color: white; 
            }
            .receipt-footer {
                background: #fdf8f8;
                border-top: 1px solid #eee;
                padding: 18px 35px;
                text-align: center;
                font-size: 12px;
                color: #aaa;
            }
            @media (max-width: 1200px) { 
                .page-wrap { 
                    padding: 30px 20px; 
                } 
            }
            @media (max-width: 600px)  { 
                .info-grid { 
                    grid-template-columns: 1fr; 
                } 
            }
</style>
</head>
<body>
    <!-- header -->
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
                                    <img src="../<?php echo htmlspecialchars($item['image_url']); ?>" class="item-img" alt="">
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
    <!-- footer -->
    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>