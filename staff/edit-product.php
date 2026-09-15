<?php
require __DIR__ . '/../backend/staff/edit-product-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-staff">
    <?php include ('../includes/header-staff.php'); ?>

    <div class="container">
        <div class="edit-container">
            <a href="inventory.php" class="close-btn"><i class="bi bi-x-lg"></i></a>

            <h1 class="title">Update Stock</h1>
            <p class="product-subtitle"><?php echo $product['name']; ?></p>

            <div class="stock-indicator">
                Current Stock: <?php echo $product['stock_quantity']; ?>
            </div>

            <!-- form for editing product -->
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php if($cat['id']==$product['category_id']) echo "selected"; ?>>
                                <?php echo $cat['name']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="<?php echo $product['sku']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Size</label>
                        <input type="text" name="size" class="form-control" value="<?php echo $product['size']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="<?php echo $product['color']; ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" <?php if($product['status']=='active') echo 'selected'; ?>>Active</option>
                            <option value="inactive" <?php if($product['status']=='inactive') echo 'selected'; ?>>Inactive</option>
                            <option value="out_of_stock" <?php if($product['status']=='out_of_stock') echo 'selected'; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Product Image</label>
                        <div class="d-flex align-items-start gap-3">
                            <?php if($product['image_url'] != ''): ?>
                                <div>
                                    <small class="text-muted d-block mb-1">Current:</small>
                                    <img src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $product['image_url'])); ?>" class="current-img-preview">
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1">Upload New:</small>
                                <input type="file" name="product_image" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="bi bi-save"></i> Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>