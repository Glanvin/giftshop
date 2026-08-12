
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Capitol University Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body { 
            margin: 0; 
        }
        .custombg, 
        .navbar { 
            padding-left: 180px; 
            padding-right: 180px; 
        }
        .custombg { 
            background-color: #6d1223; 
            color: white; 
            padding-top: 10px; 
            padding-bottom: 10px; 
        }
        .toptext { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            font-size: 18px; 
        }
        .left-info { 
            display: flex; 
            gap: 30px; 
        }
        p { 
            margin: 0; 
        }
        .navbar-nav { 
            width: 100%; 
            height: 40px; 
            display: flex; 
            align-items: center; 
            margin: 5px 0px; 
        }
        .logo-gold-outline { 
            height: 60px; 
            width: 60px; 
            object-fit: cover; 
            border: 3px solid #d4af37; 
            border-radius: 50%; 
        }
        .customnav { 
            background-color: #7f1429; 
        }
        .nav-link { 
            color: white !important; 
        }
        .center-links { 
            display: flex; 
            gap: 30px; 
            margin-left: auto; 
            margin-right: auto; 
        }
        li { 
            font-size: 20px; 
            font-weight: bold; 
            list-style: none; 
        }
        .brand-text { 
            color: white; 
            margin-left: 10px; 
        }
        .brand-text h1 { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 26px; 
            margin: 0; 
            font-weight: bold; 
            line-height: 1; 
        }
        .brand-text span { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            display: block; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-top: 2px; 
        }
        .btn-login { 
            color: white; 
            border: 1px solid white; 
            background: transparent; 
            padding: 8px 20px; 
            font-size: 12px; 
            font-weight: bold; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .btn-login:hover { 
            background: rgba(255,255,255,0.15); 
        }
        .cart-btn { 
            background-color: #d4af37; 
            color: #6d1223 !important; 
            font-weight: bold; 
            border-radius: 20px; 
            padding: 6px 20px; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 6px; 
            font-size: 14px; 
        }
        .cart-btn:hover { 
            background-color: #c9a030; 
        }
        .profile-dropdown { 
            position: relative; 
        }
        .profile-btn { 
            background: rgba(255,255,255,0.15); 
            border: 1px solid rgba(255,255,255,0.4); 
            color: white; 
            border-radius: 20px; 
            padding: 6px 16px; 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            font-size: 14px; 
            font-weight: 600; 
        }
        .profile-btn:hover { 
            background: rgba(255,255,255,0.25); 
        }
        .dropdown-menu-custom { 
            position: absolute; 
            right: 0; 
            top: 44px; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15); 
            min-width: 200px; 
            z-index: 9999; 
            display: none; 
            overflow: hidden; 
        }
        .dropdown-menu-custom.show { 
            display: block; 
        }
        .dropdown-header-custom { 
            background: #6d1223; 
            color: white; 
            padding: 14px 16px; 
        }
        .dropdown-header-custom .name { 
            font-weight: 700; 
            font-size: 15px; 
        }
        .dropdown-header-custom .role { 
            font-size: 11px; 
            opacity: 0.8; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
        .dropdown-item-custom { 
            padding: 11px 16px; 
            color: #333; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            font-size: 14px; 
        }
        .dropdown-item-custom:hover { 
            background: #f8f0f0; 
            color: #6d1223; 
        }
        .dropdown-divider-custom { 
            border-top: 1px solid #eee; 
            margin: 4px 0; 
        }
        .dropdown-item-custom.logout { 
            color: #dc3545; 
        }
        .logout-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
            display: none; 
            z-index: 99999; 
            justify-content: center; 
            align-items: center; 
        }
        .logout-overlay.show { 
            display: flex; 
        }
        .logout-modal { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            text-align: center; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.3); 
            max-width: 320px; 
        }
        .logout-modal h5 { 
            color: #6d1223; 
            margin-bottom: 20px; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

    <!-- Logout Modal -->
    <div id="logoutOverlay" class="logout-overlay">
        <div class="logout-modal">
            <h5>Are you sure you want to log out?</h5>
            <div class="d-flex gap-2 justify-content-center">
                <button onclick="closeLogoutModal()" class="btn btn-secondary btn-sm px-4">Cancel</button>
                <a href="/giftshop/logout.php" class="btn btn-sm px-4" style="background:#6d1223; color:white;">Yes, Log Out</a>
            </div>
        </div>
    </div>

    <!-- Top Bar -->
    <div class="custombg">
        <div class="toptext">
            <div class="left-info">
                <p>✆ Support: 000-000-00000</p>
                <p>🖂 support@cu.edu.ph</p>
            </div>
            <div class="schedule">
                <p>⏱︎ Mon-Fri: 8:00 AM - 5:00 PM</p>
            </div>
        </div>
    </div>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-sm customnav">
        <div class="navbar-nav d-flex justify-content-between align-items-center w-100">

            <!-- Logo -->
            <div class="d-flex align-items-center">
                <img src="../Product-Images/culogo.jpg" class="logo-gold-outline" alt="Logo">
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

                <!-- Cart Button-->
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
                        <a href="logout.php" class="dropdown-item-custom logout" onclick="showLogoutModal(); return false;">
                            <i class="bi bi-box-arrow-right"></i> Log Out
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CART_KEY = 'universityGiftshopCart';

        function updateHeaderCount() {
            try {
                const cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
                const total = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);
                const badge = document.getElementById('cart-count');
                if (badge) {
                    badge.innerText = total;
                    badge.style.display = total > 0 ? 'inline-block' : 'none';
                }
            } catch(e) {}
        }

        function toggleDropdown(e) {
            e.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const dd = document.querySelector('.profile-dropdown');
            if (dd && !dd.contains(e.target)) {
                document.getElementById('profileDropdown').classList.remove('show');
            }
        });

        function showLogoutModal()  { 
            document.getElementById('logoutOverlay').classList.add('show'); 
        }
        function closeLogoutModal() { 
            document.getElementById('logoutOverlay').classList.remove('show'); 
        }

        document.addEventListener('DOMContentLoaded', updateHeaderCount);
        window.updateHeaderCount = updateHeaderCount;
        window.CART_KEY = CART_KEY;
    </script>

</body>
</html>