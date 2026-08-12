<?php
session_start();
include('../connection.php');
//checks to see whether the user has login and if not, redirected to login pagej
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
// get id and make sure that its an integer
$id      = (int) $_GET['id'];
// Fetch product information along with its category name
$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT p.*, c.name AS category_name
                                                   FROM products p
                                                   LEFT JOIN categories c ON p.category_id = c.id
                                                   WHERE p.id = $id"));
// If product not found, show message and stop
if (!$product) {
    echo "Product not found.";
    exit;
}
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
            font-family: 'Poppins', sans-serif;
            background: #faf9f6;
            margin: 0;
        }
        .page-wrap {
            padding: 50px 180px;
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
        .qty-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 2px solid #6d1223;
            background: white;
            color: #6d1223;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .qty-btn:hover {
            background: #6d1223;
            color: white;
        }

        .qty-num {
            font-size: 18px;
            font-weight: 700;
            min-width: 36px;
            text-align: center;
            color: #333;
        }
        .btn-cart {
            background: #6d1223;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-cart:hover {
            background: #4d0d19;
            color: white;
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
        }

        .btn-back:hover {
            background: #6d1223;
            color: white;
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
            .page-wrap {
                padding: 30px 20px;
            }
        }
</style>
</head>
<body>
    <!-- header -->
    <?php include('../includes/header.php'); ?>

    <div class="page-wrap">

        <a href="Shopuser.php" class="btn-back mb-4 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Back to Shop
        </a>

        <div class="row g-4">

            <!-- product image -->
            <div class="col-md-5">
                <div class="img-box">
                    <?php if ($product['image_url']): ?>
                        <!-- ../  goes up from User/ to giftshop/ where Product-Images/ lives -->
                        <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                            <p class="text-muted fs-6 mt-3">No image available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- product info -->
            <div class="col-md-7">
                <div class="info-card">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="cat-badge">
                            <i class="bi bi-tag me-1"></i>
                            <?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?>
                        </span>
                        <?php
                        $stock = (int) $product['stock_quantity'];
                        if ($stock <= 0):
                        ?>
                            <span class="stock-out"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                        <?php elseif ($stock <= 10): ?>
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

                    <!-- Product Details -->
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

                    <!-- Add to Cart Form -->
                    <?php if ($stock > 0): ?>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity"   id="qty-value" value="1">

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">
                                <i class="bi bi-hash me-1"></i> Quantity
                            </label>
                            <div class="qty-wrap">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                <span class="qty-num" id="qty-display">1</span>
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                                <span class="text-muted small">/ <?php echo $stock; ?> available</span>
                            </div>
                        </div>

                        <button type="submit" name="add_to_cart" class="btn-cart">
                            <i class="bi bi-cart-plus me-2"></i> Add to Cart
                        </button>
                    </form>
                    <?php else: ?>
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-x-circle me-2"></i> This item is currently out of stock.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <!-- Description  -->
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
    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const maxStock = <?php echo $stock; ?>;

        function changeQty(change) {
            const display = document.getElementById('qty-display');
            const input   = document.getElementById('qty-value');
            let current   = parseInt(display.textContent);
            current += change;
            if (current < 1) current = 1;
            if (current > maxStock) current = maxStock;
            display.textContent = current;
            input.value         = current;
        }
    </script>
</body>
</html>