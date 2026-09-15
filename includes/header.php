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

        <!-- Logo -->
        <div class="d-flex align-items-center">
            <img src="../product-images/culogo.jpg" class="logo-gold-outline" alt="Logo">
            <div class="brand-text">
                <h1>Capitol University</h1>
                <span>Official Giftshop</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="center-links">
            <li class="nav-item">
                <a class="nav-link" href="Useracc.php">
                    <i class="bi bi-house"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Shopuser.php">
                    <i class="bi bi-bag"></i> Shop
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my-reservations.php">
                    <i class="bi bi-clipboard-check"></i> My Reservations
                </a>
            </li>
        </div>

        <div class="d-flex align-items-center gap-3">

            <!-- Cart Button -->
            <a href="cart.php" class="cart-btn">
                <i class="bi bi-cart-fill"></i>
                Cart
                <span id="cart-count" class="badge rounded-pill bg-danger" style="display:none; font-size:11px;">0</span>
            </a>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <button class="profile-btn" onclick="toggleDropdown(event)">
                    <i class="bi bi-person-circle"></i>
                    <span id="header-username">
                        <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Account'; ?>
                    </span>
                    <i class="bi bi-chevron-down" style="font-size:11px;"></i>
                </button>
                <div class="dropdown-menu-custom" id="profileDropdown">
                    <div class="dropdown-header-custom">
                        <div class="name">
                            <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Guest'; ?>
                        </div>
                        <div class="role">
                            <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : ''; ?>
                        </div>
                    </div>
                    <a href="Useracc.php" class="dropdown-item-custom">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    <a href="my-reservations.php" class="dropdown-item-custom">
                        <i class="bi bi-clipboard-check"></i> My Reservations
                    </a>
                    <div class="dropdown-divider-custom"></div>
                    <a href="../backend/logout.php" class="dropdown-item-custom logout" onclick="showLogoutModal(); return false;">
                        <i class="bi bi-box-arrow-right"></i> Log Out
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>

<script src="../assets/js/main.js"></script>