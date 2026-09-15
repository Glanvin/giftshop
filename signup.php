<?php
require __DIR__ . '/backend/auth/register-controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up – CU Giftshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="area-auth">

<div class="coin-overlay" id="coinOverlay">
    <div class="coin">
        <img src="product-images/culogo.jpg" alt="CU Logo">
    </div>
</div>

<div class="auth-card signup-card">

    <div class="left-panel signup-panel">
        <div class="logo-circle">
            <img src="product-images/culogo.jpg" alt="CU Logo">
        </div>
        <h2>Welcome Back!</h2>
        <p>Already have an account? Log in to continue.</p>
        <button class="btn-switch" onclick="goToLogin()">LOG IN HERE</button>
    </div>

    <div class="right-panel signup-panel">
        <div class="form-title">Create Account</div>
        <div class="form-sub">Fill in your details to get started</div>

        <form method="POST" id="signupForm">

            <!-- Full Name -->
            <div class="mb-2">
                <label class="form-label">Full Name *</label>
                <input type="text" name="full_name" class="form-control"
                       placeholder="Juan Dela Cruz"
                       value="<?php echo htmlspecialchars($regName); ?>">
                <span class="field-error"><?php echo $regNameEr; ?></span>
            </div>

            <!-- Email -->
            <div class="mb-2">
                <label class="form-label">Email Address *</label>
                <input type="text" name="email" class="form-control"
                       placeholder="name@cu.edu.ph"
                       value="<?php echo htmlspecialchars($regEmail); ?>">
                <span class="field-error"><?php echo $regEmailEr; ?></span>
            </div>

            <!-- Password -->
            <div class="row mb-2">
                <div class="col-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 chars">
                    <span class="field-error"><?php echo $regPassEr; ?></span>
                </div>
                <div class="col-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat">
                    <span class="field-error"><?php echo $regConfirmEr; ?></span>
                </div>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control"
                       placeholder="09XXXXXXXXX"
                       value="<?php echo htmlspecialchars($regPhone); ?>">
                <span class="field-error"><?php echo $regPhoneEr; ?></span>
            </div>

            <!-- Role -->
            <div class="mb-2">
                <label class="form-label">Account Type *</label>
                <div class="role-toggle">
                    <div class="role-pill <?php echo $selectedRole=='student'?'active':''; ?>"
                         onclick="selectRole('student')" id="pill-student">
                        <i class="bi bi-mortarboard"></i> Student
                    </div>
                    <div class="role-pill <?php echo $selectedRole=='staff'?'active':''; ?>"
                         onclick="selectRole('staff')" id="pill-staff">
                        <i class="bi bi-person-badge"></i> Staff
                    </div>
                </div>
                <input type="hidden" name="role" id="roleInput" value="<?php echo htmlspecialchars($selectedRole); ?>">
            </div>

            <!-- Staff -->
            <div class="staff-note <?php echo $selectedRole=='staff'?'show':''; ?>" id="staffNote">
                <i class="bi bi-info-circle me-1"></i>
                Staff accounts do not require Student ID or Department.
            </div>

            <!-- Student ID and Department (disabled for staff) -->
            <div class="row mb-3">
                <div class="col-6">
                    <div class="field-wrap <?php echo $selectedRole=='staff'?'locked':''; ?>" id="wrap-student-id">
                        <label class="form-label">Student ID</label>
                        <input type="text" name="student_id" id="studentId"
                               class="form-control"
                               placeholder="2024-00001"
                               value="<?php echo htmlspecialchars($regStudentId); ?>"
                               <?php echo $selectedRole=='staff'?'disabled':''; ?>>
                        <span class="lock-icon">🔒</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="field-wrap <?php echo $selectedRole=='staff'?'locked':''; ?>" id="wrap-department">
                        <label class="form-label">Department</label>
                        <select name="department" id="department"
                                class="form-select"
                                <?php echo $selectedRole=='staff'?'disabled':''; ?>>
                            <option value="">-- Select --</option>
                            <option value="Computer Studies"  <?php echo $regDept=='Computer Studies'  ?'selected':'';?>>Computer Studies</option>
                            <option value="Health Science"    <?php echo $regDept=='Health Science'    ?'selected':'';?>>Health Science</option>
                            <option value="Engineering"       <?php echo $regDept=='Engineering'       ?'selected':'';?>>Engineering</option>
                            <option value="Arts and Sciences" <?php echo $regDept=='Arts and Sciences' ?'selected':'';?>>Arts and Sciences</option>
                            <option value="Accountancy"       <?php echo $regDept=='Accountancy'       ?'selected':'';?>>Accountancy</option>
                        </select>
                        <span class="lock-icon">🔒</span>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label small" for="terms">
                    I agree to the university's terms and conditions
                </label>
            </div>

            <button type="submit" name="do_register" class="btn-main">Register Account</button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="text-muted text-decoration-none small">← Back to Homepage</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/signup.js"></script>
</body>
</html>