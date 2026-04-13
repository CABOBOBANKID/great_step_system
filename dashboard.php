<?php
session_start();

if (!isset($_SESSION['teacher'])) {
    header("Location: teacher_login.php");
    exit();
}

include "../db.php";

// Get teacher's name/username for welcome message
$teacher_name = $_SESSION['teacher'];

// Count scores entered by this teacher
$score_count = $conn->query("SELECT COUNT(*) as count FROM grades WHERE teacher_username = '$teacher_name'")->fetch_assoc()['count'] ?? 0; 
// Note: If you don't have 'teacher_username' column yet, we'll use total grades for now

$total_grades = $conn->query("SELECT COUNT(*) as count FROM grades")->fetch_assoc()['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #002147;
            --accent: #00b4d8;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .sidebar {
            background: var(--primary);
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 260px;
            padding-top: 20px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 14px 25px;
            margin: 6px 15px;
            border-radius: 0 30px 30px 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--accent);
            color: white;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
        }
        
        .top-bar {
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 18px 30px;
            border-radius: 12px;
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
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link active">
                <i class="fas fa-tachometer-alt me-3"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="enter_score.php" class="nav-link">
                <i class="fas fa-edit me-3"></i> Enter Student Score
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-list me-3"></i> My Classes
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-chart-bar me-3"></i> My Results
            </a>
        </li>
        <li class="nav-item mt-5">
            <a href="../logout.php" class="nav-link text-danger" 
               onclick="return confirm('Are you sure you want to logout?')">
                <i class="fas fa-sign-out-alt me-3"></i> Logout
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Welcome back, <strong><?= htmlspecialchars($teacher_name) ?></strong> 👋</h2>
            <p class="text-muted mb-0">Manage your students' academic records</p>
        </div>
        <span class="badge bg-success px-3 py-2">Online</span>
    </div>

    <h4 class="mb-4">Teacher Overview</h4>
    
    <div class="row g-4">
        <!-- Stat Cards -->
        <div class="col-xl-6 col-md-6">
            <div class="stat-card card bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-primary"><?= number_format($total_grades) ?></h2>
                        <p class="text-muted mb-0 fw-semibold">Total Scores Recorded</p>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="fas fa-file-alt fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 col-md-6">
            <div class="stat-card card bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-success"><?= number_format($score_count) ?></h2>
                        <p class="text-muted mb-0 fw-semibold">Scores Entered by You</p>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="fas fa-edit fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-5">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="enter_score.php" class="btn btn-primary w-100 py-3 shadow-sm">
                    <i class="fas fa-edit me-2"></i> Enter New Student Score
                </a>
            </div>
            <div class="col-md-6">
                <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm">
                    <i class="fas fa-list me-2"></i> View My Entered Scores
                </a>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="../index.php" class="btn btn-outline-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>