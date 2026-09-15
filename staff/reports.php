<?php
require __DIR__ . '/../backend/staff/reports-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports – Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="area-staff">
    <!-- header -->
    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">
      <!-- Page Header -->
      <div class="page-header">
        <h2><i class="bi bi-bar-chart-line me-2"></i>Reports</h2>
        <p>Overview of sales, reservations, and inventory as of <?php echo date('F d, Y'); ?></p>
      </div>

      <!-- Summary -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="summary-card">
            <span class="card-icon" style="color: var(--maroon)"><i class="bi bi-cash-coin"></i></span>
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
            <span class="card-icon" style="color: var(--gold)"><i class="bi bi-hourglass-split"></i></span>
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
            <span class="card-icon" style="color: #2e7d32"><i class="bi bi-clipboard-check"></i></span>
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
            <?php $total = $stockOk + $stockLow + $stockOut; $total = $total ?: 1; ?>
            <div class="stock-row">
              <div class="stock-label">
                <span><i class="bi bi-check-circle-fill text-success me-2"></i>In Stock</span>
                <span class="fw-bold"><?php echo $stockOk; ?> products</span>
              </div>
              <div class="progress">
                <div class="progress-bar bg-success" style="width:<?php echo round($stockOk/$total*100); ?>%"></div>
              </div>
            </div>

            <div class="stock-row">
              <div class="stock-label">
                <span><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Low Stock</span>
                <span class="fw-bold"><?php echo $stockLow; ?> products</span>
              </div>
              <div class="progress">
                <div class="progress-bar bg-warning" style="width:<?php echo round($stockLow/$total*100); ?>%"></div>
              </div>
            </div>

            <div class="stock-row">
              <div class="stock-label">
                <span><i class="bi bi-x-circle-fill text-danger me-2"></i>Out of Stock</span>
                <span class="fw-bold"><?php echo $stockOut; ?> products</span>
              </div>
              <div class="progress">
                <div class="progress-bar bg-danger" style="width:<?php echo round($stockOut/$total*100); ?>%"></div>
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
        <?php else: ?>
          <?php $rank = 1; while ($p = mysqli_fetch_assoc($topProducts)): ?>
          <div class="product-row">
            <div class="product-rank <?php echo $rank == 1 ? 'gold-rank' : ''; ?>">
              <?php echo $rank; ?>
            </div>
            <?php if ($p['image_url']): ?>
            <img
              src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $p['image_url'])); ?>"
              class="product-img-small"
              alt=""
            />
            <?php else: ?>
            <div class="product-img-small d-flex align-items-center justify-content-center" style="background: #f0f0f0">
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
          <?php $rank++; endwhile; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php include '../includes/footer.php'; ?>
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
</html>