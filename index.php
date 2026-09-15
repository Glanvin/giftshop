<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Capitol University Official Giftshop</title>
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
                    <a class="nav-link" href="indexshop.php">Shop</a>
                </li>
            </div>

            <div class="d-flex">
                <a href="login.php" class="btn btn-login btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid bgcustom text-white text-center size p-5">
        <h1 class="welcome">
            <span class="welcome-top">Welcome to</span><br>
            Capitol University <br>Official Giftshop
        </h1>
        <p class="fs-4 mt-3"> Your one-stop destination for textbooks, uniforms, and official university merchandise </p>
        <div class="d-flex gap-4 mt-5 justify-content-center">
            <a href="login.php" class="btn btn-danger btn-lg px-5">Shop now</a>
            <a href="login.php" class="btn btn-logins btn-lg px-5 sizelogin">Log In</a>
        </div>
    </div>

    <div class="shopcateg" style="padding-top: 60px; padding-bottom: 60px;">
        <div class="text-center mb-5">
            <h1 class="welcome category-title">Total Person Development</h1>
            <hr class="w-50 mx-auto border-danger opacity-50">
            <p class="fs-5">Browse our extensive collection of university essentials</p>
        </div>

        <!-- Carousel -->
        <div id="demo" class="carousel slide" data-bs-ride="carousel">

            <!-- Indicators/dots -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="3"></button>
            </div>

            <!-- The slideshow/carousel -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="product-images/carousel1.jpg" alt="Slide 1" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="product-images/carousel2.jpg" alt="Slide 2" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="product-images/carousel3.jpg" alt="Slide 3" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="product-images/carousel4.jpg" alt="Slide 4" class="d-block w-100">
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>