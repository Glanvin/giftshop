<?php
session_start();

// Redirects logged-in users to their respective dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['role']=='admin'||$_SESSION['role']=='staff' ? 'Staff/dashboard.php' : 'User/Useracc.php'));
    exit;
}

include('connection.php');
// Initialize form variables and error messages
$email = $emailEr = $passwordEr = $loginError = "";
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validates the email
    if (empty($_POST['email'])) {
        $emailEr = "Email is required.";
    } else {
        $email = trim($_POST['email']);
        if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email))
            $emailEr = "Invalid email format.";
    }
    // validates the password
    if (empty($_POST['password'])) {
        $passwordEr = "Password is required.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $passwordEr = "Minimum 6 characters.";
    }
    // If no errors, attempt login
    if (empty($emailEr) && empty($passwordEr)) {
        $es = mysqli_real_escape_string($con, $email);
        $ps = mysqli_real_escape_string($con, trim($_POST['password']));
        $r  = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$es' AND password='$ps' AND status='active' LIMIT 1"));
        if ($r) {
            $_SESSION['user_id']   = $r['id'];
            $_SESSION['email']     = $r['email'];
            $_SESSION['full_name'] = $r['full_name'];
            $_SESSION['role']      = $r['role'];
            mysqli_query($con, "UPDATE users SET last_login=NOW() WHERE id=".$r['id']);
            header("Location: ".($r['role']=='admin'||$r['role']=='staff' ? 'Staff/dashboard.php' : 'User/Useracc.php'));
            exit();
        } else {
            $loginError = "Wrong email or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { 
    box-sizing: border-box; 
        }
        body { 
            background: #b02038; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', sans-serif; 
            padding: 20px; 
            margin: 0; 
        }

        .auth-card { 
            width: 980px; 
            min-height: 620px; 
            display: flex; 
            background: white; 
            border-radius: 18px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.35); 
            overflow: hidden; 
        }

        .left-panel { 
            width: 42%; 
            background: #6d1223; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            padding: 50px 35px; 
            text-align: center; 
            color: white; 
        }
        .logo-circle { 
            width: 110px; 
            height: 110px; 
            border: 4px solid #d4af37; 
            border-radius: 50%; 
            background: white; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            margin-bottom: 24px; 
        }

        .logo-circle img { 
            height: 78%; 
            width: auto; 
        }

        .left-panel h2 { 
            font-family: 'Times New Roman', serif; 
            font-size: 34px; 
            font-weight: bold; 
            margin-bottom: 10px; 
        }

        .left-panel p  { 
            font-size: 14px; 
            opacity: 0.85; 
            margin-bottom: 30px; 
        }

        .btn-outline-white { 
            background: transparent; 
            color: white; 
            border: 2px solid white; 
            padding: 10px 35px; 
            border-radius: 25px; 
            font-weight: 700; 
            font-size: 14px; 
            cursor: pointer; 
            transition: 0.3s; 
        }

        .btn-outline-white:hover { 
            background: white; 
            color: #6d1223; 
        }

        .right-panel { 
            width: 58%; 
            padding: 50px 55px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
        }

        .form-title { 
            color: #6d1223; 
            font-size: 28px; 
            font-weight: 800; 
            margin-bottom: 4px; 
        }

        .form-sub   { 
            color: #bbb; 
            font-size: 13px; 
            margin-bottom: 22px; 
        }

        .form-label { 
            font-weight: 600; 
            font-size: 13px; 
            color: #444; 
            margin-bottom: 3px; 
            display: block; 
        }

        .form-control { 
            border-radius: 8px; 
            padding: 10px 14px; 
            border: 1px solid #ddd; 
            font-size: 14px; 
            width: 100%; 
            transition: border 0.2s; 
        }

        .form-control:focus { 
            border-color: #6d1223; 
            box-shadow: none; 
            outline: none; 
        }

        .field-error { 
            color: #c0392b; 
            font-size: 11px; 
            margin-top: 2px; 
            display: block; 
        }

        .btn-main { 
            background: #6d1223; 
            color: white; 
            border: none; 
            width: 100%; 
            padding: 12px; 
            font-weight: 700; 
            border-radius: 10px; 
            font-size: 15px; 
            cursor: pointer; 
            transition: 0.3s; 
            margin-top: 6px; 
        }

        .btn-main:hover { 
            background: #4d0d19; 
        }

        .error-box { 
            background: #fde8e8; 
            color: #721c24; 
            padding: 9px 14px; 
            border-radius: 8px; 
            font-size: 13px; 
            margin-bottom: 15px; 
            border: 1px solid #f5c6cb; 
        }

        .demo-box { 
            background: #fdfaf0; 
            border-left: 4px solid #d4af37; 
            padding: 12px 15px; 
            margin-top: 20px; 
            border-radius: 6px; 
            font-size: 12px; 
        }

        .demo-box h6 { 
            color: #6d1223; 
            font-weight: bold; 
            margin-bottom: 5px; 
            font-size: 13px; 
        }

        .flip-overlay { 
            display: none; 
            position: fixed; 
            inset: 0; 
            background: rgba(0,0,0,0.5); 
            z-index: 9999; 
            align-items: center; 
            justify-content: center; 
        }

        .flip-overlay.show { 
            display: flex; 
        }

        .flip-coin { 
            width: 130px; 
            height: 130px; 
            border: 5px solid #d4af37; 
            border-radius: 50%; 
            background: white; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            animation: coinFlip 1s ease-in-out forwards; 
        }

        .flip-coin img { 
            height: 78%; 
            width: auto; 
        }

        @keyframes coinFlip {
            0%   { transform: scale(0.3) rotateY(0deg);   opacity: 0; }
            20%  { transform: scale(1)   rotateY(0deg);   opacity: 1; }
            60%  { transform: scale(1.1) rotateY(360deg); opacity: 1; }
            100% { transform: scale(0)   rotateY(360deg); opacity: 0; }
        }
    </style>
</head>
<body>

<div class="flip-overlay" id="flipOverlay">
    <div class="flip-coin">
        <img src="Product-Images/culogo.jpg" alt="CU Logo">
    </div>
</div>

<div class="auth-card">

    <div class="left-panel">
        <div class="logo-circle">
            <img src="Product-Images/culogo.jpg" alt="CU Logo">
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
            <button type="submit" class="btn-main">➜ Log In</button>
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

<script>
    function goToSignup() {
        // Show coin flip
        document.getElementById('flipOverlay').classList.add('show');
        // Redirect after animation finishes (1 second)
        setTimeout(() => {
            window.location.href = 'signup.php';
        }, 950);
    }
</script>

</body>
</html>