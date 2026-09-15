<?php
require __DIR__ . '/backend/auth/login-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-auth">

<div class="flip-overlay" id="flipOverlay">
    <div class="flip-coin">
        <img src="product-images/culogo.jpg" alt="CU Logo">
    </div>
</div>

<div class="auth-card">

    <div class="left-panel">
        <div class="logo-circle">
            <img src="product-images/culogo.jpg" alt="CU Logo">
        </div>
        <h2>New Here?</h2>
        <p>Create an account to reserve your university essentials.</p>
        <button class="btn-outline-white" onclick="goToSignup()">CREATE ACCOUNT</button>
    </div>

    <div class="right-panel">
        <div class="form-title">Log In</div>
        <div class="form-sub">Enter your credentials to continue</div>

        <?php if ($loginError): ?>
            <div class="error-box"><?php echo $loginError; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="text" name="email" class="form-control"
                       placeholder="your.email@cu.edu.ph"
                       value="<?php echo htmlspecialchars($email); ?>">
                <span class="field-error"><?php echo $emailEr; ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
                <span class="field-error"><?php echo $passwordEr; ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rem">
                    <label class="form-check-label small" for="rem">Remember me</label>
                </div>
                <a href="forgot.php" class="text-danger small text-decoration-none">Forgot Password?</a>
            </div>
            <button type="submit" class="btn-main">Log In</button>
        </form>

        <div class="demo-box">
            <h6>Demo Credentials</h6>
            <b>Student:</b> botsai@g.cu.edu.ph / botsai123<br>
            <b>Admin:</b> admin@g.cu.edu.ph / admin123
        </div>

        <div class="text-center mt-3">
            <a href="index.php" class="text-muted text-decoration-none small">← Back to Homepage</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/login.js"></script>
</body>
</html>