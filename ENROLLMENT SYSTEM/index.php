<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .profile-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .profile-card-header {
            border-radius: 8px 8px 0 0 !important;
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f7;
            padding: 15px 20px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .signature-img {
            max-width: 100%;
            max-height: 100px;
            object-fit: contain;
            border: 1px solid #e1e8ed;
            border-radius: 4px;
            background-color: #fff;
            padding: 10px;
        }

        .quick-actions-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .quick-actions-card-header {
            border-radius: 8px 8px 0 0 !important;
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f7;
            padding: 15px 20px;
        }

        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Welcome to <?php echo SITE_NAME; ?></h1>
                    <p class="mb-0 opacity-75">Hello, <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?> (<?php echo ucfirst($user_role); ?>)</p>
                </div>
                <div class="text-end">
                    <div class="fs-5">Dashboard</div>
                    <div class="small opacity-75">Today: <?php echo date('M j, Y'); ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h4 class="mb-0">Profile Picture</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($_SESSION['profile_picture'])): ?>
                            <img src="<?php echo $_SESSION['profile_picture']; ?>" alt="Profile Picture" class="img-fluid profile-img">
                        <?php else: ?>
                            <div class="py-5">
                                <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No profile picture uploaded yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h4 class="mb-0">Signature</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($_SESSION['signature'])): ?>
                            <img src="<?php echo $_SESSION['signature']; ?>" alt="Signature" class="img-fluid signature-img">
                        <?php else: ?>
                            <div class="py-5">
                                <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No signature uploaded yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card quick-actions-card">
                    <div class="quick-actions-card-header">
                        <h4 class="mb-0">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="profile.php" class="btn btn-primary">
                                <i class="fas fa-user-edit me-2"></i>Update Profile
                            </a>
                            <?php if ($user_role == 'student'): ?>
                                <a href="enroll.php" class="btn btn-success">
                                    <i class="fas fa-book-open me-2"></i>Enroll in Subjects
                                </a>
                            <?php elseif ($user_role == 'faculty'): ?>
                                <a href="classes.php" class="btn btn-info">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>View My Classes
                                </a>
                            <?php elseif ($user_role == 'admin'): ?>
                                <a href="subjects.php" class="btn btn-warning">
                                    <i class="fas fa-book me-2"></i>Manage Subjects
                                </a>
                                <a href="users.php" class="btn btn-danger">
                                    <i class="fas fa-users me-2"></i>Manage Users
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>