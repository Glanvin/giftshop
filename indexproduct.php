<?php
include('connection.php');

$id      = (int) ($_GET['id'] ?? 0);
$product = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT p.*, c.name AS category_name
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.id = $id AND p.status = 'active'"
));

if (!$product) {
    echo "Product not found.";
    exit;
}

$stock = (int) $product['stock_quantity'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($product['name']); ?> – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
   <style>
        body { 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
            background: #faf9f6; 
        }

        .custombg, .navbar {
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

        .btn-back { 
            border: 2px solid #6d1223; 
            color: #6d1223; 
            background: white; 
            border-radius: 8px; 
            padding: 8px 20px; 
            font-weight: 600; 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            font-size: 14px; 
            margin-bottom: 30px; 
        }

        .btn-back:hover { 
            background: #6d1223; 
            color: white; 
        }

        .img-box { 
            background: white; 
            border-radius: 16px; 
            border: 1px solid #eee; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.07); 
            padding: 40px; 
            text-align: center; 
            min-height: 380px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }

        .img-box img { 
            max-height: 300px; 
            max-width: 100%; 
            object-fit: contain; 
        }

        .no-image { 
            color: #ccc; 
            font-size: 80px; 
        }

        .info-card { 
            background: white; 
            border-radius: 16px; 
            padding: 35px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.07); 
        }

        .cat-badge { 
            background: #7f1429; 
            color: white; 
            padding: 4px 14px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }

        .product-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 32px; 
            color: #6d1223; 
            margin: 10px 0; 
            line-height: 1.2; 
        }

        .price-box { 
            background: #fdf8f8; 
            border-left: 5px solid #6d1223; 
            border-radius: 8px; 
            padding: 15px 20px; 
            margin: 20px 0; 
        }

        .price-text { 
            font-size: 36px; 
            font-weight: 900; 
            color: #6d1223; 
        }

        .stock-ok { 
            background: #e8f5e9; 
            color: #2e7d32; 
            border-radius: 20px; 
            padding: 4px 14px; 
            font-size: 13px; 
            font-weight: 600; 
        }

        .stock-low { 
            background: #fff3e0; 
            color: #e65100; 
            border-radius: 20px; 
            padding: 4px 14px; 
            font-size: 13px; 
            font-weight: 600; 
        }

        .stock-out { 
            background: #fce4ec; 
            color: #ad1457; 
            border-radius: 20px; 
            padding: 4px 14px; 
            font-size: 13px; 
            font-weight: 600; 
        }

        .detail-item { 
            font-size: 14px; 
            color: #555; 
            padding: 8px 0; 
            border-bottom: 1px solid #f5f5f5; 
        }

        .detail-item:last-child { 
            border-bottom: none; 
        }

        .detail-label { 
            color: #999; 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            display: block; 
            margin-bottom: 2px; 
        }

        .cta-box { 
            background: #6d1223; 
            color: white; 
            border-radius: 12px; 
            padding: 20px 24px; 
            margin-top: 24px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: 16px; 
        }

        .cta-box p { 
            margin: 0; 
            font-size: 14px; 
            opacity: 0.9; 
        }

        .cta-box strong { 
            display: block; 
            font-size: 16px; 
            opacity: 1; 
            margin-bottom: 3px; 
        }

        .btn-cta { 
            background: #d4af37; 
            color: #333; 
            font-weight: 700; 
            padding: 11px 28px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-size: 14px; 
            white-space: nowrap; 
            transition: 0.2s; 
        }

        .btn-cta:hover { 
            background: #c9a030; 
            color: #333; 
        }

        .desc-card { 
            background: white; 
            border-radius: 16px; 
            padding: 30px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.07); 
            margin-top: 30px; 
        }

        .desc-title { 
            font-family: 'Playfair Display', serif; 
            color: #6d1223; 
            font-size: 22px; 
            margin-bottom: 15px; 
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
                    <a class="nav-link" href="indexshop.php">𖠩 Shop</a>
                </li>
            </div>
            <div class="d-flex gap-2">
                <a href="login.php"  class="btn btn-login  btn-sm">Login</a>
                <a href="signup.php" class="btn btn-logins btn-sm">Sign Up</a>
            </div>
        </div>
    </nav>

   
    <div class="page-wrap">

        <a href="indexshop.php" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Shop
        </a>

        <div class="row g-4">

            <!-- Image -->
            <div class="col-md-5">
                <div class="img-box">
                    <?php if ($product['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                            <p class="text-muted fs-6 mt-3">No image available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Information -->
            <div class="col-md-7">
                <div class="info-card">

                    <!-- Category and Stock -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="cat-badge">
                            <i class="bi bi-tag me-1"></i>
                            <?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?>
                        </span>
                        <?php if ($stock <= 0): ?>
                            <span class="stock-out"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                        <?php elseif ($stock <= 5): ?>
                            <span class="stock-low"><i class="bi bi-exclamation-triangle me-1"></i>Only <?php echo $stock; ?> left!</span>
                        <?php else: ?>
                            <span class="stock-ok"><i class="bi bi-check-circle me-1"></i><?php echo $stock; ?> Available</span>
                        <?php endif; ?>
                    </div>

                    <!-- Name -->
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <!-- Price -->
                    <div class="price-box">
                        <div class="price-text">₱<?php echo number_format($product['price'], 2); ?></div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            Payment at the CU Cashier upon pickup
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="mb-3">
                        <?php if ($product['size']): ?>
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-rulers me-1"></i> Size</span>
                            <?php echo htmlspecialchars($product['size']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['color']): ?>
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-palette me-1"></i> Color</span>
                            <?php echo htmlspecialchars($product['color']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['sku']): ?>
                        <div class="detail-item">
                            <span class="detail-label"><i class="bi bi-upc me-1"></i> SKU</span>
                            <code><?php echo htmlspecialchars($product['sku']); ?></code>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Login to Reserve  -->
                    <?php if ($stock > 0): ?>
                    <div class="cta-box">
                        <div>
                            <strong><i class="bi bi-lock me-2"></i>Want to reserve this item?</strong>
                            <p>Log in to your account to add this to your cart and place a reservation.</p>
                        </div>
                        <a href="login.php" class="btn-cta">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Log In to Reserve
                        </a>
                    </div>
                    <?php else: ?>
                    <div style="background:#fce4ec; border-radius:10px; padding:16px 20px; margin-top:16px; color:#ad1457; font-weight:600;">
                        <i class="bi bi-x-circle me-2"></i> This item is currently out of stock.
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- Description -->
        <?php if ($product['description']): ?>
        <div class="desc-card">
            <h3 class="desc-title"><i class="bi bi-card-text me-2"></i>Product Description</h3>
            <p class="text-muted" style="font-size:15px; line-height:1.8;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>
        </div>
        <?php endif; ?>

    </div>
    <!-- footer -->
    <?php include('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>