<?php
session_start();

if (!isset($_SESSION['headmaster'])) {
    header("Location: headmaster_login.php");
    exit();
}

include "../db.php";

$success = "";
$error = "";

if (isset($_POST['save'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $subject  = trim($_POST['subject']);
    $class    = trim($_POST['class']);

    // Basic validation
    if (empty($username) || empty($password) || empty($subject) || empty($class)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM teachers WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO teachers (username, password, subject, class) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $hashed_password, $subject, $class);

            if ($stmt->execute()) {
                $success = "Teacher account created successfully!";
                // Optional: Clear form after success
                $_POST = array();
            } else {
                $error = "Failed to create teacher. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Teacher Account - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        .sidebar {
            background: #002147;
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 260px;
        }
        .form-card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Sidebar (same as dashboard) -->
<div class="sidebar">
    <div class="text-center mb-5 px-3 pt-4">
        <h4 class="fw-bold">🎓 Great Step Academy</h4>
        <p class="small">Headmaster Portal</p>
    </div>
    <ul class="nav flex-column px-3">
        <li><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
        <li><a href="enroll_student.php" class="nav-link text-white"><i class="fas fa-user-plus me-2"></i> Enroll Student</a></li>
        <li><a href="add_teacher.php" class="nav-link active bg-light text-dark"><i class="fas fa-chalkboard-teacher me-2"></i> Create Teacher</a></li>
        <li><a href="#" class="nav-link text-white"><i class="fas fa-users me-2"></i> View Students</a></li>
        <li><a href="../logout.php" class="nav-link text-danger mt-5" onclick="return confirm('Logout?')"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create New Teacher Account</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>

    <div class="form-card card p-5">
        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           name="username" 
                           value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                           placeholder="e.g. teacher_john" 
                           required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           placeholder="Minimum 6 characters" 
                           required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           name="subject" 
                           value="<?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '' ?>"
                           placeholder="e.g. Mathematics" 
                           required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Class <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           name="class" 
                           value="<?= isset($_POST['class']) ? htmlspecialchars($_POST['class']) : '' ?>"
                           placeholder="e.g. JSS1 or SSS2" 
                           required>
                </div>
            </div>

            <button type="submit" 
                    name="save" 
                    class="btn btn-primary btn-lg w-100 mt-4">
                <i class="fas fa-user-plus me-2"></i> Create Teacher Account
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>