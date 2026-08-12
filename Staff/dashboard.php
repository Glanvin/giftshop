<?php
session_start();
//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

include('../connection.php');
// getting the total num of products
$totalProducts = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products"))['c'];
// getting the total num of products that are low on stocks
$lowStock      = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
// getting the total num of pending reservation
$pending       = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status = 'pending'"))['c'];
// getting the total num of completed reservations
$completed     = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status = 'completed'"))['c'];
// get recent reservation
$recentRes = mysqli_query($con, "SELECT r.reservation_code, r.total_amount, r.status, r.created_at, u.full_name
                                  FROM reservations r
                                  JOIN users u ON r.user_id = u.id
                                  ORDER BY r.created_at DESC LIMIT 5");
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
    <style>
        :root {
            --maroon:      #6d1223;
            --maroon-dark: #4d0d19;
            --maroon-light:#fdf0f2;
            --gold:        #d4af37;
            --bg:          #f7f3ef;
        }
        { 
            box-sizing: border-box; 
        }
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: var(--bg); 
            margin: 0; 
        }
        .page-wrap { 
            padding: 40px 60px; 
        }
        .welcome-section { 
            margin-bottom: 36px; 
        }
        .welcome-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: var(--maroon);
            margin: 0 0 4px;
        }
        .welcome-section p { 
            color: #aaa; font-size: 14px; margin: 0; }

        .stat-card {
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: default;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.18);
        }
        .card-1 {
            background: var(--maroon);
            color: white;
            box-shadow: 0 8px 24px rgba(109,18,35,0.35);
        }
        .card-2 {
            background: var(--gold);
            color: #2a1a00;
            box-shadow: 0 8px 24px rgba(212,175,55,0.4);
        }
        .card-3 {
            background: white;
            color: var(--maroon);
            border: 3px solid var(--maroon);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .card-4 {
            background: #1a1a2e;
            color: white;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .card-bg-num {
            position: absolute;
            right: -10px;
            bottom: -20px;
            font-size: 120px;
            font-weight: 900;
            font-family: 'Playfair Display', serif;
            opacity: 0.08;
            line-height: 1;
            user-select: none;
        }
        .card-icon {
            font-size: 2rem;
            margin-bottom: 16px;
            display: block;
        }
        .card-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            opacity: 0.7;
            margin-bottom: 8px;
        }
        .card-number {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 16px;
        }
        .card-link {
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            opacity: 0.8;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .card-link:hover { 
            opacity: 1; 
        }
        .card-1 .card-link, .card-4 .card-link { 
            color: rgba(255,255,255,0.9); 
        }
        .card-2 .card-link { 
            color: #2a1a00; 
        }
        .card-3 .card-link { 
            color: var(--maroon); 
        }
        .recent-section { 
            margin-top: 36px; 
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .section-header h5 {
            font-family: 'Playfair Display', serif;
            color: var(--maroon);
            font-size: 22px;
            margin: 0;
        }
        .section-header a {
            font-size: 13px;
            font-weight: 700;
            color: var(--maroon);
            text-decoration: none;
            border-bottom: 2px solid var(--gold);
            padding-bottom: 2px;
        }

        .table-wrap {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }
        .table-wrap table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table-wrap thead tr { 
            background: var(--maroon); 
        }
        .table-wrap thead th { 
            color: white; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            padding: 14px 20px; 
            font-weight: 600; 
            border: none; 
        }
        .table-wrap tbody td { 
            padding: 16px 20px; 
            font-size: 13px; 
            border-bottom: 1px solid #f5f5f5; 
            vertical-align: middle; 
        }
        .table-wrap tbody tr:last-child td { 
            border-bottom: none; 
        }
        .table-wrap tbody tr:hover { 
            background: var(--maroon-light); 
        }

        .res-code { 
            font-weight: 800; 
            color: var(--maroon); 
            font-family: monospace; 
            font-size: 14px; 
        }

        .s-badge { 
            padding: 5px 14px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: 700; 
        }
        .s-pending   { 
            background: #fff3e0; 
            color: #e65100; 
        }
        .s-confirmed { 
            background: #e3f2fd; 
            color: #1565c0; 
        }
        .s-ready     { 
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

        .btn-eye {
            background: var(--maroon-light);
            color: var(--maroon);
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-eye:hover { 
            background: var(--maroon); 
            color: white; 
        }

        .empty-state { 
            text-align: center; 
            padding: 50px; 
            color: #ccc; 
        }
        .empty-state i { 
            font-size: 3rem;
            display: block; 
            margin-bottom: 10px; 
        }
        .stat-card { 
            animation: cardIn 0.5s ease both; 
        }
        .stat-card:nth-child(1) { 
            animation-delay: 0.05s; 
        }
        .stat-card:nth-child(2) { 
            animation-delay: 0.15s; 
        }
        .stat-card:nth-child(3) { 
            animation-delay: 0.25s; 
        }
        .stat-card:nth-child(4) { 
            animation-delay: 0.35s; 
        }
        @keyframes cardIn {
            from { 
                opacity: 0; 
                transform: translateY(24px); 
            }
            to   { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @media (max-width: 1200px) { .page-wrap { padding: 24px 20px; } }
    </style>
</head>
<body>

    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">

        <div class="welcome-section">
            <h2>Good day, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
            <p><i class="bi bi-calendar3 me-1"></i><?php echo date('l, F d, Y'); ?></p>
        </div>
]
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
                    <a href="view-reservation.php" class="card-link">Process <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card card-4">
                    <div class="card-bg-num"><?php echo $completed; ?></div>
                    <span class="card-icon"><i class="bi bi-patch-check-fill"></i></span>
                    <div class="card-label">Completed</div>
                    <div class="card-number"><?php echo $completed; ?></div>
                    <a href="view-reservation.php?filter=completed" class="card-link">View <i class="bi bi-arrow-right"></i></a>
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
                        <!-- if no reservations was found -->
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
                            <!-- this loops throught the recent reservations -->
                            <?php while ($row = mysqli_fetch_assoc($recentRes)): ?>
                            <tr>
                                <!-- reservation code -->
                                <td><span class="res-code"><?php echo $row['reservation_code']; ?></span></td>
                                <!-- user name -->
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <!-- total amount -->
                                <td><strong style="color:var(--maroon);">₱<?php echo number_format($row['total_amount'], 2); ?></strong></td>
                                <!-- status -->
                                <td><span class="s-badge s-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <!-- date created -->
                                <td style="color:#aaa;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <!-- view button -->
                                <td><a href="view-reservation.php" class="btn-eye"><i class="bi bi-eye"></i></a></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>