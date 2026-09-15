<!-- Logout Modal -->
<div id="logoutOverlay" class="logout-overlay">
    <div class="logout-modal">
        <h5>Are you sure you want to log out?</h5>
        <div class="d-flex gap-2 justify-content-center">
            <button onclick="closeLogoutModal()" class="btn btn-secondary btn-sm px-4">Cancel</button>
            <a href="../backend/logout.php" class="btn btn-sm px-4" style="background:#6d1223; color:white;">Yes, Log Out</a>
        </div>
    </div>
</div>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-sm customnav">
    <div class="navbar-nav d-flex justify-content-between align-items-center w-100">

        <div class="d-flex align-items-center">
            <img src="../product-images/culogo.jpg" class="logo-gold-outline" alt="Logo">
            <div class="brand-text">
                <h1>Staff Panel</h1>
                <span>Official Giftshop</span>
            </div>
        </div>

        <!-- Staff Navigation Links -->
        <div class="center-links">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="inventory.php">
                    <i class="bi bi-box-seam"></i> Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reservation.php">
                    <i class="bi bi-clipboard-check"></i> Reservations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reports.php">
                    <i class="bi bi-bar-chart"></i> Reports
                </a>
            </li>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <button class="profile-btn" onclick="toggleDropdown(event)">
                <i class="bi bi-person-circle"></i>
                <span>
                    <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Staff'; ?>
                </span>
                <i class="bi bi-chevron-down" style="font-size:11px;"></i>
            </button>
            <div class="dropdown-menu-custom" id="profileDropdown">
                <div class="dropdown-header-custom">
                    <div class="name">
                        <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Staff'; ?>
                    </div>
                    <div class="role">
                        <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Staff'; ?>
                    </div>
                </div>
                <div class="dropdown-divider-custom"></div>
                <a href="../backend/logout.php" class="dropdown-item-custom logout" onclick="showLogoutModal(); return false;">
                    <i class="bi bi-box-arrow-right"></i> Log Out
                </a>
            </div>
        </div>

    </div>
</nav>

<script src="../assets/js/main.js"></script>