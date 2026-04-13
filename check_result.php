<?php
include "../db.php";

$report = null;
$error = "";

if (isset($_POST['check'])) {
    $student_id = trim($_POST['student_id']);

    if (empty($student_id)) {
        $error = "Please enter a Student ID.";
    } else {
        // Get student details
        $stmt = $conn->prepare("SELECT name, class FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $studentResult = $stmt->get_result();

        if ($studentResult->num_rows === 0) {
            $error = "Student ID not found.";
        } else {
            $student = $studentResult->fetch_assoc();
            $class = $student['class'];

            // Get student's results
            $stmt = $conn->prepare("SELECT subject, score, grade, status, term FROM grades WHERE student_id = ? ORDER BY term, subject");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $subjects = [];
            $total = 0;
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $subjects[] = $row;
                $total += $row['score'];
                $count++;
            }

            if ($count > 0) {
                $average = round($total / $count, 2);

                // Calculate Position in Class
                $rankQuery = "
                    SELECT student_id, SUM(score) as total_score 
                    FROM grades 
                    WHERE class = ? 
                    GROUP BY student_id 
                    ORDER BY total_score DESC
                ";
                $rankStmt = $conn->prepare($rankQuery);
                $rankStmt->bind_param("s", $class);
                $rankStmt->execute();
                $rankResult = $rankStmt->get_result();

                $position = 1;
                $found = false;

                while ($rankRow = $rankResult->fetch_assoc()) {
                    if ($rankRow['student_id'] == $student_id) {
                        $found = true;
                        break;
                    }
                    $position++;
                }

                $report = [
                    'student_id' => $student_id,
                    'name'       => $student['name'],
                    'class'      => $class,
                    'subjects'   => $subjects,
                    'total'      => $total,
                    'average'    => $average,
                    'position'   => $found ? $position : 'N/A',
                    'total_subjects' => $count
                ];
            } else {
                $error = "No results found for this student yet.";
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
    <title>Check Result - Great Step Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        .result-card {
            max-width: 800px;
            margin: 40px auto;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .header {
            background: #002147;
            color: white;
            padding: 25px;
            border-radius: 20px 20px 0 0;
        }
        table th {
            background: #002147;
            color: white;
        }
        .average {
            font-size: 1.4rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="result-card card">
        <div class="header text-center">
            <h2><i class="fas fa-graduation-cap me-2"></i> Great Step Academy</h2>
            <p class="mb-0">Student Report Card</p>
        </div>

        <div class="card-body p-5">
            <!-- Search Form -->
            <form method="POST" class="mb-5">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Student ID</label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               name="student_id" 
                               value="<?= isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '' ?>"
                               placeholder="Enter Student ID (e.g. GSA20251023)" 
                               required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="check" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search me-2"></i> Check Result
                        </button>
                    </div>
                </div>
            </form>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($report): ?>
                <div class="text-center mb-4">
                    <h3><?= htmlspecialchars($report['name']) ?></h3>
                    <p class="text-muted">Student ID: <strong><?= htmlspecialchars($report['student_id']) ?></strong> | Class: <strong><?= htmlspecialchars($report['class']) ?></strong></p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Score</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Term</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($report['subjects'] as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['subject']) ?></td>
                                    <td><strong><?= $row['score'] ?></strong></td>
                                    <td><?= $row['grade'] ?></td>
                                    <td>
                                        <span class="badge <?= $row['status'] === 'Pass' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['term']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4 text-center">
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <strong>Total Score</strong><br>
                            <span class="average"><?= $report['total'] ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <strong>Average</strong><br>
                            <span class="average"><?= $report['average'] ?>%</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <strong>Position in Class</strong><br>
                            <span class="average text-primary"><?= $report['position'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="../index.php" class="btn btn-outline-primary">
                        ← Back to Portal
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>