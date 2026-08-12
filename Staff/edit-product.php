<?php
session_start();
//checks to see whether the user has login and if not, redirected to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /giftshop/login.php');
    exit;
}

include('../connection.php');
// get product Id base from url
$id = $_GET['edit'];

// fetching product details 
$product = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM products WHERE id='$id'"));
$categories = mysqli_query($con,"SELECT * FROM categories");

// using post to see if the form has been submitted
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];
    $sku = $_POST['sku'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $status = $_POST['status'];


    $sql_image = '';
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error']==0){
        $image_name = time().'_'.$_FILES['product_image']['name'];
        $target = "../product-images/".$image_name;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target);
        $sql_image = ", image_url='product-images/".$image_name."'";
    }

    // updates the product in the database
    $sql = "UPDATE products SET
        name='$name',
        category_id='$category_id',
        price='$price',
        stock_quantity='$stock',
        sku='$sku',
        size='$size',
        color='$color',
        status='$status'
        $sql_image
        WHERE id='$id'";
    //  executing update
    mysqli_query($con,$sql);
    // success message
    $_SESSION['message'] = "Product Updated Successfully";
    // then redirects back to the inventory page
    header("Location: inventory.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .edit-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 25px;
            right: 25px;
            color: #80182a;
            font-size: 24px;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .close-btn:hover {
            transform: scale(1.1);
            color: #a01e35;
        }
        .title {
            font-family: 'Playfair Display', serif;
            color: #6d1223;
            font-size: 42px;
            margin-bottom: 5px;
        }
        .product-subtitle {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }
        .stock-indicator {
            color: #6d1223;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .form-label {
            font-weight: 600;
            color: #6d1223;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #80182a;
            box-shadow: 0 0 0 0.25rem rgba(128, 24, 42, 0.1);
        }
        .btn-update {
            background-color: #be1e3c;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        .btn-update:hover {
            background-color: #a01e35;
            color: white;
            box-shadow: 0 4px 12px rgba(190, 30, 60, 0.3);
        }
        .current-img-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #eee;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- header temporary file-->
    <?php include ('../includes/header-staff.php'); ?>

    <div class="container">
        <div class="edit-container">
            <a href="inventory.php" class="close-btn"><i class="fas fa-times"></i></a>
            
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
                                    <img src="../<?php echo $product['image_url']; ?>" class="current-img-preview">
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
                        <i class="fas fa-save"></i> Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

//alt + i ang multicursor