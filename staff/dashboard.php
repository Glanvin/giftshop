<?php
require __DIR__ . '/../backend/staff/dashboard-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard – Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-staff">
    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">

        <div class="welcome-section">
            <h2>Good day, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
            <p><i class="bi bi-calendar3 me-1"></i><?php echo date('l, F d, Y'); ?></p>
        </div>

        <div class="row g-4">

            <div class="col-6 col-lg-3">
                <div class="stat-card card-1">
                    <div class="card-bg-num"><?php echo $totalProducts; ?></div>
                    <span class="card-icon"><i class="bi bi-box-seam-fill"></i></span>
                    <div class="card-label">Total Products</div>
                    <div class="card-number"><?php echo $totalProducts; ?></div>
                    <a href="inventory.php" class="card-link">Manage <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card card-2">
                    <div class="card-bg-num"><?php echo $lowStock; ?></div>
                    <span class="card-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <div class="card-label">Low Stock</div>
                    <div class="card-number"><?php echo $lowStock; ?></div>
                    <a href="inventory.php" class="card-link">View Items <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card card-3">
                    <div class="card-bg-num"><?php echo $pending; ?></div>
                    <span class="card-icon"><i class="bi bi-clock-fill"></i></span>
                    <div class="card-label">Pending</div>
                    <div class="card-number"><?php echo $pending; ?></div>
                    <a href="reservation.php?filter=pending" class="card-link">Process <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card card-4">
                    <div class="card-bg-num"><?php echo $completed; ?></div>
                    <span class="card-icon"><i class="bi bi-patch-check-fill"></i></span>
                    <div class="card-label">Completed</div>
                    <div class="card-number"><?php echo $completed; ?></div>
                    <a href="reservation.php?filter=completed" class="card-link">View <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

        </div>

        <div class="recent-section">
            <div class="section-header">
                <h5><i class="bi bi-clipboard-check me-2"></i>Recent Reservations</h5>
                <a href="reservation.php">View All →</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recentRes) == 0): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        No reservations yet.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while ($row = mysqli_fetch_assoc($recentRes)): ?>
                            <tr>
                                <td><span class="res-code"><?php echo $row['reservation_code']; ?></span></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><strong style="color:var(--maroon);">₱<?php echo number_format($row['total_amount'], 2); ?></strong></td>
                                <td><span class="s-badge s-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td style="color:#aaa;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td><a href="view-reservation.php?id=<?php echo $row['id']; ?>" class="btn-eye"><i class="bi bi-eye"></i></a></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>