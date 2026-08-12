<?php
session_start();
include('connection.php');

$regName = $regEmail = $regPhone = $regStudentId = $regDept = "";
$regNameEr = $regEmailEr = $regPassEr = $regConfirmEr = $regPhoneEr = "";
$selectedRole = $_POST['role'] ?? 'student';

if (isset($_POST['do_register'])) {

    // Full Name
    if (empty($_POST['full_name'])) {
        $regNameEr = "Full name is required.";
    } else {
        $regName = trim($_POST['full_name']);
        if (!preg_match("/^[a-zA-Z\s]+$/", $regName))
            $regNameEr = "Letters and spaces only.";
    }

    // Email
    if (empty($_POST['email'])) {
        $regEmailEr = "Email is required.";
    } else {
        $regEmail = trim($_POST['email']);
        if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $regEmail))
            $regEmailEr = "Invalid email format.";
    }

    // Password
    if (empty($_POST['password'])) {
        $regPassEr = "Password is required.";
    } elseif (strlen($_POST['password']) < 6) {
        $regPassEr = "Minimum 6 characters.";
    }

    // Confirm Password
    if (empty($_POST['confirm_password'])) {
        $regConfirmEr = "Please confirm password.";
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $regConfirmEr = "Passwords do not match.";
    }

    // Phone (optional)
    if (!empty($_POST['phone'])) {
        $regPhone = trim($_POST['phone']);
        if (!preg_match("/^[0-9+\-\s]+$/", $regPhone))
            $regPhoneEr = "Numbers only.";
    }

    // Student fields only matter if role is student
    $regRole      = $_POST['role'] ?? 'student';
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up – CU Giftshop</title>
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
        display: flex; 
        background: white; 
        border-radius: 18px; 
        box-shadow: 0 20px 60px rgba(0,0,0,0.35); 
        overflow: hidden; 
        flex-direction: row-reverse; 
    }

    .left-panel { 
        width: 38%; 
        background: #6d1223; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        padding: 50px 30px; 
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
        font-size: 32px; 
        font-weight: bold; 
        margin-bottom: 10px; 
    }

    .left-panel p  { 
        font-size: 14px; 
        opacity: 0.85; 
        margin-bottom: 30px; 
    }

    .btn-switch { 
        background: transparent; 
        color: white; 
        border: 2px solid white; 
        padding: 10px 30px; 
        border-radius: 25px; 
        font-weight: 700; 
        font-size: 14px; 
        text-decoration: none; 
        transition: 0.3s; 
        display: inline-block; 
    }

    .btn-switch:hover { 
        background: white; 
        color: #6d1223; 
    }

    .right-panel { 
        width: 62%; 
        padding: 40px 50px; 
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
        overflow-y: auto; 
    }

    .form-title { 
        color: #6d1223; 
        font-size: 26px; 
        font-weight: 800; 
        margin-bottom: 4px; 
    }

    .form-sub   { 
        color: #bbb; 
        font-size: 13px; 
        margin-bottom: 18px; 
    }

    .form-label { 
        font-weight: 600; 
        font-size: 13px; 
        color: #444; 
        margin-bottom: 3px; 
        display: block; 
    }

    .form-control, .form-select { 
        border-radius: 8px; 
        padding: 9px 13px; 
        border: 1px solid #ddd; 
        font-size: 13px; 
        width: 100%; 
        transition: border 0.2s, background 0.2s; 
    }

    .form-control:focus, .form-select:focus { 
        border-color: #6d1223; 
        box-shadow: none; 
        outline: none; 
    }

    .form-control:disabled, .form-select:disabled {
        background: #f5f5f5; 
        color: #bbb; 
        cursor: not-allowed; 
        border-color: #eee; 
    }

    .field-wrap { 
        position: relative; 
    }

    .lock-icon {
        position: absolute; 
        right: 12px; 
        top: 50%; 
        transform: translateY(-50%); 
        color: #ccc; 
        font-size: 14px; 
        display: none; 
        pointer-events: none; 
    }

    .locked .lock-icon { 
        display: block; 
    }

    .locked label { 
        color: #bbb; 
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

    .role-toggle { 
        display: flex; 
        gap: 10px; 
        margin-bottom: 16px; 
    }

    .role-pill {
        flex: 1; 
        padding: 10px; 
        border: 2px solid #ddd; 
        border-radius: 10px; 
        text-align: center; 
        cursor: pointer; 
        font-weight: 600; 
        font-size: 13px; 
        color: #888; 
        transition: 0.2s; 
        user-select: none; 
    }

    .role-pill.active { 
        border-color: #6d1223; 
        background: #6d1223; 
        color: white; 
    }

    .role-pill:hover:not(.active) { 
        border-color: #6d1223; 
        color: #6d1223; 
    }

    .role-pill i { 
        display: block; 
        font-size: 20px; 
        margin-bottom: 4px; 
    }

    .staff-note {
        display: none; 
        background: #fff8f0; 
        border-left: 3px solid #e65100; 
        border-radius: 6px; 
        padding: 8px 12px; 
        font-size: 12px; 
        color: #e65100; 
        margin-bottom: 12px; 
    }

    .staff-note.show { 
        display: block; 
    }

    .coin-overlay { 
        display: none; 
        position: fixed; 
        inset: 0; 
        background: rgba(0,0,0,0.5); 
        z-index: 9999; 
        align-items: center; 
        justify-content: center; 
    }

    .coin-overlay.show { 
        display: flex; 
    }

    .coin { 
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

    .coin img { 
        height: 78%; 
        width: auto; 
    }

    @keyframes coinFlip {
        0%   { transform: scale(0.3) rotateY(0deg);   opacity: 0; }
        20%  { transform: scale(1)   rotateY(0deg);   opacity: 1; }
        50%  { transform: scale(1.1) rotateY(180deg); opacity: 1; }
        80%  { transform: scale(1)   rotateY(360deg); opacity: 1; }
        100% { transform: scale(0)   rotateY(360deg); opacity: 0; }
    }
    </style>
</head>
<body>

<div class="coin-overlay" id="coinOverlay">
    <div class="coin">
        <img src="Product-Images/culogo.jpg" alt="CU Logo">
    </div>
</div>

<div class="auth-card">

    <div class="left-panel">
        <div class="logo-circle">
            <img src="Product-Images/culogo.jpg" alt="CU Logo">
        </div>
        <h2>Welcome Back!</h2>
        <p>Already have an account? Log in to continue.</p>
        <button class="btn-switch" onclick="goToLogin()">LOG IN HERE</button>
    </div>

    <div class="right-panel">
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

<script>
    function selectRole(role) {
        document.getElementById('roleInput').value = role;

        // Toggle pill active state
        document.getElementById('pill-student').classList.toggle('active', role === 'student');
        document.getElementById('pill-staff').classList.toggle('active', role === 'staff');

        const isStaff     = role === 'staff';
        const studentId   = document.getElementById('studentId');
        const department  = document.getElementById('department');
        const staffNote   = document.getElementById('staffNote');
        const wrapStu     = document.getElementById('wrap-student-id');
        const wrapDept    = document.getElementById('wrap-department');

        // Disable/enable fields
        studentId.disabled  = isStaff;
        department.disabled = isStaff;

        // Clear values when switching to staff
        if (isStaff) {
            studentId.value  = '';
            department.value = '';
        }

        // Add/remove locked class for visual
        wrapStu.classList.toggle('locked',  isStaff);
        wrapDept.classList.toggle('locked', isStaff);

        // Show/hide staff note
        staffNote.classList.toggle('show', isStaff);
    }

    function goToLogin() {
        const overlay = document.getElementById('coinOverlay');
        const coin    = overlay.querySelector('.coin');
        coin.style.animation = 'none';
        coin.offsetHeight;
        coin.style.animation = '';
        overlay.classList.add('show');
        setTimeout(() => { window.location.href = 'login.php'; }, 950);
    }
</script>

</body>
</html>