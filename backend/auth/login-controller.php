<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff' ? 'staff/dashboard.php' : 'user/Useracc.php'));
    exit;
}

require __DIR__ . '/../connection.php';

$email = $emailEr = $passwordEr = $loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email'])) {
        $emailEr = "Email is required.";
    } else {
        $email = trim($_POST['email']);
        if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email))
            $emailEr = "Invalid email format.";
    }

    if (empty($_POST['password'])) {
        $passwordEr = "Password is required.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $passwordEr = "Minimum 6 characters.";
    }

    if (empty($emailEr) && empty($passwordEr)) {
        $es = mysqli_real_escape_string($con, $email);
        $ps = mysqli_real_escape_string($con, trim($_POST['password']));
        $r  = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$es' AND password='$ps' AND status='active' LIMIT 1"));
        if ($r) {
            $_SESSION['user_id']   = $r['id'];
            $_SESSION['email']     = $r['email'];
            $_SESSION['full_name'] = $r['full_name'];
            $_SESSION['role']      = $r['role'];
            mysqli_query($con, "UPDATE users SET last_login=NOW() WHERE id=" . $r['id']);
            header("Location: " . ($r['role'] == 'admin' || $r['role'] == 'staff' ? 'staff/dashboard.php' : 'user/Useracc.php'));
            exit();
        } else {
            $loginError = "Wrong email or password. Please try again.";
        }
    }
}