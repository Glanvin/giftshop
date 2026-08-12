<?php
session_start();
//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}
include('../connection.php');

// Fetch categories for the dropdown
$categories_query = mysqli_query($con, "SELECT * FROM categories");
$categories = [];
while ($cat = mysqli_fetch_assoc($categories_query)) {
    $categories[] = $cat;
}

// Get filter from URL
$filter = $_GET['filter'] ?? 'all';

// Apply filter to product query
if ($filter == 'active') 
    { // Active products with stock > 5
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'active' AND p.stock_quantity > 5";
} elseif ($filter == 'low') 
    {  // Low stock (1–5)
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock_quantity > 0 AND p.stock_quantity <= 5";
} elseif ($filter == 'out') 
    { // Out of stock
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.stock_quantity <= 0 OR p.status = 'out_of_stock'";
} else 
    {  // All products
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
}
// executing the query
$products_result = mysqli_query($con, $sql);

// Get counts for each filter (all, active, low, out)
$countAll    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products"))['c'];
$countActive = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE status = 'active' AND stock_quantity > 5"))['c'];
$countLow    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5"))['c'];
$countOut    = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products WHERE stock_quantity <= 0 OR status = 'out_of_stock'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory – Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { 
            --maroon: #6d1223; 
            --maroon-dark: #4d0d19; 
            --gold: #d4af37; 
            --bg: #f7f3ef; 
        }

        body { 
            font-family: 'DM Sans', sans-serif; 
            background: var(--bg); 
        }
        .page-wrap { 
            padding: 40px 60px; 
        }
        .page-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 28px; 
        }
        .page-header h2 { 
            font-family: 'Playfair Display', serif; 
            color: var(--maroon); 
            font-size: 34px; 
            margin: 0; 
        }
        .page-header p  { 
            color: #aaa; 
            font-size: 13px; 
            margin: 4px 0 0; 
        }
        .btn-add {
            background: var(--maroon);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-add:hover { 
            background: var(--maroon-dark); 
            color: white; 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(109,18,35,0.3); 
        }
        .filter-strip { 
            display: flex; 
            gap: 10px; 
            margin-bottom: 24px; 
            flex-wrap: wrap; 
        }
        .filter-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            border: 2px solid transparent;
            background: white;
            color: #666;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.2s;
        }
        .filter-pill:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 16px rgba(0,0,0,0.1); 
            color: var(--maroon); 
            border-color: var(--maroon); 
        }
        .filter-pill.active { 
            background: var(--maroon); 
            color: white; 
            border-color: var(--maroon); 
        }
        .filter-pill .count { 
            background: rgba(0,0,0,0.1); 
            border-radius: 20px; 
            padding: 1px 8px; 
            font-size: 11px; 
        }
        .filter-pill.active .count { 
            background: rgba(255,255,255,0.25); 
        }
        .table-card { 
            background: white; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.07); 
        }
        .table-card table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .table-card thead { 
            background: var(--maroon); 
        }
        .table-card thead th { 
            color: white; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            padding: 16px 18px; 
            font-weight: 600; 
            border: none; 
        }
        .table-card tbody td { 
            padding: 16px 18px; 
            font-size: 13px; 
            border-bottom: 1px solid #f5f0eb; 
            vertical-align: middle; 
        }
        .table-card tbody tr:last-child td { 
            border-bottom: none; 
        }
        .table-card tbody tr:hover { 
            background: #fdf8f5; 
        }
        .table-card tbody tr:nth-child(even) { 
            background: #fdfaf7; 
        }
        .table-card tbody tr:nth-child(even):hover { 
            background: #fdf0ee; 
        }
        .product-img { 
            width: 50px; 
            height: 50px; 
            object-fit: cover; 
            border-radius: 10px; 
            border: 1px solid #eee; 
        }
        .img-placeholder { 
            width: 50px; 
            height: 50px; 
            border-radius: 10px; 
            background: #f0ebe6; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: #ccc; 
            font-size: 20px; 
        }
        .product-name { 
            font-weight: 700; 
            color: var(--maroon); 
            font-size: 14px; 
        }
        .product-desc { 
            font-size: 12px; 
            color: #aaa; 
            margin-top: 2px; 
        }
        .stock-badge { 
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 700; 
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
            color: #c62828; 
        }
        .status-badge { 
            padding: 5px 14px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: 700; 
        }
        .status-active   { 
            background: #e8f5e9; 
            color: #2e7d32; 
        }
        .status-low      { 
            background: #fff3e0; 
            color: #ef6c00; }
        .status-out      { 
            background: #ffebee; 
            color: #c62828; }
        .status-inactive { 
            background: #f5f5f5; 
            color: #888; 
        }
        .btn-edit-row { 
            background: #fdf6e3; 
            color: #8a6500; 
            border: none; 
            border-radius: 8px; 
            width: 34px; 
            height: 34px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            transition: 0.2s; 
            text-decoration: none; 
        }
        .btn-edit-row:hover { 
            background: var(--gold); 
            color: white; 
        }
        .btn-del-row  { 
            background: #fde8e8; 
            color: #c62828; 
            border: none; 
            border-radius: 8px; 
            width: 34px; 
            height: 34px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            transition: 0.2s; 
            text-decoration: none; 
        }
        .btn-del-row:hover { 
            background: #c62828; 
            color: white; 
        }
        .empty-state { 
            text-align: center; 
            padding: 60px; 
            color: #ccc; 
        }
        .empty-state i { 
            font-size: 3rem; 
            display: block; 
            margin-bottom: 12px; 
        }
        .modal-title { 
            color: var(--maroon); 
            font-weight: 700; 
            font-size: 1.4rem; 
        }
        .form-label { 
            font-weight: 600; 
            color: #555; 
            font-size: 13px; 
            margin-bottom: 4px; 
        }
        .form-control, .form-select { 
            border-radius: 8px; 
            padding: 10px; 
            border: 1px solid #ddd; 
            font-size: 13px; 
        }
        .form-control:focus, .form-select:focus { 
            border-color: var(--maroon); 
            box-shadow: none; 
        }
        .btn-save { 
            background: var(--maroon); 
            color: white; 
            border: none; 
            padding: 10px 28px;
            border-radius: 8px; 
            font-weight: 700; 
        }
        .btn-save:hover { 
            background: var(--maroon-dark); 
            color: white; 
        }

        @media (max-width: 1200px) { .page-wrap { padding: 24px 16px; } }
    </style>
</head>
<body>

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
                    <!-- condition if there are no products in the database, this will show empty message -->
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
                        <!-- this Loop goes through each product from database -->
                        <?php while ($row = mysqli_fetch_assoc($products_result)): ?>
                        <tr>
                            <!-- product image -->
                            <td>
                                <?php if ($row['image_url']): ?>
                                    <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" class="product-img" alt="">
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
                                    <a href="../Insertion/delete-product.php?delete=<?php echo $row['id']; ?>"
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
                    <form method="POST" action="../Insertion/add-product.php" enctype="multipart/form-data">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('../includes/footer-staff.php'); ?>
</html>