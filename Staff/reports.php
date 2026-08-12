<?php
session_start();
include('../connection.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

// ---- Total Revenue (completed reservations only) ----
$totalRevenue = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT SUM(total_amount) as revenue FROM reservations WHERE status = 'completed'"
))['revenue'] ?? 0;

// ---- Total Reservations ----
$totalRes = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT COUNT(*) as c FROM reservations"
))['c'];

// ---- Pending amount ----
$pendingRevenue = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT SUM(total_amount) as revenue FROM reservations WHERE status = 'pending'"
))['revenue'] ?? 0;

// ---- Most Reserved Products (top 5) ----
$topProducts = mysqli_query($con,
    "SELECT p.name, p.image_url, SUM(ri.quantity) as total_reserved
     FROM reservation_items ri
     JOIN products p ON ri.product_id = p.id
     GROUP BY ri.product_id
     ORDER BY total_reserved DESC
     LIMIT 5"
);

// ---- Reservations per month (last 6 months) ----
$monthlyData = mysqli_query($con,
    "SELECT DATE_FORMAT(created_at, '%b %Y') as month,
            DATE_FORMAT(created_at, '%Y-%m') as month_key,
            COUNT(*) as total,
            SUM(total_amount) as revenue
     FROM reservations
     GROUP BY month_key
     ORDER BY month_key DESC
     LIMIT 6"
);
$months = []; $monthlyCounts = []; $monthlyRevenue = [];
while ($row = mysqli_fetch_assoc($monthlyData)) {
    array_unshift($months,        $row['month']);
    array_unshift($monthlyCounts, (int)$row['total']);
    array_unshift($monthlyRevenue,(float)$row['revenue']);
}

// ---- Stock Summary ----
$stockOk  = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity >
5"))['c']; $stockLow = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as
c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
$stockOut = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM
products WHERE stock_quantity <= 0"))['c']; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports – Staff</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      :root {
        --maroon: #6d1223;
        --maroon-dark: #4d0d19;
        --gold: #d4af37;
        --bg: #f7f3ef;
      }
      body {
        font-family: "DM Sans", sans-serif;
        background: var(--bg);
        margin: 0;
      }
      .page-wrap {
        padding: 40px 60px;
      }
      .page-header {
        margin-bottom: 30px;
      }
      .page-header h2 {
        font-family: "Playfair Display", serif;
        color: var(--maroon);
        font-size: 34px;
        margin: 0;
      }
      .page-header p {
        color: #aaa;
        font-size: 13px;
        margin: 4px 0 0;
      }

      .summary-card {
        background: white;
        border-radius: 16px;
        padding: 28px 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
        border-left: 5px solid var(--maroon);
        height: 100%;
        transition: transform 0.2s;
      }
      .summary-card:hover {
        transform: translateY(-4px);
      }
      .summary-card.gold {
        border-color: var(--gold);
      }
      .summary-card.green {
        border-color: #2e7d32;
      }
      .card-icon {
        font-size: 2rem;
        margin-bottom: 12px;
        display: block;
      }
      .card-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        font-weight: 600;
      }
      .card-value {
        font-family: "Playfair Display", serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--maroon);
        line-height: 1;
        margin: 6px 0 0;
      }
      .summary-card.gold .card-value {
        color: #8a6500;
      }
      .summary-card.green .card-value {
        color: #2e7d32;
      }
      .section-title {
        font-family: "Playfair Display", serif;
        color: var(--maroon);
        font-size: 22px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .white-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
      }
      .product-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #f5f0eb;
      }
      .product-row:last-child {
        border-bottom: none;
      }
      .product-rank {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--maroon);
        color: white;
        font-weight: 800;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }
      .product-rank.gold-rank {
        background: var(--gold);
        color: #2a1a00;
      }
      .product-img-small {
        width: 44px;
        height: 44px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
        flex-shrink: 0;
      }
      .product-name-text {
        font-weight: 700;
        font-size: 14px;
        color: #333;
      }
      .product-qty {
        margin-left: auto;
        font-family: "Playfair Display", serif;
        font-size: 22px;
        font-weight: 700;
        color: var(--maroon);
      }
      .product-qty small {
        font-family: "DM Sans", sans-serif;
        font-size: 11px;
        color: #aaa;
        display: block;
        text-align: right;
      }
      .stock-row {
        margin-bottom: 18px;
      }
      .stock-label {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
      }
      .progress {
        height: 10px;
        border-radius: 20px;
        background: #f0f0f0;
      }
      .progress-bar {
        border-radius: 20px;
      }
      .chart-wrap {
        position: relative;
        height: 260px;
      }
      @media (max-width: 1200px) {
        .page-wrap {
          padding: 24px 20px;
        }
      }
    </style>
  </head>
  <body>
    <!-- header -->
    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">
      <!-- Page Header -->
      <div class="page-header">
        <h2><i class="bi bi-bar-chart-line me-2"></i>Reports</h2>
        <p>
          Overview of sales, reservations, and inventory as of <?php echo
          date('F d, Y'); ?>
        </p>
      </div>
      <!-- Summary -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="summary-card">
            <span class="card-icon" style="color: var(--maroon)"
              ><i class="bi bi-cash-coin"></i
            ></span>
            <div class="card-label">Total Revenue</div>
            <div class="card-value">
              ₱<?php echo number_format($totalRevenue, 2); ?>
            </div>
            <div style="font-size: 12px; color: #aaa; margin-top: 6px">
              From completed reservations
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="summary-card gold">
            <span class="card-icon" style="color: var(--gold)"
              ><i class="bi bi-hourglass-split"></i
            ></span>
            <div class="card-label">Pending Revenue</div>
            <div class="card-value">
              ₱<?php echo number_format($pendingRevenue, 2); ?>
            </div>
            <div style="font-size: 12px; color: #aaa; margin-top: 6px">
              Awaiting payment
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="summary-card green">
            <span class="card-icon" style="color: #2e7d32"
              ><i class="bi bi-clipboard-check"></i
            ></span>
            <div class="card-label">Total Reservations</div>
            <div class="card-value"><?php echo $totalRes; ?></div>
            <div style="font-size: 12px; color: #aaa; margin-top: 6px">
              All time
            </div>
          </div>
        </div>
      </div>
      <div class="row g-4 mb-4">
        <div class="col-lg-7">
          <div class="white-card">
            <div class="section-title">
              <i class="bi bi-graph-up"></i> Reservations Per Month
            </div>
            <div class="chart-wrap">
              <canvas id="monthlyChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="white-card">
            <div class="section-title">
              <i class="bi bi-boxes"></i> Stock Summary
            </div>
            <?php $total = $stockOk + $stockLow + $stockOut; $total = $total ?:
            1; ?>
            <div class="stock-row">
              <div class="stock-label">
                <span
                  ><i class="bi bi-check-circle-fill text-success me-2"></i>In
                  Stock</span>
                <span class="fw-bold"
                  ><?php echo $stockOk; ?>
                  products</span>
              </div>
              <div class="progress">
                <div
                  class="progress-bar bg-success"
                  style="width:<?php echo round($stockOk/$total*100); ?>%"
                ></div>
              </div>
            </div>

            <div class="stock-row">
              <div class="stock-label">
                <span
                  ><i
                    class="bi bi-exclamation-triangle-fill text-warning me-2"
                  ></i
                  >Low Stock</span
                >
                <span class="fw-bold"
                  ><?php echo $stockLow; ?>
                  products</span
                >
              </div>
              <div class="progress">
                <div
                  class="progress-bar bg-warning"
                  style="width:<?php echo round($stockLow/$total*100); ?>%"
                ></div>
              </div>
            </div>

            <div class="stock-row">
              <div class="stock-label">
                <span
                  ><i class="bi bi-x-circle-fill text-danger me-2"></i>Out of
                  Stock</span
                >
                <span class="fw-bold"
                  ><?php echo $stockOut; ?>
                  products</span
                >
              </div>
              <div class="progress">
                <div
                  class="progress-bar bg-danger"
                  style="width:<?php echo round($stockOut/$total*100); ?>%"
                ></div>
              </div>
            </div>

            <!-- chart -->
            <div style="max-width: 200px; margin: 20px auto 0">
              <canvas id="stockChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="white-card">
        <div class="section-title">
          <i class="bi bi-trophy"></i> Most Reserved Products
        </div>
        <?php if (mysqli_num_rows($topProducts) == 0): ?>
        <p class="text-muted text-center py-3">No reservation data yet.</p>
        <?php else: ?> <?php $rank = 1; while ($p =
        mysqli_fetch_assoc($topProducts)): ?>
        <div class="product-row">
          <div
            class="product-rank <?php echo $rank == 1 ? 'gold-rank' : ''; ?>"
          >
            <?php echo $rank; ?>
          </div>
          <?php if ($p['image_url']): ?>
          <img
            src="../<?php echo htmlspecialchars($p['image_url']); ?>"
            class="product-img-small"
            alt=""
          />
          <?php else: ?>
          <div
            class="product-img-small d-flex align-items-center justify-content-center"
            style="background: #f0f0f0"
          >
            <i class="bi bi-image text-muted"></i>
          </div>
          <?php endif; ?>
          <div class="product-name-text">
            <?php echo htmlspecialchars($p['name']); ?>
          </div>
          <div class="product-qty">
            <?php echo $p['total_reserved']; ?>
            <small>reserved</small>
          </div>
        </div>
        <?php $rank++; endwhile; ?> <?php endif; ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      new Chart(document.getElementById('monthlyChart'), {
          type: 'bar',
          data: {
              labels: <?php echo json_encode($months); ?>,
              datasets: [{
                  label: 'Reservations',
                  data: <?php echo json_encode($monthlyCounts); ?>,
                  backgroundColor: 'rgba(109,18,35,0.8)',
                  borderRadius: 8,
                  borderSkipped: false,
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: {
                  y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                  x: { grid: { display: false } }
              }
          }
      });
      new Chart(document.getElementById('stockChart'), {
          type: 'doughnut',
          data: {
              labels: ['In Stock', 'Low Stock', 'Out of Stock'],
              datasets: [{
                  data: [<?php echo $stockOk; ?>, <?php echo $stockLow; ?>, <?php echo $stockOut; ?>],
                  backgroundColor: ['#2e7d32', '#f57c00', '#c62828'],
                  borderWidth: 0,
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } }
              },
              cutout: '65%'
          }
      });
    </script>
  </body>
  <!-- footer -->
  <?php include('../includes/footer-staff.php'); ?>
</html>
