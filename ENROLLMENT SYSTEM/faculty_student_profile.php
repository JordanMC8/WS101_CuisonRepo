<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/User.php';
require_once 'models/Enrollment.php';

// Check if user is logged in and is faculty
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header('Location: index.php');
    exit();
}

// Check if student ID is provided
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    header('Location: classes.php');
    exit();
}

$student_id = intval($_GET['student_id']);

$user_model = new User($pdo);
$enrollment_model = new Enrollment($pdo);

// Get student details
$student = $user_model->getUserById($student_id);

if (!$student || $student['role'] != 'student') {
    header('Location: classes.php');
    exit();
}

// Get student enrollments
$enrollments = $enrollment_model->getStudentEnrollments($student_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - <?php echo SITE_NAME; ?></title>
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

        .profile-header {
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

        .table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-text);
        }

        .badge {
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 20px;
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
        <div class="profile-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Student Profile</h1>
                    <p class="mb-0 opacity-75">Detailed information and academic history</p>
                </div>
                <a href="classes.php" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back to Classes
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h4 class="mb-0">Profile Information</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($student['profile_picture'])): ?>
                            <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>"
                                alt="Profile Picture"
                                class="img-fluid rounded-circle mb-3 profile-img">
                        <?php else: ?>
                            <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>

                        <h3 class="mb-1"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h3>
                        <p class="text-muted mb-3"><?php echo htmlspecialchars($student['email']); ?></p>
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <p class="mb-1"><strong>Student ID:</strong></p>
                                <p class="mb-1"><strong>Username:</strong></p>
                            </div>
                            <div class="text-end">
                                <p class="mb-1"><?php echo $student['id']; ?></p>
                                <p class="mb-1"><?php echo htmlspecialchars($student['username']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h4 class="mb-0">Signature</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($student['signature'])): ?>
                            <img src="<?php echo htmlspecialchars($student['signature']); ?>"
                                alt="Signature"
                                class="img-fluid signature-img">
                        <?php else: ?>
                            <div class="py-5">
                                <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No signature uploaded</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Enrollment History</h4>
                            <span class="badge bg-primary"><?php echo count($enrollments); ?> Subjects</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (count($enrollments) > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Credits</th>
                                            <th>Status</th>
                                            <th>Grade</th>
                                            <th>Date Enrolled</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($enrollments as $enrollment): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($enrollment['subject_code']); ?></td>
                                                <td><?php echo htmlspecialchars($enrollment['subject_name']); ?></td>
                                                <td><?php echo $enrollment['credits']; ?></td>
                                                <td>
                                                    <?php if ($enrollment['status'] == 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php elseif ($enrollment['status'] == 'enrolled'): ?>
                                                        <span class="badge bg-primary">Enrolled</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?php echo ucfirst(htmlspecialchars($enrollment['status'])); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($enrollment['grade'])): ?>
                                                        <?php
                                                        $grade = $enrollment['grade'];
                                                        $gradeClass = '';

                                                        // Check if grade is numeric to determine color coding
                                                        if (is_numeric($grade)) {
                                                            $numericGrade = floatval($grade);
                                                            if ($numericGrade <= 1.25) {
                                                                $gradeClass = 'gpa-a';
                                                            } elseif ($numericGrade <= 1.50) {
                                                                $gradeClass = 'gpa-b';
                                                            } elseif ($numericGrade <= 2.00) {
                                                                $gradeClass = 'gpa-c';
                                                            } elseif ($numericGrade <= 2.50) {
                                                                $gradeClass = 'gpa-d';
                                                            } else {
                                                                $gradeClass = 'gpa-f';
                                                            }
                                                        } else {
                                                            // Letter grades
                                                            $upperGrade = strtoupper($grade);
                                                            switch ($upperGrade) {
                                                                case 'A':
                                                                    $gradeClass = 'gpa-a';
                                                                    break;
                                                                case 'B':
                                                                    $gradeClass = 'gpa-b';
                                                                    break;
                                                                case 'C':
                                                                    $gradeClass = 'gpa-c';
                                                                    break;
                                                                case 'D':
                                                                    $gradeClass = 'gpa-d';
                                                                    break;
                                                                case 'F':
                                                                    $gradeClass = 'gpa-f';
                                                                    break;
                                                                default:
                                                                    $gradeClass = 'text-muted';
                                                            }
                                                        }
                                                        ?>
                                                        <span class="<?php echo $gradeClass; ?>"><?php echo htmlspecialchars($enrollment['grade']); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M j, Y', strtotime($enrollment['enrollment_date'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <h5>No Enrollment History</h5>
                                <p class="mb-0">This student has not enrolled in any subjects yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>