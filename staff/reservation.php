<?php
require __DIR__ . '/../backend/staff/reservation-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reservations – Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css" />
  </head>
  <body class="area-staff">
    <!-- header -->
    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">
      <div class="page-header">
        <div>
          <h2><i class="bi bi-clipboard-check me-2"></i>Reservations</h2>
          <p>Manage and track all customer reservations</p>
        </div>
      </div>

      <?php if (isset($_SESSION['res_message'])): ?>
      <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 12px">
        <?php echo $_SESSION['res_message']; unset($_SESSION['res_message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php endif; ?>

      <!-- Status filter (All, Pending, Confirmed, Ready, Completed, Cancelled) -->
      <div class="stat-strip">
        <a
          href="?filter=all"
          class="stat-pill pill-all <?php echo $filter=='all' ? 'active':''; ?>"
          style="<?php echo $filter!='all' ? 'background:white;' : ''; ?>"
        >
          <span class="pill-label" style="<?php echo $filter!='all' ? 'color:#555;' : 'color:white;'; ?>">All</span>
          <span class="pill-count" style="<?php echo $filter!='all' ? 'color:var(--maroon);' : 'color:white;'; ?>">
            <?php echo array_sum($counts); ?>
          </span>
        </a>
        <?php
        $pillColors = [ 'pending' => '#e65100', 'confirmed' => '#1565c0',
                        'ready' => '#2e7d32', 'completed' => '#4527a0', 'cancelled' => '#ad1457' ];
        $pillLabels = [ 'pending' => 'Pending', 'confirmed' => 'Confirmed',
                        'ready' => 'Ready', 'completed' => 'Completed', 'cancelled' => 'Cancelled' ];
        foreach ($pillLabels as $key => $label): ?>
        <a href="?filter=<?php echo $key; ?>" class="stat-pill <?php echo $filter==$key ? 'active':''; ?>">
          <span class="pill-dot" style="background:<?php echo $pillColors[$key]; ?>;"></span>
          <span class="pill-label"><?php echo $label; ?></span>
          <span class="pill-count"><?php echo $counts[$key] ?? 0; ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Reservations Table -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Code</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Items</th>
              <th>Total</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <i class="bi bi-inbox"></i>
                  No reservations found.
                </div>
              </td>
            </tr>
            <?php else: ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><span class="res-code"><?php echo $row['reservation_code']; ?></span></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar">
                      <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
                    </div>
                    <div>
                      <div class="fw-bold" style="font-size: 13px">
                        <?php echo htmlspecialchars($row['full_name']); ?>
                      </div>
                      <div class="text-muted" style="font-size: 11px">
                        <?php echo htmlspecialchars($row['email']); ?>
                      </div>
                    </div>
                  </div>
                </td>
                <td style="color: #999; font-size: 12px">
                  <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                </td>
                <td>
                  <span class="badge rounded-pill"
                    style="background: #f0eae8; color: var(--maroon); font-weight: 700;">
                    <?php echo $row['item_count']; ?> item(s)
                  </span>
                </td>
                <td>
                  <strong style="color: var(--maroon)">₱<?php echo number_format($row['total_amount'], 2); ?></strong>
                </td>
                <td>
                  <span class="s-badge s-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="view-reservation.php?id=<?php echo $row['id']; ?>" class="btn-view">
                      <i class="bi bi-eye"></i> View
                    </a>
                    <a href="edit-reservation.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>