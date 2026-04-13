<?php
session_start();

if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit();
}

include "../db.php";

$success = "";
$error = "";

if (isset($_POST['save'])) {
    $student_id = trim($_POST['student_id']);
    $subject    = trim($_POST['subject']);
    $score      = (int)$_POST['score'];
    $class      = trim($_POST['class']);
    $term       = $_POST['term'];

    if (empty($student_id) || empty($subject) || empty($class) || empty($term)) {
        $error = "All fields are required.";
    } elseif ($score < 0 || $score > 100) {
        $error = "Score must be between 0 and 100.";
    } else {
        // Calculate Grade
        if ($score >= 80) $grade = "A";
        elseif ($score >= 70) $grade = "B";
        elseif ($score >= 60) $grade = "C";
        elseif ($score >= 50) $grade = "D";
        else $grade = "F";

        // Calculate Status
        $status = ($score >= 50) ? "Pass" : "Fail";

        // Secure Insert
        $sql = "INSERT INTO grades (student_id, subject, score, grade, status, class, term) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", $student_id, $subject, $score, $grade, $status, $class, $term);

        if ($stmt->execute()) {
            $success = "Score saved successfully for Student ID: <strong>" . htmlspecialchars($student_id) . "</strong> in " . htmlspecialchars($subject);
        } else {
            $error = "Failed to save score. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Student Score - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
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
            max-width: 720px;
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
        <p class="small mb-0">Teacher Portal</p>
    </div>
    
    <ul class="nav flex-column px-3">
        <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="enter_score.php" class="nav-link active bg-light text-dark"><i class="fas fa-edit me-2"></i> Enter Student Score</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white"><i class="fas fa-list-ul me-2"></i> My Classes</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white"><i class="fas fa-chart-bar me-2"></i> View Results</a></li>
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
        <h2><i class="fas fa-edit"></i> Enter Student Score</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>

    <div class="form-card card p-5">
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Student ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="student_id" 
                           value="<?= isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '' ?>" 
                           placeholder="e.g. GSA20251023" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                    <select class="form-select" name="subject" required>
                        <option value="">Select Subject</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="English">English</option>
                        <option value="Science">Science</option>
                        <option value="Social Studies">Social Studies</option>
                        <option value="History">History</option>
                        <option value="ICT">ICT</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Score (0-100) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="score" min="0" max="100" 
                           value="<?= isset($_POST['score']) ? htmlspecialchars($_POST['score']) : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Class <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="class" 
                           value="<?= isset($_POST['class']) ? htmlspecialchars($_POST['class']) : '' ?>" 
                           placeholder="e.g. JSS1, Basic 5" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Term <span class="text-danger">*</span></label>
                    <select class="form-select" name="term" required>
                        <option value="">Select Term</option>
                        <option value="Term1">Term 1</option>
                        <option value="Term2">Term 2</option>
                        <option value="Term3">Term 3</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="save" class="btn btn-primary btn-lg w-100 mt-4">
                <i class="fas fa-save me-2"></i> Save Score
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>