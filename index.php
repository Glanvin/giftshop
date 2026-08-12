<?php
include('connection.php');
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
            margin: 0;
        }
        .custombg, .navbar , .shopcateg, .featuredproducts {
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
            margin: 5px 0px 5px 0px;
        }
        img {
            height: 60px;
            width: 70px;
        }
        .logo-gold-outline {
            height: 60px;
            width: 60px;
            object-fit: cover;
            border: 3px solid #d4af37; 
            box-shadow: 0 0 10px
        }
        .customnav {
            background-color: #7f1429;
        }
        .nav-link {
            color: white;
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
        .btn-login, .btn-logins{
            padding: 10px 30px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn-login {
            color: white;
            border: 1px solid white;
            margin-right: 0px;
            background: transparent;
        }
        .btn-logins {
            background-color: #d4af37;
            color: #333;
            border: none;
        }
        .bgcustom{
            background-color: #b02038;
        }
        .size {
            min-height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center;     
        } 
        .welcome {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 70px;
            color: white;
            margin-top: -50px; 
            letter-spacing: -2px;
        }

        .welcome-top {
            font-family: Arial, sans-serif;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 4px;
            display: block;
            margin-bottom: -50px; 
        }
        .sizelogin{
            font-size: 20px;
        }
        .btn-lg {
            padding: 15px 45px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 8px;
        }

        .sizelogin {
            padding: 15px 60px;
        }
        .display , .shopcateg{
            text-decoration: none;
            color: #6d1223;
        }

        .display h2 {
            margin-bottom: 5px;
        }

        .display:hover, . {
            color: #7f1429;
        }
        .centercateg{
            text-align: center;
            line-height: 40px;
        }

        .centercateg {
            text-align: center;
            margin-bottom: 40px;
        }
        .cardfeature {
        background-color: white;
        min-height: 600px;
        border-radius: 10px;
        overflow: hidden;
        transition: 0.3s;
        text-align: left !important;
        }
        .cardfeature:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
        .placeholder-img {
        width: 100%;
        height: 250px;
        background-color: #f7f7e7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-weight: bold;
         }
        .detailcolor {
            background-color: #6d1223; 
            color: white;           
            border: none;
            transition: 0.3s;
            font-weight: bold;
        }

        .detailcolor:hover {
            background-color: #d4af37; 
            color: #333;     
        }
        .btn-cu-outline {
            color: #6d1223;
            border: 2px solid #6d1223;
            background-color: transparent;
        }

        .btn-cu-outline:hover {
            background-color: #6d1223;
            color: white;
        }
        .carousel-item img {
        width: 100%;
        height: 500px;        
        object-fit: cover;   
        }
        .carousel-indicators [data-bs-target] {
            width: 12px;
            height: 12px;
            border-radius: 50%;     
            background-color: white;
            border: none;           
            opacity: 0.5;
        }

        .carousel-indicators .active {
            opacity: 1;
            background-color: #d4af37; 
        }

    </style>
</head>
<body>
    <div class="custombg">
        <div class="toptext">
            <div class="left-info">
                <p> ✆ Support: 000-000-00000</p>
                <p>🖂 support@cu.edu.ph</p>
            </div>
            <div class="schedule">
                <p>⏱︎ Mon-Fri: 8:00 AM - 5:00 PM</p>
            </div>
        </div>
    </div>

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
                    <a class="nav-link" href="Index.php">🏠︎ Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="indexshop.php">𖠩 Shop</a>
                </li>
            </div>

            <div class="d-flex">
                <a href="Login.php" class="btn btn-login btn-sm">Login</a>
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
             <a href="Login.php" class="btn btn-logins btn-lg px-5 sizelogin">Log In</a>
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
                    <img src="Product-Images/carousel1.jpg" alt="Slide 1" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="Product-Images/carousel2.jpg" alt="Slide 2" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="Product-Images/carousel3.jpg" alt="Slide 3" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="Product-Images/carousel4.jpg" alt="Slide 4" class="d-block w-100">
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
    <?php include ('includes/footer.php'); ?>
</body>
</html>