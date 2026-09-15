<?php
require __DIR__ . '/../backend/staff/view-reservation-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body class="area-staff">
    <?php include('../includes/header-staff.php'); ?>

    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="reservation.php" class="btn-back">
          <i class="bi bi-arrow-left"></i> Back
        </a>
        <a href="edit-reservation.php?id=<?php echo $id; ?>" class="btn-edit-link">
          <i class="bi bi-pencil"></i> Edit Status
        </a>
      </div>

      <h2 class="page-title mb-4">Reservation Details</h2>

      <!-- reservation information -->
      <div class="info-card">
        <div class="row g-4">
          <div class="col-md-4">
            <div class="label">Reservation Code</div>
            <div class="res-code">
              <?php echo htmlspecialchars($res['reservation_code']); ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="label">Status</div>
            <span class="status-badge badge-<?php echo $res['status']; ?>">
              <?php echo ucfirst(str_replace('_', ' ', $res['status'])); ?>
            </span>
          </div>
          <div class="col-md-4">
            <div class="label">Total Amount</div>
            <div class="value" style="color: #6d1223; font-size: 20px">
              ₱<?php echo number_format($res['total_amount'], 2); ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="label">Customer</div>
            <div class="value">
              <?php echo htmlspecialchars($res['full_name']); ?>
            </div>
            <small class="text-muted"><?php echo htmlspecialchars($res['email']); ?></small>
          </div>
          <div class="col-md-4">
            <div class="label">Date Placed</div>
            <div class="value">
              <?php echo date('M d, Y h:i A', strtotime($res['created_at'])); ?>
            </div>
          </div>
          <div class="col-md-4">
            <div class="label">Expiry Date</div>
            <div class="value">
              <?php echo date('M d, Y', strtotime($res['expiry_date'])); ?>
            </div>
          </div>
          <?php if ($res['notes']): ?>
          <div class="col-12">
            <div class="label">Notes</div>
            <div class="value">
              <?php echo htmlspecialchars($res['notes']); ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- product table -->
      <div class="info-card p-0 overflow-hidden">
        <table class="table table-borderless mb-0 items-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>SKU</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Unit Price</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php $grandTotal = 0; while ($item = mysqli_fetch_assoc($items)):
            $sub = $item['price'] * $item['quantity']; $grandTotal += $sub; ?>
            <tr>
              <td>
                <?php if ($item['image_url']): ?>
                <img
                  src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $item['image_url'])); ?>"
                  class="product-img"
                />
                <?php else: ?>
                <div class="product-img d-flex align-items-center justify-content-center bg-light text-muted">
                  <i class="bi bi-image"></i>
                </div>
                <?php endif; ?>
              </td>
              <td class="fw-bold" style="color: #6d1223">
                <?php echo htmlspecialchars($item['product_name']); ?>
              </td>
              <td>
                <code><?php echo htmlspecialchars($item['sku'] ?? '—'); ?></code>
              </td>
              <td class="text-center">
                <span class="badge rounded-pill" style="background: #6d1223"><?php echo $item['quantity']; ?></span>
              </td>
              <td class="text-end">
                ₱<?php echo number_format($item['price'], 2); ?>
              </td>
              <td class="text-end fw-bold">
                ₱<?php echo number_format($sub, 2); ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
          <tfoot>
            <tr style="background: #fdf8f8">
              <td colspan="5" class="text-end fw-bold" style="color: #6d1223">
                Grand Total:
              </td>
              <td class="text-end fw-bold" style="color: #6d1223; font-size: 16px">
                ₱<?php echo number_format($grandTotal, 2); ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>