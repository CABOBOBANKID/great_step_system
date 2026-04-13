<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Great Step Academy | Portal</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #002147;
            --accent: #00b4d8;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background-color: var(--primary) !important;
        }
        
        .hero {
            background: linear-gradient(rgba(0, 33, 71, 0.85), rgba(0, 33, 71, 0.85)), url('https://source.unsplash.com/1600x900/?school,classroom') center/cover no-repeat;
            color: white;
            padding: 120px 0;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3.2rem;
            font-weight: 700;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: var(--accent);
        }
        
        .card-title {
            font-weight: 600;
            color: #002147;
        }
        
        .btn-login {
            background-color: var(--accent);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: #0099b8;
            transform: scale(1.05);
        }
        
        .footer {
            background-color: var(--primary);
            color: white;
            padding: 40px 0 20px;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">
                🎓 Great Step Academy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to Great Step Academy Portal</h1>
            <p class="lead mt-3 mb-4">Empowering education through seamless access for Headmasters, Teachers, and Parents</p>
            <a href="#roles" class="btn btn-light btn-lg px-5">Choose Your Role</a>
        </div>
    </section>

    <!-- Role Cards -->
    <div class="container my-5" id="roles">
        <div class="row g-4 justify-content-center">
            
            <!-- Headmaster Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card text-center p-4 h-100">
                    <div class="card-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="card-title">Headmaster</h3>
                    <p class="text-muted">Manage students, staff, and the entire school system with powerful administrative tools.</p>
                    <a href="headmaster/headmaster_login.php" class="btn btn-login text-white mt-auto">Login as Headmaster</a>
                </div>
            </div>

            <!-- Teacher Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card text-center p-4 h-100">
                    <div class="card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="card-title">Teacher</h3>
                    <p class="text-muted">Enter and manage student scores, attendance, and academic records efficiently.</p>
                    <a href="teacher/teacher_login.php" class="btn btn-login text-white mt-auto">Login as Teacher</a>
                </div>
            </div>

            <!-- Parent Card -->
            <div class="col-lg-4 col-md-6">
                <div class="card text-center p-4 h-100">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Parent</h3>
                    <p class="text-muted">View your ward's academic performance, results, and important school updates.</p>
                    <a href="parent/check_result.php" class="btn btn-login text-white mt-auto">Check Student Result</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date("Y"); ?> Great Step Academy. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Designed for excellence in education</p>
                    <p class="mb-1">by Cabobo Solution</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>