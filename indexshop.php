<?php
include('connection.php');
// Get search, category, and sort from URL
$search   = $_GET['search']   ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort']     ?? 'az';

// basing from query and selects all active products and their category
$query = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
// search conditions
if ($search) {
    $s = mysqli_real_escape_string($con, $search);
    $query .= " AND (p.name LIKE '%$s%' OR p.description LIKE '%$s%')";
}
// can filter by category
if ($category && $category != "All Categories") {
    $cat = mysqli_real_escape_string($con, $category);
    $query .= " AND p.category_id = '$cat'";
}
// Apply sorting based on user selection
switch ($sort) {
    case "za":   $query .= " ORDER BY p.name DESC"; break;
    case "low":  $query .= " ORDER BY p.price ASC"; break;
    case "high": $query .= " ORDER BY p.price DESC"; break;
    default:     $query .= " ORDER BY p.name ASC";
}
// execute query
$result     = mysqli_query($con, $query);
// Define category names
$categories = [1 => "Textbook", 2 => "Uniform", 3 => "PE Uniform", 4 => "Merchandise"];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 <style>
    body { 
        margin: 0; 
        font-family: 'Poppins', sans-serif; 
        background: #faf9f6; 
    }

    .custombg, .navbar, .shopcateg, .featuredproducts {
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
        margin: 5px 0; 
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
        padding-right: 190px; 
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
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 11px; 
        display: block; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        margin-top: 2px; 
    }

    .btn-login { 
        color: white; 
        border: 1px solid white; 
        padding: 10px 30px; 
        font-size: 12px; 
        font-weight: bold; 
        border-radius: 5px; 
        background: transparent; 
        text-decoration: none; 
    }

    .btn-login:hover { 
        background: rgba(255,255,255,0.15); 
        color: white; 
    }

    .btn-logins { 
        background-color: #d4af37; 
        color: #333; 
        border: none; 
        padding: 10px 30px; 
        font-size: 12px; 
        font-weight: bold; 
        border-radius: 5px; 
        text-decoration: none; 
    }

    .btn-logins:hover { 
        background: #c9a030; 
        color: #333; 
    }

    .page-wrap { 
        padding: 50px 180px; 
    }

    .page-title { 
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
        margin-bottom: 35px; 
    }

    .form-control, .form-select { 
        border-radius: 8px; 
        padding: 10px 14px; 
        border: 1px solid #ddd; 
        font-size: 14px; 
    }

    .form-control:focus, .form-select:focus { 
        border-color: #6d1223; 
        box-shadow: none; 
    }

    .btn-maroon { 
        background: #6d1223 !important; 
        color: white !important; 
        border: none; 
        border-radius: 8px; 
    }

    .btn-maroon:hover { 
        background: #4d0d19 !important; 
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

    .no-img { 
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

    .stock-ok { 
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
        padding: 18px 20px; 
        flex-grow: 1; 
        display: flex; 
        flex-direction: column; 
    }

    .product-name { 
        font-family: 'Playfair Display', serif; 
        font-size: 17px; 
        color: #6d1223; 
        font-weight: 700; 
        margin-bottom: 5px; 
        line-height: 1.3; 
    }

    .product-desc { 
        font-size: 12px; 
        color: #999; 
        margin-bottom: 10px; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden; 
        flex-grow: 1; 
    }

    .product-price { 
        font-size: 22px; 
        font-weight: 900; 
        color: #b02038; 
        margin-bottom: 4px; 
    }

    .product-stock-text { 
        font-size: 12px; 
        color: #aaa; 
        margin-bottom: 14px; 
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

    .btn-reserve { 
        flex: 2; 
        background: #6d1223; 
        border: none; 
        color: white; 
        border-radius: 8px; 
        padding: 9px; 
        font-weight: 700; 
        font-size: 13px; 
        text-decoration: none; 
        transition: 0.2s; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 5px; 
    }

    .btn-reserve:hover { 
        background: #4d0d19; 
        color: white; 
    }

    .btn-disabled { 
        flex: 2; 
        background: #eee; 
        border: none; 
        color: #aaa; 
        border-radius: 8px; 
        padding: 9px; 
        font-size: 13px; 
        cursor: not-allowed; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 5px; 
    }

    .reserve-banner { 
        background: #6d1223; 
        color: white; 
        padding: 16px 24px; 
        border-radius: 12px; 
        margin-bottom: 30px; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        gap: 20px; 
    }

    .reserve-banner p { 
        margin: 0; 
        font-size: 14px; 
    }

    .reserve-banner a { 
        background: #d4af37; 
        color: #333; 
        font-weight: 700; 
        padding: 9px 25px; 
        border-radius: 20px; 
        text-decoration: none; 
        font-size: 13px; 
        white-space: nowrap; 
    }

    .reserve-banner a:hover { 
        background: #c9a030; 
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

    @media (max-width: 1200px) { 
        .custombg, .navbar, .page-wrap { 
            padding-left: 20px; 
            padding-right: 20px; 
        } 
        .center-links { 
            padding-right: 0; 
        } 
    }
</style>
</head>
<body>

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
        <div class="navbar-nav">
            <div class="d-flex align-items-center">
                <img src="Product-Images/culogo.jpg" class="rounded-circle logo-gold-outline" alt="Logo">
                <div class="brand-text">
                    <h1>Capitol University</h1>
                    <span>Official Giftshop</span>
                </div>
            </div>
            <div class="center-links">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">🏠︎ Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexshop.php" style="border-bottom: 3px solid #d4af37; padding-bottom: 2px;">𖠩 Shop</a>
                </li>
            </div>
            <div class="d-flex gap-2">
                <a href="login.php"  class="btn btn-login  btn-sm">Login</a>
                <a href="signup.php" class="btn btn-logins btn-sm">Sign Up</a>
            </div>
        </div>
    </nav>

    <div class="page-wrap">

        <div class="text-center mb-5">
            <h1 class="page-title">Product Catalog</h1>
            <hr class="w-50 mx-auto border-danger opacity-50">
            <p class="text-muted">Explore our university collection</p>
        </div>

        <!-- reservation banner -->
        <div class="reserve-banner">
            <p>
                <i class="bi bi-lock me-2"></i>
                You can <strong>browse and view</strong> all products freely.
                To <strong>reserve items</strong>, please log in to your account.
            </p>
            <a href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Log In to Reserve</a>
        </div>

        <!-- Search and Filter -->
        <div class="search-box">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted"><i class="bi bi-search me-1"></i> Search</label>
                        <input type="text" class="form-control" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted"><i class="bi bi-funnel me-1"></i> Category</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $id => $name): ?>
                                <option value="<?php echo $id; ?>" <?php echo ($category==$id)?'selected':''; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted"><i class="bi bi-sort-alpha-down me-1"></i> Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="az"   <?php echo ($sort=='az')  ?'selected':''; ?>>Name A–Z</option>
                            <option value="za"   <?php echo ($sort=='za')  ?'selected':''; ?>>Name Z–A</option>
                            <option value="low"  <?php echo ($sort=='low') ?'selected':''; ?>>Price: Low to High</option>
                            <option value="high" <?php echo ($sort=='high')?'selected':''; ?>>Price: High to Low</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-maroon w-100 py-2"><i class="bi bi-search me-1"></i> Apply</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results count -->
        <div class="results-bar">
            <i class="bi bi-grid me-1"></i>
            Showing <span><?php echo mysqli_num_rows($result); ?></span> product(s)
            <?php if ($search): ?> for "<span><?php echo htmlspecialchars($search); ?></span>"<?php endif; ?>
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
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="">
                            <?php else: ?>
                                <div class="no-img"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                            <span class="cat-ribbon"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></span>
                            <?php if ($stock <= 0): ?>
                                <span class="stock-badge stock-out">Out of Stock</span>
                            <?php elseif ($stock <= 5): ?>
                                <span class="stock-badge stock-low">Only <?php echo $stock; ?> left</span>
                            <?php else: ?>
                                <span class="stock-badge stock-ok"><i class="bi bi-check-circle me-1"></i><?php echo $stock; ?> in stock</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body-custom">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-desc"><?php echo htmlspecialchars($product['description'] ?? ''); ?></div>
                            <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                            <div class="product-stock-text">
                                <?php if ($stock <= 0): ?>
                                    <i class="bi bi-x-circle text-danger"></i> <span class="text-danger fw-bold">Out of Stock</span>
                                <?php elseif ($stock <= 5): ?>
                                    <i class="bi bi-exclamation-triangle text-warning"></i> <span class="text-warning fw-bold">Only <?php echo $stock; ?> left!</span>
                                <?php else: ?>
                                    <i class="bi bi-box-seam"></i> <?php echo $stock; ?> available
                                <?php endif; ?>
                            </div>

                          
                            <div class="card-actions">
                                <a href="indexproduct.php?id=<?php echo $product['id']; ?>" class="btn-view">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <?php if ($stock <= 0): ?>
                                    <span class="btn-disabled">
                                        <i class="bi bi-cart-x"></i> Out of Stock
                                    </span>
                                <?php else: ?>
                                    <a href="login.php" class="btn-reserve">
                                        <i class="bi bi-lock"></i> Login to Reserve
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endwhile; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size:4rem; color:#ddd;"></i>
                    <p class="text-muted mt-3 fs-5">No products found.</p>
                    <a href="indexshop.php" class="btn btn-maroon mt-2 px-4">Clear Search</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <!-- footer -->
    <?php include('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>