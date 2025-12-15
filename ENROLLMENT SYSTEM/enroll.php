<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Subject.php';
require_once 'models/Enrollment.php';
require_once 'models/Schedule.php';

// Check if user is logged in and is student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: index.php');
    exit();
}

$subject_model = new Subject($pdo);
$enrollment_model = new Enrollment($pdo);
$schedule_model = new Schedule($pdo);

$message = '';
$message_type = '';

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
    $subject_id = intval($_POST['subject_id']);

    // Check if already enrolled
    $existing_enrollment = $pdo->prepare("SELECT * FROM enrollments WHERE student_id = ? AND subject_id = ? AND status != 'dropped'");
    $existing_enrollment->execute([$_SESSION['user_id'], $subject_id]);

    if ($existing_enrollment->fetch()) {
        $message = 'You are already enrolled in this subject.';
        $message_type = 'warning';
    } else {
        // Check prerequisites
        $prerequisites = $subject_model->getPrerequisites($subject_id);
        $can_enroll = true;
        $missing_prereq = '';

        foreach ($prerequisites as $prerequisite) {
            if (!$enrollment_model->hasCompletedPrerequisite($_SESSION['user_id'], $prerequisite['id'])) {
                $can_enroll = false;
                $missing_prereq = $prerequisite['subject_code'] . ' - ' . $prerequisite['subject_name'];
                break;
            }
        }

        if ($can_enroll) {
            if ($enrollment_model->enrollStudent($_SESSION['user_id'], $subject_id)) {
                $message = 'Successfully enrolled in the subject. <a href="schedule.php" class="alert-link">View your class schedule</a>';
                $message_type = 'success';
            } else {
                $message = 'Failed to enroll in the subject.';
                $message_type = 'danger';
            }
        } else {
            $message = 'You have not completed the prerequisite: ' . $missing_prereq;
            $message_type = 'danger';
        }
    }
}

// Get all subjects
$subjects = $subject_model->getAllSubjects();

// Get student's current enrollments
$current_enrollments = $enrollment_model->getStudentEnrollments($_SESSION['user_id']);
$enrolled_subject_ids = [];
foreach ($current_enrollments as $enrollment) {
    $enrolled_subject_ids[] = $enrollment['subject_id'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll - <?php echo SITE_NAME; ?></title>
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

        .enroll-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .enroll-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .enroll-card-header {
            border-radius: 8px 8px 0 0 !important;
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f7;
            padding: 15px 20px;
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

        .prerequisite-completed {
            color: #28a745;
            font-weight: 600;
        }

        .prerequisite-pending {
            color: #dc3545;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="enroll-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Enroll in Subjects</h1>
                    <p class="mb-0 opacity-75">Select subjects to enroll in for the current semester</p>
                </div>
                <div class="text-end">
                    <div class="fs-5">Student Portal</div>
                    <div class="small opacity-75">Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>Enrollment Information</h5>
            <p>You can enroll in subjects that meet the following criteria:</p>
            <ul>
                <li>You have not already enrolled in the subject</li>
                <li>You have completed all prerequisite subjects (marked in green)</li>
            </ul>
            <p>If a prerequisite is marked in red, you must complete that subject first before enrolling.</p>
        </div>

        <!-- Current Enrollments -->
        <div class="card enroll-card">
            <div class="enroll-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Current Enrollments</h4>
                    <span class="badge bg-primary"><?php echo count($current_enrollments); ?> Subjects</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($current_enrollments) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Credits</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($current_enrollments as $enrollment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($enrollment['subject_code']); ?></td>
                                        <td><?php echo htmlspecialchars($enrollment['subject_name']); ?></td>
                                        <td><?php echo htmlspecialchars($enrollment['credits']); ?></td>
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
                                                <strong><?php echo htmlspecialchars($enrollment['grade']); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <h5>No Current Enrollments</h5>
                        <p class="mb-0">You are not currently enrolled in any subjects.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Available Subjects -->
        <div class="card enroll-card">
            <div class="enroll-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Available Subjects</h4>
                    <span class="badge bg-primary"><?php echo count($subjects); ?> Subjects</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($subjects) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Credits</th>
                                    <th>Description</th>
                                    <th>Prerequisites</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['credits']); ?></td>
                                        <td><?php echo htmlspecialchars($subject['description']); ?></td>
                                        <td>
                                            <?php
                                            $prerequisites = $subject_model->getPrerequisites($subject['id']);
                                            if (count($prerequisites) > 0) {
                                                foreach ($prerequisites as $prereq) {
                                                    // Check if student has completed this prerequisite
                                                    $completed = $enrollment_model->hasCompletedPrerequisite($_SESSION['user_id'], $prereq['id']);
                                                    $status_class = $completed ? 'prerequisite-completed' : 'prerequisite-pending';
                                                    $status_text = $completed ? ' (Completed)' : ' (Not Completed)';
                                                    echo '<div class="' . $status_class . '">' . htmlspecialchars($prereq['subject_code']) . ' - ' . htmlspecialchars($prereq['subject_name']) . $status_text . '</div>';
                                                }
                                            } else {
                                                echo '<span class="text-muted">None</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (in_array($subject['id'], $enrolled_subject_ids)): ?>
                                                <span class="badge bg-success">Enrolled</span>
                                            <?php else: ?>
                                                <?php
                                                // Check if all prerequisites are met
                                                $prerequisites = $subject_model->getPrerequisites($subject['id']);
                                                $canEnroll = true;
                                                foreach ($prerequisites as $prereq) {
                                                    if (!$enrollment_model->hasCompletedPrerequisite($_SESSION['user_id'], $prereq['id'])) {
                                                        $canEnroll = false;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <?php if ($canEnroll || count($prerequisites) == 0): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                                        <button type="submit" name="enroll" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-user-plus me-1"></i>Enroll
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-exclamation-circle me-1"></i>Prerequisites Not Met
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <h5>No Subjects Available</h5>
                        <p class="mb-0">No subjects are currently available for enrollment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>