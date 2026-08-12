<?php
session_start();
include('../connection.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

// Get the reservation id from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch reservation + customer info
$res = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT r.*, u.full_name, u.email
     FROM reservations r
     JOIN users u ON r.user_id = u.id
     WHERE r.id = $id"
));

if (!$res) {
    echo "Reservation not found.";
    exit;
}

// Fetch items
$items = mysqli_query($con,
    "SELECT ri.quantity, ri.price_at_time AS price,
            p.name AS product_name, p.image_url, p.sku
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     WHERE ri.reservation_id = $id"
);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Reservation</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      body {
        font-family: "Poppins", sans-serif;
        background: #f5f0eb;
      }
      .page-title {
        font-family: "Playfair Display", serif;
        color: #6d1223;
        font-size: 36px;
      }
      .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
      }
      .label {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
      }
      .value {
        font-size: 15px;
        font-weight: 600;
        color: #333;
      }
      .res-code {
        font-size: 22px;
        font-weight: 900;
        color: #6d1223;
        font-family: monospace;
        letter-spacing: 1px;
      }
      .status-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
      }
      .badge-pending {
        background: #ffc107;
        color: #333;
      }
      .badge-confirmed {
        background: #17a2b8;
        color: white;
      }
      .badge-ready {
        background: #6f42c1;
        color: white;
      }
      .badge-completed {
        background: #28a745;
        color: white;
      }
      .badge-cancelled {
        background: #dc3545;
        color: white;
      }
      .items-table thead {
        background: #6d1223;
        color: white;
      }
      .items-table thead th {
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 500;
      }
      .items-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
      }
      .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
      }
      .btn-back {
        border: 2px solid #6d1223;
        color: #6d1223;
        background: white;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }
      .btn-back:hover {
        background: #6d1223;
        color: white;
      }
      .btn-edit-link {
        background: #d4af37;
        color: #333;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }
      .btn-edit-link:hover {
        background: #b8982e;
        color: #333;
      }
    </style>
  </head>
  <body>
    <?php include('../includes/header-staff.php'); ?>

    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="reservation-management.php" class="btn-back">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <a
          href="edit-reservation.php?id=<?php echo $id; ?>"
          class="btn-edit-link"
        >
          <i class="fas fa-edit"></i> Edit Status
        </a>
      </div>

      <h2 class="page-title mb-4">Reservation Details</h2>

      <!--reservation information -->
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
            <small class="text-muted"
              ><?php echo htmlspecialchars($res['email']); ?></small
            >
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
                  src="<?php echo htmlspecialchars($item['image_url']); ?>"
                  class="product-img"
                />
                <?php else: ?>
                <div
                  class="product-img d-flex align-items-center justify-content-center bg-light text-muted"
                >
                  <i class="fas fa-image"></i>
                </div>
                <?php endif; ?>
              </td>
              <td class="fw-bold" style="color: #6d1223">
                <?php echo htmlspecialchars($item['product_name']); ?>
              </td>
              <td>
                <code
                  ><?php echo htmlspecialchars($item['sku'] ?? '—'); ?></code
                >
              </td>
              <td class="text-center">
                <span class="badge rounded-pill" style="background: #6d1223"
                  ><?php echo $item['quantity']; ?></span
                >
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
              <td
                class="text-end fw-bold"
                style="color: #6d1223; font-size: 16px"
              >
                ₱<?php echo number_format($grandTotal, 2); ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  <?php include('../includes/footer-staff.php'); ?>
</html>
