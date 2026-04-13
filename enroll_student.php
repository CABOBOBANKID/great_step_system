<?php
session_start();

if (!isset($_SESSION['headmaster'])) {
    header("Location: headmaster_login.php");
    exit();
}

include "../db.php";

$success = "";
$error = "";
$generated_student_id = "";

if (isset($_POST['save'])) {
    $name  = trim($_POST['name']);
    $dob   = $_POST['dob'];
    $class = trim($_POST['class']);

    if (empty($name) || empty($dob) || empty($class)) {
        $error = "All fields are required.";
    } else {
        // Generate unique Student ID (GSA + Year + 4 random digits)
        $year = date("Y");
        $random = rand(1000, 9999);
        $student_id = "GSA" . $year . $random;

        // Check if student_id already exists (rare but good practice)
        $check = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
        $check->bind_param("s", $student_id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $random = rand(1000, 9999); // regenerate if collision
            $student_id = "GSA" . $year . $random;
        }

        $sql = "INSERT INTO students (student_id, name, dob, class) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $student_id, $name, $dob, $class);

        if ($stmt->execute()) {
            $success = "Student enrolled successfully!";
            $generated_student_id = $student_id;
        } else {
            $error = "Error enrolling student: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll New Student - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            background: #002147;
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 260px;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        .form-card {
            max-width: 650px;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-5 px-3">
        <h4 class="fw-bold">🎓 Great Step Academy</h4>
        <p class="small mb-0">Headmaster Portal</p>
    </div>
    <ul class="nav flex-column px-3">
        <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="enroll_student.php" class="nav-link active bg-light text-dark"><i class="fas fa-user-plus me-2"></i> Enroll Student</a></li>
        <li class="nav-item"><a href="add_teacher.php" class="nav-link text-white"><i class="fas fa-chalkboard-teacher me-2"></i> Create Teacher</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white"><i class="fas fa-users me-2"></i> View All Students</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white"><i class="fas fa-file-alt me-2"></i> Academic Results</a></li>
        <li class="nav-item mt-5">
            <a href="../logout.php" class="nav-link text-danger" onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Enroll New Student</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>

    <div class="form-card card p-5">
        <?php if ($success): ?>
            <div class="alert alert-success text-center">
                <h4><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></h4>
                <p class="mb-0">Student ID: <strong><?= htmlspecialchars($generated_student_id) ?></strong></p>
                <small class="text-muted">Please save this Student ID. It will be used for login and result checking.</small>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="form-label fw-bold">Student Full Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control form-control-lg" 
                       name="name" 
                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                       placeholder="Enter student's full name" 
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control form-control-lg" 
                           name="dob" 
                           value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>"
                           required>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-bold">Class <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           name="class" 
                           value="<?= isset($_POST['class']) ? htmlspecialchars($_POST['class']) : '' ?>"
                           placeholder="e.g. Basic 5, JSS1, SSS2" 
                           required>
                </div>
            </div>

            <button type="submit" 
                    name="save" 
                    class="btn btn-primary btn-lg w-100">
                <i class="fas fa-user-plus me-2"></i> Enroll Student
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>