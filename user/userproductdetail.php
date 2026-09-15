<?php
session_start();
// gets user id and ensures that the user must login first
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
                    font-family: Arial, sans-serif;
                    background-color: #fff;
                }
                .product-img-box {
                    background-color: #fdfaf5;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 50px;
                    text-align: center;
                }
                
                .cat-tag {
                    background-color: #7f1429;
                    color: white;
                    padding: 2px 8px;
                    border-radius: 3px;
                    font-size: 12px;
                }
                .price-text {
                    font-size: 32px;
                    font-weight: bold;
                    color: #6d1223;
                }
                .btn-cu {
                    background-color: #7f1429;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                }
                .btn-cu:hover {
                    background-color: #6d1223;
                    color: white;
                }
                .qty-input {
                    width: 50px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                .detail-section {
                    margin-top: 30px;
                    border-top: 1px solid #eee;
                    padding-top: 15px;
                }
    </style>
</head>
<body>  
    <?php include 'header.php'?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="product-img-box h-50">
                </div>

                <div class="detail-section">
                    <h5><i class="bi bi-info-circle"></i> Product Details</h5>
                    <p class="text-muted">First year calculus with exercises.</p>
                </div>
            </div>

            <div class="col-md-6">
                <span class="cat-tag">Textbooks</span>
                <h2 class="mt-2">Calculus I Textbook</h2>

                <div class="my-4 p-3 bg-light border-start border-4 border-danger">
                    <div class="price-text">₱95.00</div>
                    <div class="text-muted small"><i class="bi bi-box"></i> 45 items available</div>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-rulers"></i> Select Size</h6>
                    <button class="btn btn-cu btn-sm">Standard</button>
                </div>

                <div class="mb-4">
                    <h6><i class="bi bi-cart"></i> Quantity</h6>
                    <div class="d-flex">
                        <button class="btn btn-outline-secondary btn-sm">-</button>
                        <input type="text" class="qty-input mx-1" value="1">
                        <button class="btn btn-outline-secondary btn-sm">+</button>
                    </div>
                </div>

                <button class="btn btn-cu w-100 py-3 mt-2">
                    <i class="bi bi-cart-fill"></i> Add to Cart
                </button>
            </div>
        </div>
    </div><br><br><br>
  
            <?php include 'footer.php'?>
        </body>
        </html>