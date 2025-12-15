<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Schedule.php';
require_once 'models/Enrollment.php';

// Check if user is logged in and is student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: index.php');
    exit();
}

$schedule_model = new Schedule($pdo);
$enrollment_model = new Enrollment($pdo);

// Get student's schedule
$student_schedule = $schedule_model->getStudentSchedule($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule - <?php echo SITE_NAME; ?></title>
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

        .schedule-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .schedule-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .schedule-card-header {
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

        .day-highlight {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        .time-cell {
            min-width: 120px;
        }

        .room-cell {
            min-width: 100px;
        }

        @media print {
            .schedule-header {
                background: #2c3e50 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .btn {
                display: none;
            }

            body {
                background-color: white;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .table {
                box-shadow: none;
            }

            .table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="schedule-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">My Class Schedule</h1>
                    <p class="mb-0 opacity-75">View your enrolled classes and their schedules</p>
                </div>
                <div class="text-end">
                    <!-- <button class="btn btn-light mb-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Schedule
                    </button> -->
                    <div class="fs-5">Student Portal</div>
                    <div class="small opacity-75">Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></div>
                </div>
            </div>
        </div>

        <div class="card schedule-card">
            <div class="schedule-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Class Schedule</h4>
                    <span class="badge bg-primary"><?php echo count($student_schedule); ?> Classes</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($student_schedule) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Class Code</th>
                                    <th>Subject</th>

                                    <th>Units</th>
                                    <th>Unit Hours</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                    <th>Days</th>
                                    <th>Room</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $prev_day = '';
                                foreach ($student_schedule as $schedule):
                                    $current_day = $schedule['days'];
                                    $day_changed = ($prev_day !== $current_day);
                                    $prev_day = $current_day;
                                ?>
                                    <tr <?php echo $day_changed ? 'class="day-highlight"' : ''; ?>>
                                        <td><?php echo htmlspecialchars($schedule['class_code']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($schedule['subject_code']); ?></strong>
                                            <div class="small text-muted"><?php echo htmlspecialchars($schedule['subject_name']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($schedule['credits']); ?></td>
                                        <td><?php echo htmlspecialchars($schedule['unit_hours']); ?></td>
                                        <td><?php echo !empty($schedule['description']) ? htmlspecialchars($schedule['description']) : '<span class="text-muted">No description</span>'; ?></td>
                                        <td class="time-cell">
                                            <?php
                                            // Format time to match sample data format
                                            $time_from = date('g:ia', strtotime($schedule['time_from']));
                                            $time_to = date('g:ia', strtotime($schedule['time_to']));
                                            // Replace 'am' with 'a' and 'pm' with 'p' to match sample format
                                            $time_from = str_replace(['am', 'pm'], ['a', 'p'], $time_from);
                                            $time_to = str_replace(['am', 'pm'], ['a', 'p'], $time_to);
                                            echo $time_from . '<br>' . $time_to;
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($schedule['days']); ?></td>
                                        <td class="room-cell"><?php echo htmlspecialchars($schedule['room']); ?></td>
                                        <td>
                                            <?php
                                            if (!empty($schedule['instructor_first_name']) && !empty($schedule['instructor_last_name'])) {
                                                echo htmlspecialchars($schedule['instructor_first_name'] . ' ' . $schedule['instructor_last_name']);
                                            } else {
                                                echo '<span class="text-muted">Not assigned</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <h5>No Class Schedule Found</h5>
                        <p class="mb-0">You are not currently enrolled in any subjects with scheduled classes. <a href="enroll.php">Enroll in subjects</a> to see your schedule.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>