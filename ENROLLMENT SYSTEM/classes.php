<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Faculty.php';
require_once 'models/Enrollment.php';
require_once 'models/User.php';

// Check if user is logged in and is faculty
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header('Location: index.php');
    exit();
}

$faculty_model = new Faculty($pdo);
$enrollment_model = new Enrollment($pdo);
$user_model = new User($pdo);

$message = '';
$message_type = '';

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_grade'])) {
    $enrollment_id = intval($_POST['enrollment_id']);
    $grade = trim($_POST['grade']);

    // Validate grade input (can be letter grade or numeric)
    if (!empty($grade)) {
        $formatted_grade = '';

        // Check if grade is a letter grade
        $upper_grade = strtoupper($grade);
        switch ($upper_grade) {
            case 'A':
                $formatted_grade = '1.25';
                break;
            case 'B':
                $formatted_grade = '1.50';
                break;
            case 'C':
                $formatted_grade = '2.00';
                break;
            case 'D':
                $formatted_grade = '2.50';
                break;
            case 'F':
                $formatted_grade = '3.00';
                break;
            default:
                // Check if grade is numeric
                if (is_numeric($grade)) {
                    $numeric_grade = floatval($grade);
                    // Validate numeric grade range (0.00-4.00)
                    if ($numeric_grade >= 0.00 && $numeric_grade <= 4.00) {
                        $formatted_grade = number_format($numeric_grade, 2.00, '.', '');
                    } else {
                        $message = 'Invalid numeric grade. Please enter a value between 0.00 and 4.00.';
                        $message_type = 'danger';
                    }
                } else {
                    $message = 'Invalid grade. Please enter a letter grade (A, B, C, D, F) or numeric grade (0.00-4.00).';
                    $message_type = 'danger';
                }
        }

        // If we have a valid formatted grade, submit it
        if (!empty($formatted_grade)) {
            if ($enrollment_model->submitGrade($enrollment_id, $formatted_grade)) {
                $message = 'Grade submitted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to submit grade.';
                $message_type = 'danger';
            }
        }
    } else {
        $message = 'Please enter a grade.';
        $message_type = 'danger';
    }
}

// Get subjects assigned to faculty
$assigned_subjects = $faculty_model->getAssignedSubjects($_SESSION['user_id']);

// Function to get student details
function getStudentDetails($user_model, $student_id)
{
    return $user_model->getUserById($student_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - <?php echo SITE_NAME; ?></title>
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

        .faculty-student-image {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border: 2px solid #e1e8ed;
            border-radius: 50%;
        }

        .faculty-signature-image {
            max-width: 120px;
            max-height: 50px;
            object-fit: contain;
            border: 1px solid #e1e8ed;
            border-radius: 4px;
            background-color: #fff;
            padding: 2px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .feature-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .feature-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .class-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .class-card-header {
            border-radius: 8px 8px 0 0 !important;
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f7;
            padding: 15px 20px;
        }

        .grade-input {
            max-width: 120px;
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

        .alert {
            border-radius: 8px;
        }

        .welcome-section {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Faculty Dashboard</h1>
                    <p class="mb-0 opacity-75">Manage your classes, students, and grades</p>
                </div>
                <div class="text-end">
                    <div class="fs-5"><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></div>
                    <div class="small opacity-75">Faculty ID: <?php echo $_SESSION['user_id']; ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <h3 class="mb-0">Faculty Features</h3>
                        <p class="text-muted mb-3">Essential tools for managing your academic responsibilities</p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card feature-card h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                        <i class="fas fa-users text-primary mb-3"></i>
                                        <h5>Class Lists</h5>
                                        <p class="text-muted mb-0">View all students enrolled in your subjects</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card feature-card h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                        <i class="fas fa-id-card text-success mb-3"></i>
                                        <h5>Student Profiles</h5>
                                        <p class="text-muted mb-0">Access student photos, signatures, and records</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card feature-card h-100">
                                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                        <i class="fas fa-graduation-cap text-warning mb-3"></i>
                                        <h5>Grade Submission</h5>
                                        <p class="text-muted mb-0">Submit and manage student grades efficiently</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">My Classes</h3>
            <span class="badge bg-primary"><?php echo count($assigned_subjects); ?> Classes</span>
        </div>

        <?php if (count($assigned_subjects) > 0): ?>
            <?php foreach ($assigned_subjects as $subject): ?>
                <div class="card class-card">
                    <div class="class-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">
                                    <?php echo htmlspecialchars($subject['subject_code'] . ' - ' . $subject['subject_name']); ?>
                                </h4>
                                <div class="small text-muted">Subject ID: <?php echo $subject['id']; ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary"><?php echo $subject['credits']; ?> Credits</span>
                                <div class="small text-muted mt-1"><?php echo count($enrollment_model->getStudentsInSubject($subject['id'])); ?> Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-4 text-muted"><?php echo htmlspecialchars($subject['description']); ?></p>

                        <h5 class="mb-3">Enrolled Students</h5>

                        <?php
                        // Get students enrolled in this subject
                        $students = $enrollment_model->getStudentsInSubject($subject['id']);
                        ?>

                        <?php if (count($students) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Profile Picture</th>
                                            <th>Signature</th>
                                            <th>Status</th>
                                            <th>Current Grade</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                                <td>
                                                    <strong>
                                                        <a href="faculty_student_profile.php?student_id=<?php echo $student['student_id']; ?>"
                                                            class="text-decoration-none text-dark">
                                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                                        </a>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <?php if (!empty($student['profile_picture'])): ?>
                                                        <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>"
                                                            alt="Profile Picture"
                                                            class="img-thumbnail faculty-student-image rounded">
                                                    <?php else: ?>
                                                        <span class="text-muted">No picture</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($student['signature'])): ?>
                                                        <img src="<?php echo htmlspecialchars($student['signature']); ?>"
                                                            alt="Signature"
                                                            class="img-thumbnail faculty-signature-image rounded">
                                                    <?php else: ?>
                                                        <span class="text-muted">No signature</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($student['status'] == 'completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php elseif ($student['status'] == 'enrolled'): ?>
                                                        <span class="badge bg-primary">Enrolled</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><?php echo ucfirst(htmlspecialchars($student['status'])); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($student['status'] == 'completed' && !empty($student['grade'])): ?>
                                                        <span class="fw-bold text-success"><?php echo htmlspecialchars($student['grade']); ?></span>
                                                    <?php elseif (!empty($student['grade'])): ?>
                                                        <span class="text-warning"><?php echo htmlspecialchars($student['grade']); ?> (Draft)</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not graded</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($student['status'] != 'completed'): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="enrollment_id" value="<?php echo $student['id']; ?>">
                                                            <div class="input-group input-group-sm grade-input">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    name="grade"
                                                                    placeholder="A-F or 0.00-4.00"
                                                                    title="Enter letter grade (A, B, C, D, F) or numeric grade (0.00-4.00)"
                                                                    required>
                                                                <button type="submit"
                                                                    name="submit_grade"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    onclick="return confirm('Are you sure you want to submit this grade for ' + 
                                                                        '<?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>' + '?')">
                                                                    <i class="fas fa-paper-plane"></i> Submit
                                                                </button>
                                                            </div>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-success fw-bold"><?php echo htmlspecialchars($student['grade']); ?>/5.00</span>
                                                        <span class="badge bg-success">Final</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Total students enrolled: <?php echo count($students); ?>
                                </small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <p class="mb-0">No students enrolled in this subject yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <h5>No Assigned Classes</h5>
                <p>You are not currently assigned to teach any subjects. Please contact the administrator to be assigned classes.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Grade validation script
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all grade submission forms
            const gradeForms = document.querySelectorAll('form');
            gradeForms.forEach(function(form) {
                const submitButton = form.querySelector('button[name="submit_grade"]');
                if (submitButton) {
                    form.addEventListener('submit', function(e) {
                        const gradeInput = form.querySelector('input[name="grade"]');
                        const gradeValue = gradeInput.value.trim();

                        // Check if grade is entered
                        if (gradeValue === '') {
                            alert('Please enter a grade.');
                            e.preventDefault();
                            gradeInput.focus();
                            return false;
                        }

                        // Check if grade is a valid letter grade (A, B, C, D, F)
                        const upperGrade = gradeValue.toUpperCase();
                        const validLetterGrades = ['A', 'B', 'C', 'D', 'F'];

                        if (validLetterGrades.includes(upperGrade)) {
                            // Valid letter grade, no further validation needed
                            return true;
                        }

                        // Check if grade is numeric
                        const numericGrade = parseFloat(gradeValue);
                        if (isNaN(numericGrade)) {
                            alert('Please enter a valid grade (letter grade A-F or numeric grade 0.00-4.00).');
                            e.preventDefault();
                            gradeInput.focus();
                            return false;
                        }

                        // Check if numeric grade is within valid range (0.00-4.00)
                        if (numericGrade < 0.00 || numericGrade > 4.00) {
                            alert('Numeric grade must be between 0.00 and 4.00.');
                            e.preventDefault();
                            gradeInput.focus();
                            return false;
                        }

                        // Format to 2 decimal places
                        gradeInput.value = numericGrade.toFixed(2);
                    });
                }
            });
        });
    </script>
</body>

</html>