<?php
require __DIR__ . '/../backend/staff/edit-reservation-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-staff">
    <?php include('../includes/header-staff.php'); ?>

    <div class="container py-5">
        <h2 class="page-title text-center mb-4">Edit Reservation Status</h2>

        <div class="edit-card">

            <!-- Current Info -->
            <div class="mb-4 p-3 rounded" style="background:#fdf8f8; border-left: 4px solid #6d1223;">
                <div class="info-label mb-1">Reservation Code</div>
                <div class="res-code"><?php echo htmlspecialchars($res['reservation_code']); ?></div>
                <div class="mt-2 text-muted" style="font-size:14px;">
                    Customer: <strong><?php echo htmlspecialchars($res['full_name']); ?></strong>
                </div>
                <div class="text-muted" style="font-size:14px;">
                    Total: <strong>₱<?php echo number_format($res['total_amount'], 2); ?></strong>
                </div>
            </div>

            <!-- Form -->
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Current Status</label>
                    <input type="text" class="form-control"
                           value="<?php echo ucfirst(str_replace('_', ' ', $res['status'])); ?>"
                           disabled>
                </div>

                <div class="mb-4">
                    <label class="form-label">Update Status To</label>
                    <select name="new_status" class="form-select" required>
                        <option value="pending"   <?php echo $res['status']=='pending'   ? 'selected':''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $res['status']=='confirmed' ? 'selected':''; ?>>Confirmed</option>
                        <option value="ready"     <?php echo $res['status']=='ready'     ? 'selected':''; ?>>Ready for Pickup</option>
                        <option value="completed" <?php echo $res['status']=='completed' ? 'selected':''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $res['status']=='cancelled' ? 'selected':''; ?>>Cancelled</option>
                    </select>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" name="update_status" class="btn-save">
                        <i class="bi bi-check-circle me-2"></i> Save Changes
                    </button>
                    <a href="reservation.php" class="btn-cancel">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>