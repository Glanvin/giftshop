<?php
session_start();
include('../connection.php');
//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Counts
foreach (['pending','confirmed','ready','completed','cancelled'] as $s) {
    $r = mysqli_query($con, "SELECT COUNT(*) as c FROM reservations WHERE status='$s'");
    $counts[$s] = mysqli_fetch_assoc($r)['c'];
}

// Main query
if ($filter !== 'all') {
    $safe   = mysqli_real_escape_string($con, $filter);
    $result = mysqli_query($con, "SELECT r.*, u.full_name, u.email,
                (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) AS item_count
                FROM reservations r JOIN users u ON r.user_id = u.id
                WHERE r.status = '$safe' ORDER BY r.created_at DESC");
} else {
    $result = mysqli_query($con, "SELECT r.*, u.full_name, u.email,
                (SELECT COUNT(*) FROM reservation_items WHERE reservation_id = r.id) AS item_count
                FROM reservations r JOIN users u ON r.user_id = u.id
                ORDER BY r.created_at DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reservations – Staff</title>
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
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
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
      .stat-strip {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
        flex-wrap: wrap;
      }
      .stat-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        border-radius: 50px;
        padding: 10px 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid transparent;
      }
      .stat-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      }
      .stat-pill.active {
        border-color: var(--maroon);
        background: var(--maroon);
      }
      .stat-pill.active .pill-label,
      .stat-pill.active .pill-count {
        color: white;
      }
      .pill-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
      }
      .pill-label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
      }
      .pill-count {
        font-size: 18px;
        font-weight: 800;
        color: var(--maroon);
        margin-left: 4px;
      }
      .pill-all {
        background: var(--maroon);
      }
      .pill-all .pill-label,
      .pill-all .pill-count {
        color: white;
      }
      .pill-all.active {
        border-color: var(--maroon-dark);
      }
      .table-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
      }
      .table-card table {
        width: 100%;
        border-collapse: collapse;
      }
      .table-card thead tr {
        background: var(--maroon);
      }
      .table-card thead th {
        color: white;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 16px 20px;
        font-weight: 600;
        border: none;
      }

      .table-card tbody td {
        padding: 16px 20px;
        font-size: 13px;
        border-bottom: 1px solid #f5f0eb;
        vertical-align: middle;
      }
      .table-card tbody tr:last-child td {
        border-bottom: none;
      }
      .table-card tbody tr:hover {
        background: #fdf8f5;
      }
      .table-card tbody tr:nth-child(even) {
        background: #fdfaf7;
      }
      .table-card tbody tr:nth-child(even):hover {
        background: #fdf0ee;
      }

      .res-code {
        font-weight: 800;
        color: var(--maroon);
        font-family: monospace;
        font-size: 14px;
        letter-spacing: 0.5px;
      }
      .s-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
      }
      .s-pending {
        background: #fff3e0;
        color: #e65100;
      }
      .s-confirmed {
        background: #e3f2fd;
        color: #1565c0;
      }
      .s-ready {
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
      .btn-view {
        background: #fdf0f2;
        color: var(--maroon);
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
      }
      .btn-view:hover {
        background: var(--maroon);
        color: white;
      }
      .btn-edit {
        background: #fdf6e3;
        color: #8a6500;
        border: none;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
      }
      .btn-edit:hover {
        background: var(--gold);
        color: white;
      }

      .empty-state {
        text-align: center;
        padding: 60px;
        color: #ccc;
      }
      .empty-state i {
        font-size: 3rem;
        display: block;
        margin-bottom: 12px;
      }
      .avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--maroon);
        color: white;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
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
      <div class="page-header">
        <div>
          <h2><i class="bi bi-clipboard-check me-2"></i>Reservations</h2>
          <p>Manage and track all customer reservations</p>
        </div>
      </div>

      <?php if (isset($_SESSION['res_message'])): ?>
      <div
        class="alert alert-success alert-dismissible fade show mb-4"
        style="border-radius: 12px">
        <!-- this show  a session message if exists (for actions like add/edit/cancel reservation) -->
        <?php echo $_SESSION['res_message']; unset($_SESSION['res_message']); ?>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="alert"
        ></button>
      </div>
      <?php endif; ?>

       <!-- Status filter (All, Pending, Confirmed, Ready, Completed, Cancelled) -->
      <div class="stat-strip">
        <a
          href="?filter=all"
          class="stat-pill pill-all <?php echo $filter=='all' ? 'active':''; ?>"
          style="<?php echo $filter!='all' ? 'background:white;' : ''; ?>"
        >
          <span
            class="pill-label"
            style="<?php echo $filter!='all' ? 'color:#555;' : 'color:white;'; ?>"
            >All</span>
          <span
            class="pill-count"
            style="<?php echo $filter!='all' ? 'color:var(--maroon);' : 'color:white;'; ?>"
          >
            <?php echo array_sum($counts); ?>
          </span>
        </a>
        <!-- this loops through each and individual status -->
        <?php $pillColors = [ 'pending' => '#e65100', 'confirmed' => '#1565c0',
        'ready' => '#2e7d32', 'completed' => '#4527a0', 'cancelled' =>
        '#ad1457', ]; $pillLabels = [ 'pending' => 'Pending', 'confirmed' =>
        'Confirmed', 'ready' => 'Ready', 'completed' => 'Completed', 'cancelled'
        => 'Cancelled', ]; foreach ($pillLabels as $key => $label): ?>
        <a
          href="?filter=<?php echo $key; ?>"
          class="stat-pill <?php echo $filter==$key ? 'active':''; ?>"
        >
          <span
            class="pill-dot"
            style="background:<?php echo $pillColors[$key]; ?>;"
          ></span>
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
            <!-- this shows an empty state if there are no results found -->
            <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <i class="bi bi-inbox"></i>
                  No reservations found.
                </div>
              </td>
            </tr>
            <?php else: ?> <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td>
                <span class="res-code"
                  ><?php echo $row['reservation_code']; ?></span
                >
              </td>
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
                <span
                  class="badge rounded-pill"
                  style="
                    background: #f0eae8;
                    color: var(--maroon);
                    font-weight: 700;
                  "
                >
                  <?php echo $row['item_count']; ?> item(s)
                </span>
              </td>
              <td>
                <strong style="color: var(--maroon)"
                  >₱<?php echo number_format($row['total_amount'], 2);
                  ?></strong
                >
              </td>
              <td>
                <span class="s-badge s-<?php echo $row['status']; ?>"
                  ><?php echo ucfirst($row['status']); ?></span
                >
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a
                    href="view-reservation.php?id=<?php echo $row['id']; ?>"
                    class="btn-view"
                  >
                    <i class="bi bi-eye"></i> View
                  </a>
                  <a
                    href="edit-reservation.php?id=<?php echo $row['id']; ?>"
                    class="btn-edit"
                  >
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?> <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  <?php include('../includes/footer-staff.php'); ?>
</html>
