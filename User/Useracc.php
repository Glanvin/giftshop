<?php 
    session_start();
    include('../connection.php');
    //checks to see whether the user has login and if not, redirected to login page
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    // gets the user logged in id
    $user_id = $_SESSION['user_id'];

    // Fetch user information from the database
    $user_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($user_query);
    // Count total reservations made by the user
    $total_res_query = mysqli_query($con, "SELECT COUNT(*) as total FROM reservations WHERE user_id = '$user_id'");
    $total_res = mysqli_fetch_assoc($total_res_query)['total'];
    // Count active reservations 
    $active_res_query = mysqli_query($con, "SELECT COUNT(*) as active FROM reservations WHERE user_id = '$user_id' AND status IN ('pending', 'confirmed', 'ready')");
    $active_res = mysqli_fetch_assoc($active_res_query)['active'];
    // Fetch reservation history with item count for each reservation
    $history_query = mysqli_query($con, "SELECT *, 
        (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) as item_count
        FROM reservations r 
        WHERE user_id = '$user_id' 
        ORDER BY created_at DESC");
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
    
    <style>
        :root {
            --brand-maroon: #8b1d2e;
            --brand-light-maroon: #b32a3c;
            --brand-gold: #d4af37;
            --bg-neutral: #f8f9fa;
        }

        body {
            background-color: var(--bg-neutral);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        .dashboard-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px 40px;
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            color: var(--brand-maroon);
            font-size: 36px;
            margin-bottom: 5px;
        }
        .card-header-custom {
            background-color: var(--brand-maroon);
            color: white;
            padding: 12px 20px;
            font-size: 20px;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            align-items: center;
        }
        .card-main {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
        }
        .profile-circle {
            width: 100px;
            height: 100px;
            background-color: var(--brand-maroon);
            color: white;
            font-size: 40px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 20px auto 15px;
        }
        .profile-name {
            font-family: 'Playfair Display', serif;
            color: var(--brand-maroon);
            font-size: 26px;
            margin-bottom: 5px;
        }
        .role-badge {
            background-color: var(--brand-gold);
            color: #4a3800;
            font-weight: bold;
            padding: 4px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: capitalize;
        }
        .info-box {
            background-color: #fcfcfc;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 15px;
        }
        .info-item {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .info-item i {
            color: var(--brand-maroon);
            width: 25px;
        }
        .btn-browse {
            background-color: var(--brand-light-maroon);
            color: white;
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: 0.3s;
        }
        .table thead th {
            background-color: var(--brand-maroon);
            color: white;
            font-size: 12px;
            text-transform: uppercase;
            padding: 15px 10px;
            border: none;
        }
        .table tbody td {
            padding: 20px 10px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        .res-code {
            color: var(--brand-maroon);
            font-weight: 700;
        }
        .status-pill {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .status-pending { 
            background-color: #ffc107; 
            color: #5c4500; 
        }
        .status-confirmed { 
            background-color: var(--brand-maroon); 
            color: white; 
        }
        .btn-view {
            border: 1px solid var(--brand-maroon);
            color: var(--brand-maroon);
            background: white;
            border-radius: 6px;
            padding: 4px 15px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 70px;
        }
        .btn-upload {
            background-color: var(--brand-maroon);
            color: white;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 5px;
        }
        @media (max-width: 992px) {
            .dashboard-container { padding: 15px; }
        }
    </style>
    <?php include ("../includes/header.php"); ?>
</head>
<body>

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


</body>
            <!-- footer include temporary file -->
            <?php include '../includes/footer.php'; ?>
</html>