<?php
require __DIR__ . '/backend/product/shop-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-public">

    <nav class="navbar navbar-expand-sm customnav">
        <div class="navbar-nav">
            <div class="d-flex align-items-center">
                <img src="product-images/culogo.jpg" class="rounded-circle logo-gold-outline" alt="Logo">
                <div class="brand-text">
                    <h1>Capitol University</h1>
                    <span>Official Giftshop</span>
                </div>
            </div>
            <div class="center-links">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexshop.php" style="border-bottom: 3px solid #d4af37; padding-bottom: 2px;">Shop</a>
                </li>
            </div>
            <div class="d-flex gap-2">
                <a href="login.php" class="btn btn-login btn-sm">Login</a>
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
                    $stock  = (int) $product['stock_quantity'];
                    $imgUrl = str_replace('Product-Images', 'product-images', (string) ($product['image_url'] ?? ''));
                ?>
                <div class="col">
                    <div class="product-card">

                        <!-- Image -->
                        <div class="card-img-wrap">
                            <?php if ($imgUrl): ?>
                                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="">
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