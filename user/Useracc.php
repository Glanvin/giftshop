<?php
require __DIR__ . '/../backend/user/useracc-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Dashboard - <?php echo htmlspecialchars($user_data['full_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-user">
    <?php include("../includes/header.php"); ?>

<div class="dashboard-container">
    <div class="text-center mt-3 mb-5">
        <h1 class="page-title">My Profile</h1>
        <p class="text-muted small">Manage your profile and view reservation history</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <div class="card-main">
                <div class="card-header-custom">
                    <i class="bi bi-person-fill me-2"></i> Profile
                </div>
                <div class="text-center p-3">
                    <div class="profile-circle shadow-sm">
                        <?php echo strtoupper(substr($user_data['full_name'], 0, 1)); ?>
                    </div>
                    <h2 class="profile-name"><?php echo htmlspecialchars($user_data['full_name']); ?></h2>
                    <span class="role-badge"><?php echo htmlspecialchars($user_data['role'] ?? 'Student'); ?></span>

                    <div class="info-box text-start">
                        <div class="info-item">
                            <i class="bi bi-envelope-fill"></i> <?php echo htmlspecialchars($user_data['email']); ?>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-telephone-fill"></i> <?php echo !empty($user_data['phone']) ? htmlspecialchars($user_data['phone']) : '555-0000'; ?>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-person-badge-fill"></i> STU<?php echo date('Y'); ?>001
                        </div>
                        <div class="info-item">
                            <i class="bi bi-calendar-check-fill"></i> Member since <?php echo date('M Y', strtotime($user_data['created_at'] ?? 'now')); ?>
                        </div>
                    </div>

                    <div class="px-3 pb-3">
                        <a href="Shopuser.php" style="text-decoration: none;">
                            <button class="btn-browse">
                                <i class="bi bi-lock-fill me-2"></i> Browse Products
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-main p-3 text-center">
                <h5 style="color: var(--brand-maroon); font-family: 'Playfair Display', serif; font-weight: bold;">
                    <i class="bi bi-graph-up-arrow me-1"></i> Quick Stats
                </h5>
                <hr class="my-2 opacity-25">
                <div class="row">
                    <div class="col-6 border-end">
                        <h4 class="mb-0 fw-bold" style="color: var(--brand-maroon);"><?php echo $total_res; ?></h4>
                        <small class="text-muted uppercase" style="font-size: 9px; font-weight: 800;">TOTAL ORDERS</small>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-0 fw-bold" style="color: var(--brand-gold);"><?php echo $active_res; ?></h4>
                        <small class="text-muted uppercase" style="font-size: 9px; font-weight: 800;">ACTIVE</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xl-9">
            <div class="card-main overflow-hidden">
                <div class="card-header-custom">
                    <i class="bi bi-clock-history me-2"></i> Reservation History
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 15%">RESERVATION CODE</th>
                                <th style="width: 10%">DATE</th>
                                <th style="width: 10%">ITEMS</th>
                                <th style="width: 12%">TOTAL AMOUNT</th>
                                <th style="width: 15%">STATUS</th>
                                <th style="width: 12%">EXPIRES</th>
                                <th class="text-center" style="width: 10%">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($history_query) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($history_query)): ?>
                                <tr>
                                    <td class="res-code"><?php echo $row['reservation_code']; ?></td>
                                    <td>
                                        <div class="fw-medium"><?php echo date('M d,', strtotime($row['created_at'])); ?></div>
                                        <div class="text-muted small"><?php echo date('Y', strtotime($row['created_at'])); ?></div>
                                    </td>
                                    <td class="text-muted">
                                        <?php echo $row['item_count'] ?? 1; ?> item(s)
                                    </td>
                                    <td class="fw-bold">₱<?php echo number_format($row['total_amount'], 2); ?></td>
                                    <td>
                                        <?php
                                            $status = strtolower($row['status']);
                                            if($status == 'pending'): ?>
                                            <span class="status-pill status-pending">
                                                <i class="bi bi-clock"></i> Pending
                                            </span>
                                        <?php elseif($status == 'confirmed'): ?>
                                            <span class="status-pill status-confirmed">
                                                <i class="bi bi-check2"></i> Confirmed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark"><?php echo ucfirst($status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo date('M d, Y', strtotime($row['expiry_date'])); ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-center">
                                            <a href="view-my-reservation.php?id=<?php echo $row['id']; ?>" class="btn-view">
                                                <i class="bi bi-eye"></i>
                                                View
                                            </a>
                                            <?php if($status == 'confirmed'): ?>
                                            <a href="#" class="btn-upload">
                                                <i class="bi bi-upload"></i>
                                                Upload Receipt
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No reservations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>