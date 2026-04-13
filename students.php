<?php
session_start();

if (!isset($_SESSION['headmaster'])) {
    header("Location: headmaster_login.php");
    exit();
}

include "../db.php";

// Fetch students with ordering
$sql = "SELECT student_id, name, dob, class FROM students ORDER BY class ASC, name ASC";
$result = $conn->query($sql);
$total_students = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Students - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        .table th {
            background-color: #002147;
            color: white;
            font-weight: 600;
        }
        .table tbody tr:hover {
            background-color: #f1f8ff;
            transition: background 0.2s;
        }
        .search-box {
            max-width: 450px;
        }
        .student-count {
            font-size: 1.1rem;
            color: #002147;
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="text-center mb-5 px-3">
        <h4 class="fw-bold">🎓 Great Step Academy</h4>
        <p class="small mb-0">Headmaster Portal</p>
    </div>
    <ul class="nav flex-column px-3">
        <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="enroll_student.php" class="nav-link text-white"><i class="fas fa-user-plus me-2"></i> Enroll Student</a></li>
        <li class="nav-item"><a href="add_teacher.php" class="nav-link text-white"><i class="fas fa-chalkboard-teacher me-2"></i> Create Teacher</a></li>
        <li class="nav-item"><a href="view_students.php" class="nav-link active bg-light text-dark"><i class="fas fa-users me-2"></i> View All Students</a></li>
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
        <h2 class="mb-0">Registered Students</h2>
        <a href="enroll_student.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Enroll New Student
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="student-count">
            Total Students: <strong><?= $total_students ?></strong>
        </div>
        <div class="search-box">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by Student ID, Name or Class...">
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="studentsTable">
                    <thead>
                        <tr>
                            <th width="15%">Student ID</th>
                            <th>Full Name</th>
                            <th>Date of Birth</th>
                            <th>Class</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($row['student_id']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['dob']) ?></td>
                                    <td><?= htmlspecialchars($row['class']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="alert('View details for <?= htmlspecialchars($row['name']) ?> (Coming Soon)')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No students have been enrolled yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Live Search Script -->
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        const rows = document.querySelectorAll('#studentsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toUpperCase();
            row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
        });
    });
</script>

</body>
</html>