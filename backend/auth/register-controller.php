<?php
session_start();
require __DIR__ . '/../connection.php';

$regName = $regEmail = $regPhone = $regStudentId = $regDept = "";
$regNameEr = $regEmailEr = $regPassEr = $regConfirmEr = $regPhoneEr = "";
$selectedRole = in_array($_POST['role'] ?? '', ['staff', 'student'], true) ? $_POST['role'] : 'student';

if (isset($_POST['do_register'])) {

    if (empty($_POST['full_name'])) {
        $regNameEr = "Full name is required.";
    } else {
        $regName = trim($_POST['full_name']);
        if (!preg_match("/^[a-zA-Z\s]+$/", $regName))
            $regNameEr = "Letters and spaces only.";
    }

    if (empty($_POST['email'])) {
        $regEmailEr = "Email is required.";
    } else {
        $regEmail = trim($_POST['email']);
        if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $regEmail))
            $regEmailEr = "Invalid email format.";
    }

    if (empty($_POST['password'])) {
        $regPassEr = "Password is required.";
    } elseif (strlen($_POST['password']) < 6) {
        $regPassEr = "Minimum 6 characters.";
    }

    if (empty($_POST['confirm_password'])) {
        $regConfirmEr = "Please confirm password.";
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $regConfirmEr = "Passwords do not match.";
    }

    if (!empty($_POST['phone'])) {
        $regPhone = trim($_POST['phone']);
        if (!preg_match("/^[0-9+\-\s]+$/", $regPhone))
            $regPhoneEr = "Numbers only.";
    }

    $regRole      = in_array($_POST['role'] ?? '', ['staff', 'student'], true) ? $_POST['role'] : 'student';
    $regStudentId = ($regRole == 'student') ? trim($_POST['student_id'] ?? '') : '';
    $regDept      = ($regRole == 'student') ? trim($_POST['department']  ?? '') : '';

    if (empty($regNameEr) && empty($regEmailEr) && empty($regPassEr) && empty($regConfirmEr) && empty($regPhoneEr)) {
        $ns = mysqli_real_escape_string($con, $regName);
        $es = mysqli_real_escape_string($con, $regEmail);
        $ps = mysqli_real_escape_string($con, $_POST['password']);
        $rs = mysqli_real_escape_string($con, $regRole);
        $ph = mysqli_real_escape_string($con, $regPhone);
        $si = mysqli_real_escape_string($con, $regStudentId);
        $dp = mysqli_real_escape_string($con, $regDept);

        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$es'");
        if (mysqli_num_rows($check) > 0) {
            $regEmailEr = "Email already registered.";
        } else {
            $sql = "INSERT INTO users (full_name, email, password, role, phone, student_id, department, status, created_at)
                    VALUES ('$ns', '$es', '$ps', '$rs', '$ph', '$si', '$dp', 'active', NOW())";
            if (mysqli_query($con, $sql)) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $regEmailEr = "Error: " . mysqli_error($con);
            }
        }
    }
}