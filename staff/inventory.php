<?php
require __DIR__ . '/../backend/staff/inventory-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory – Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="area-staff">

    <?php include('../includes/header-staff.php'); ?>

    <div class="page-wrap">

        <!-- Header -->
        <div class="page-header">
            <div>
                <h2><i class="bi bi-box-seam me-2"></i>Inventory</h2>
                <p>Manage all products and stock levels</p>
            </div>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg"></i> Add Product
            </button>
        </div>

        <!-- Session Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show mb-4" style="border-radius:10px;" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="filter-strip">
            <a href="?filter=all"    class="filter-pill <?php echo $filter=='all'    ?'active':''; ?>">
                <i class="bi bi-grid"></i> All Products <span class="count"><?php echo $countAll; ?></span>
            </a>
            <a href="?filter=active" class="filter-pill <?php echo $filter=='active' ?'active':''; ?>">
                <i class="bi bi-check-circle"></i> Active <span class="count"><?php echo $countActive; ?></span>
            </a>
            <a href="?filter=low"    class="filter-pill <?php echo $filter=='low'    ?'active':''; ?>">
                <i class="bi bi-exclamation-triangle"></i> Low Stock <span class="count"><?php echo $countLow; ?></span>
            </a>
            <a href="?filter=out"    class="filter-pill <?php echo $filter=='out'    ?'active':''; ?>">
                <i class="bi bi-x-circle"></i> Out of Stock <span class="count"><?php echo $countOut; ?></span>
            </a>
        </div>

        <!-- Table -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:60px;"></th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($products_result) == 0): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    No products found.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($products_result)): ?>
                        <tr>
                            <!-- product image -->
                            <td>
                                <?php if ($row['image_url']): ?>
                                    <img src="../<?php echo htmlspecialchars(str_replace('Product-Images', 'product-images', $row['image_url'])); ?>" class="product-img" alt="">
                                <?php else: ?>
                                    <div class="img-placeholder"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </td>

                            <!-- product name and description -->
                            <td>
                                <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="product-desc">
                                    <?php echo strlen($row['description']) > 35 ? substr($row['description'], 0, 35) . '...' : $row['description']; ?>
                                </div>
                            </td>

                            <!-- Category -->
                            <td style="color:#777;"><?php echo htmlspecialchars($row['category_name'] ?? '—'); ?></td>

                            <!-- SKU -->
                            <td><code style="color:var(--maroon); font-size:12px;"><?php echo htmlspecialchars($row['sku']); ?></code></td>

                            <!-- Price -->
                            <td><strong style="color:var(--maroon);">₱<?php echo number_format($row['price'], 2); ?></strong></td>

                            <!-- Stock -->
                            <td>
                                <?php
                                $stock = (int) $row['stock_quantity'];
                                if ($stock <= 0) {
                                    $stockClass = 'stock-out';
                                } elseif ($stock <= 5) {
                                    $stockClass = 'stock-low';
                                } else {
                                    $stockClass = 'stock-ok';
                                }
                                ?>
                                <span class="stock-badge <?php echo $stockClass; ?>"><?php echo $stock; ?></span>
                            </td>

                            <!-- Status -->
                            <td>
                                <?php
                                $status = strtolower($row['status']);
                                $badgeClass = 'status-active';
                                if ($status == 'out_of_stock') $badgeClass = 'status-out';
                                elseif ($status == 'inactive')  $badgeClass = 'status-inactive';
                                elseif ($row['stock_quantity'] <= 5 && $status != 'out_of_stock') $badgeClass = 'status-low';
                                ?>
                                <span class="status-badge <?php echo $badgeClass; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $row['status'])); ?>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit-product.php?edit=<?php echo $row['id']; ?>" class="btn-edit-row" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../backend/insertions/delete-product.php?delete=<?php echo $row['id']; ?>"
                                       class="btn-del-row"
                                       title="Delete"
                                       onclick="return confirm('Delete this product?')">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../backend/insertions/add-product.php" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="product_image" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter product name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category *</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control" name="sku" placeholder="e.g. MERCH-HOOD-L">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price (₱)</label>
                                <input type="number" step="0.01" class="form-control" name="price" placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Initial Stock</label>
                                <input type="number" class="form-control" name="stock_quantity" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Size</label>
                                <input type="text" class="form-control" name="size" placeholder="S, M, L, XL">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="Red, Blue, Black">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Describe the product..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save" class="btn-save">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>