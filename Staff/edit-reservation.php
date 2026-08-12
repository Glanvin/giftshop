<?php
session_start();
//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}
include('../connection.php');

// Get id from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if (isset($_POST['update_status'])) {
    $newStatus = mysqli_real_escape_string($con, $_POST['new_status']);
    mysqli_query($con, "UPDATE reservations SET status='$newStatus' WHERE id=$id");
    $_SESSION['res_message'] = "Reservation status updated to <strong>" . ucfirst($newStatus) . "</strong>.";
    header("Location: reservation.php");
    exit;
}

// Fetch current reservation data
$res = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT r.*, u.full_name
     FROM reservations r
     JOIN users u ON r.user_id = u.id
     WHERE r.id = $id"
));

if (!$res) {
    echo "Reservation not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            background: #f5f0eb; 
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            color: #6d1223; 
            font-size: 36px; 
        }
        .edit-card {
            background: white;
            border-radius: 12px; 
            padding: 32px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            max-width: 520px; 
            margin: 0 auto; }
        .form-label {
            font-weight: 600; 
            color: #555; 
        }
        .form-control, .form-select {
            border-radius: 8px; 
            padding: 10px; 
            border: 1px solid #ddd; 
        }
        .form-control:focus, .form-select:focus { border-color: #6d1223; box-shadow: none; }
        .btn-save { background: #6d1223; color: white; border: none; border-radius: 8px; padding: 10px 30px; font-weight: 600; }
        .btn-save:hover { background: #4e0d18; color: white; }
        .btn-cancel { border: 2px solid #6d1223; color: #6d1223; background: white; border-radius: 8px; padding: 10px 30px; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-cancel:hover { background: #6d1223; color: white; }
        .res-code { font-size: 18px; font-weight: 900; color: #6d1223; font-family: monospace; }
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
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
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                    <a href="reservation-management.php" class="btn-cancel">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('../includes/footer-staff.php'); ?>
</html>