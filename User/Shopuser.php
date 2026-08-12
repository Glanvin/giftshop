<?php
session_start();

include('../connection.php');

// add to cart action 
if (isset($_POST['add_to_cart'])) {
    //checks to see whether the user has login and if not, redirected to login page
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    // Initialize cart array if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Get product ID from POST method
    $productId = (int) $_POST['product_id'];
    $product   = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM products WHERE id = $productId"));

    if ($product) {
        // If product already in cart, increment quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
             // Add new product to cart
            $_SESSION['cart'][$productId] = [
                'name'      => $product['name'],
                'price'     => $product['price'],
                'image_url' => $product['image_url'],
                'quantity'  => 1
            ];
        }
        // Store product name for notification
        $_SESSION['cart_toast'] = $product['name'];
    }

    // Redirect back to same page
    $query = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header("Location: Shopuser.php$query");
    exit;
}

// Filters
$search   = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort']     ?? 'az';

$query = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
//search filter
if ($search) {
    $s = mysqli_real_escape_string($con, $search);
    $query .= " AND (p.name LIKE '%$s%' OR p.description LIKE '%$s%')";
}
// category filter
if ($category && $category != "All Categories") {
    $cat = mysqli_real_escape_string($con, $category);
    $query .= " AND p.category_id = '$cat'";
}
// add by sorting
switch ($sort) {
    case "za":   $query .= " ORDER BY p.name DESC"; break;
    case "low":  $query .= " ORDER BY p.price ASC"; break;
    case "high": $query .= " ORDER BY p.price DESC"; break;
    default:     $query .= " ORDER BY p.name ASC";
}

$result     = mysqli_query($con, $query);
$categories = [1 => "Textbook", 2 => "Uniform", 3 => "PE Uniform", 4 => "Merchandise"];

// Count total items
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #faf9f6; 
            margin: 0; 
        }
        .page-wrap { 
            padding: 50px 180px; 
        }
        .title { 
            font-family: 'Playfair Display', serif; 
            color: #6d1223; 
            font-size: 40px; 
            letter-spacing: -1px; 
        }
        .search-box { 
            background: white; 
            padding: 25px 30px; 
            border-radius: 14px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.06); 
            margin-bottom: 40px; 
        }
        .btn-maroon { 
            background-color: #6d1223 !important; 
            color: white !important; 
            border: none; 
            border-radius: 8px; 
        }
        .btn-maroon:hover { 
            background-color: #4d0d19 !important; 
        }
        .form-control:focus, .form-select:focus { 
            border-color: #6d1223; 
            box-shadow: none; 
        }
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            transition: transform 0.25s, box-shadow 0.25s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(109,18,35,0.15);
        }
        .card-img-wrap {
            position: relative;
            height: 220px;
            background: #f7f4f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .product-card:hover .card-img-wrap img {
            transform: scale(1.05);
        }
        .no-img-placeholder {
            color: #ddd;
            font-size: 60px;
        }
        .cat-ribbon {
            position: absolute;
            top: 14px;
            left: 0;
            background: #6d1223;
            color: white;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 14px 4px 10px;
            border-radius: 0 20px 20px 0;
        }
        .stock-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .stock-ok  { 
            background: #e8f5e9; 
            color: #2e7d32; 
        }
        .stock-low { 
            background: #fff3e0; 
            color: #e65100; 
        }
        .stock-out { 
            background: #fce4ec; 
            color: #ad1457; 
        }
        .card-body-custom {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #6d1223;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .product-desc {
            font-size: 12px;
            color: #999;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }
        .product-price {
            font-size: 24px;
            font-weight: 900;
            color: #b02038;
            margin-bottom: 4px;
        }
        .product-stock-text {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 16px;
        }
        .product-stock-text i { 
            margin-right: 4px; 
        }
        .card-actions { 
            display: flex; 
            gap: 8px; 
        }
        .btn-view {
            flex: 1;
            background: transparent;
            border: 2px solid #6d1223;
            color: #6d1223;
            border-radius: 8px;
            padding: 9px;
            font-weight: 700;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .btn-view:hover { 
            background: #6d1223; 
            color: white; 
        }
        .btn-addcart {
            flex: 2;
            background: #6d1223;
            border: none;
            color: white;
            border-radius: 8px;
            padding: 9px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .btn-addcart:hover { 
            background: #4d0d19; 
        }
        .btn-addcart:disabled { 
            background: #ccc; 
            cursor: not-allowed; 
        }
        .results-bar {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }
        .results-bar span { 
            color: #6d1223; 
            font-weight: 700; 
        }
        .toast-custom {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #6d1223;
            color: white;
            padding: 14px 22px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            z-index: 9999;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            animation: slideIn 0.3s ease;
        }
        .toast-custom a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 700;
            white-space: nowrap;
        }
        .toast-custom a:hover { text-decoration: underline; }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(120%); opacity: 0; }
        }
        .toast-hide { animation: slideOut 0.3s ease forwards; }

        @media (max-width: 1200px) { .page-wrap { padding: 30px 20px; } }
    </style>
</head>
<body>

    <?php include('../includes/header.php'); ?>

    <div class="page-wrap">

        <!-- Title -->
        <div class="text-center mb-5">
            <h1 class="title">Product Catalog</h1>
            <hr class="w-50 mx-auto border-danger opacity-50">
            <p class="text-muted">Explore our university collection</p>
        </div>

        <!-- Search & Filter -->
        <div class="search-box">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">
                            <i class="bi bi-search me-1"></i> Search
                        </label>
                        <input type="text" class="form-control" name="search"
                               placeholder="Search products..."
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">
                            <i class="bi bi-funnel me-1"></i> Category
                        </label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $id => $name): ?>
                                <option value="<?php echo $id; ?>" <?php echo ($category == $id) ? 'selected' : ''; ?>>
                                    <?php echo $name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">
                            <i class="bi bi-sort-alpha-down me-1"></i> Sort By
                        </label>
                        <select class="form-select" name="sort">
                            <option value="az"   <?php echo ($sort=='az')   ? 'selected' : ''; ?>>Name A–Z</option>
                            <option value="za"   <?php echo ($sort=='za')   ? 'selected' : ''; ?>>Name Z–A</option>
                            <option value="low"  <?php echo ($sort=='low')  ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="high" <?php echo ($sort=='high') ? 'selected' : ''; ?>>Price: High to Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-maroon w-100 py-2">
                            <i class="bi bi-search me-1"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results count -->
        <div class="results-bar">
            <i class="bi bi-grid me-1"></i>
            Showing <span><?php echo mysqli_num_rows($result); ?></span> product(s)
            <?php if ($search): ?>
                for "<span><?php echo htmlspecialchars($search); ?></span>"
            <?php endif; ?>
        </div>

        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-4">

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)):
                    $stock = (int) $product['stock_quantity'];
                ?>
                <div class="col">
                    <div class="product-card">

                        <!-- Image -->
                        <div class="card-img-wrap">
                            <?php if ($product['image_url']): ?>
                                <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div class="no-img-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Category  -->
                            <span class="cat-ribbon">
                                <?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?>
                            </span>

                            <!-- Stock -->
                            <?php if ($stock <= 0): ?>
                                <span class="stock-badge stock-out">Out of Stock</span>
                            <?php elseif ($stock <= 5): ?>
                                <span class="stock-badge stock-low">Only <?php echo $stock; ?> left</span>
                            <?php else: ?>
                                <span class="stock-badge stock-ok">
                                    <i class="bi bi-check-circle me-1"></i><?php echo $stock; ?> in stock
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Card  -->
                        <div class="card-body-custom">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-desc">
                                <?php echo htmlspecialchars($product['description'] ?? ''); ?>
                            </div>
                            <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                            <div class="product-stock-text">
                                <?php if ($stock <= 0): ?>
                                    <i class="bi bi-x-circle text-danger"></i>
                                    <span class="text-danger fw-bold">Out of Stock</span>
                                <?php elseif ($stock <= 5): ?>
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <span class="text-warning fw-bold">Only <?php echo $stock; ?> left!</span>
                                <?php else: ?>
                                    <i class="bi bi-box-seam"></i> <?php echo $stock; ?> available
                                <?php endif; ?>
                                <?php if ($product['size']): ?>
                                    &nbsp;·&nbsp; <i class="bi bi-rulers"></i> <?php echo htmlspecialchars($product['size']); ?>
                                <?php endif; ?>
                            </div>

                            <!-- Action  -->
                            <div class="card-actions">
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn-view">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                <form method="POST" style="flex:2; display:flex;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity"   value="1">
                                    <button type="submit" name="add_to_cart" class="btn-addcart w-100"
                                        <?php echo ($stock <= 0) ? 'disabled' : ''; ?>>
                                        <i class="bi bi-cart-plus"></i>
                                        <?php echo ($stock <= 0) ? 'Out of Stock' : 'Add to Cart'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endwhile; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size:4rem; color:#ddd;"></i>
                    <p class="text-muted mt-3 fs-5">No products found.</p>
                    <a href="Shopuser.php" class="btn btn-maroon mt-2 px-4">Clear Search</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!--  Notification -->
    <?php if (isset($_SESSION['cart_toast'])): ?>
    <div class="toast-custom" id="cartToast">
        <i class="bi bi-cart-check-fill fs-5"></i>
        <div>
            <div><?php echo htmlspecialchars($_SESSION['cart_toast']); ?> added to cart!</div>
            <div style="font-size:12px; opacity:0.85; margin-top:2px;">
                <a href="cart.php"><i class="bi bi-bag me-1"></i>Go to Cart</a>
            </div>
        </div>
        <button onclick="dismissToast()" style="background:none;border:none;color:rgba(255,255,255,0.6);font-size:18px;margin-left:auto;cursor:pointer;">✕</button>
    </div>
    <script>
        // Auto dismiss after 3.5 seconds
        setTimeout(function() {
            dismissToast();
        }, 3500);

        function dismissToast() {
            const t = document.getElementById('cartToast');
            if (t) {
                t.classList.add('toast-hide');
                setTimeout(() => t.remove(), 300);
            }
        }
    </script>
    <?php unset($_SESSION['cart_toast']); ?>
    <?php endif; ?>

    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>