<?php
require __DIR__ . '/../backend/user/product-detail-controller.php';
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-user">
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
                        <!-- ../  goes up from User/ to giftshop/ where product-images/ lives -->
                        <img src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $product['image_url'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
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
                            <div class="qty-wrap" data-max="<?php echo $maxStock; ?>">
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
    <?php include('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>