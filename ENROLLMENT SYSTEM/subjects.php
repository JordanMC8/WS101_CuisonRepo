<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Subject.php';
require_once 'models/Schedule.php';
require_once 'models/Faculty.php';
require_once 'models/User.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$subject_model = new Subject($pdo);
$schedule_model = new Schedule($pdo);
$faculty_model = new Faculty($pdo);
$user_model = new User($pdo);

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_subject'])) {
        // Add new subject
        $subject_code = trim($_POST['subject_code']);
        $subject_name = trim($_POST['subject_name']);
        $credits = intval($_POST['credits']);
        $description = trim($_POST['description']);

        if (!empty($subject_code) && !empty($subject_name) && $credits > 0) {
            if ($subject_model->createSubject($subject_code, $subject_name, $credits, $description)) {
                $message = 'Subject added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add subject.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Please fill in all required fields.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['edit_subject'])) {
        // Edit subject
        $id = intval($_POST['subject_id']);
        $subject_code = trim($_POST['subject_code']);
        $subject_name = trim($_POST['subject_name']);
        $credits = intval($_POST['credits']);
        $description = trim($_POST['description']);

        if (!empty($subject_code) && !empty($subject_name) && $credits > 0) {
            if ($subject_model->updateSubject($id, $subject_code, $subject_name, $credits, $description)) {
                $message = 'Subject updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to update subject.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Please fill in all required fields.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['delete_subject'])) {
        // Delete subject
        $id = intval($_POST['subject_id']);

        if ($subject_model->deleteSubject($id)) {
            $message = 'Subject deleted successfully.';
            $message_type = 'success';
        } else {
            $message = 'Failed to delete subject.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['add_prerequisite'])) {
        // Add prerequisite
        $subject_id = intval($_POST['subject_id']);
        $prerequisite_id = intval($_POST['prerequisite_id']);

        if ($subject_id != $prerequisite_id) {
            if ($subject_model->addPrerequisite($subject_id, $prerequisite_id)) {
                $message = 'Prerequisite added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add prerequisite.';
                $message_type = 'danger';
            }
        } else {
            $message = 'A subject cannot be a prerequisite for itself.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['remove_prerequisite'])) {
        // Remove prerequisite
        $subject_id = intval($_POST['subject_id']);
        $prerequisite_id = intval($_POST['prerequisite_id']);

        if ($subject_model->removePrerequisite($subject_id, $prerequisite_id)) {
            $message = 'Prerequisite removed successfully.';
            $message_type = 'success';
        } else {
            $message = 'Failed to remove prerequisite.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['add_schedule'])) {
        // Add schedule
        $subject_id = intval($_POST['subject_id']);
        $class_code = trim($_POST['class_code']);
        $unit_hours = trim($_POST['unit_hours']);
        $time_from = trim($_POST['time_from']);
        $time_to = trim($_POST['time_to']);
        $days = trim($_POST['days']);
        $room = trim($_POST['room']);

        if (!empty($class_code) && !empty($unit_hours) && !empty($time_from) && !empty($time_to) && !empty($days) && !empty($room)) {
            if ($schedule_model->addSchedule($subject_id, $class_code, $unit_hours, $time_from, $time_to, $days, $room)) {
                $message = 'Schedule added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add schedule.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Please fill in all schedule fields.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['edit_schedule'])) {
        // Edit schedule
        $schedule_id = intval($_POST['schedule_id']);
        $class_code = trim($_POST['class_code']);
        $unit_hours = trim($_POST['unit_hours']);
        $time_from = trim($_POST['time_from']);
        $time_to = trim($_POST['time_to']);
        $days = trim($_POST['days']);
        $room = trim($_POST['room']);

        if (!empty($class_code) && !empty($unit_hours) && !empty($time_from) && !empty($time_to) && !empty($days) && !empty($room)) {
            if ($schedule_model->updateSchedule($schedule_id, $class_code, $unit_hours, $time_from, $time_to, $days, $room)) {
                $message = 'Schedule updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to update schedule.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Please fill in all schedule fields.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['delete_schedule'])) {
        // Delete schedule
        $schedule_id = intval($_POST['schedule_id']);

        if ($schedule_model->deleteSchedule($schedule_id)) {
            $message = 'Schedule deleted successfully.';
            $message_type = 'success';
        } else {
            $message = 'Failed to delete schedule.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['assign_faculty'])) {
        // Assign faculty to subject
        $subject_id = intval($_POST['subject_id']);
        $faculty_id = intval($_POST['faculty_id']);

        if (!empty($subject_id) && !empty($faculty_id)) {
            // Check if assignment already exists
            $stmt = $pdo->prepare("SELECT id FROM faculty_assignments WHERE faculty_id = ? AND subject_id = ?");
            $stmt->execute([$faculty_id, $subject_id]);
            $existing = $stmt->fetch();

            if (!$existing) {
                if ($faculty_model->assignToSubject($faculty_id, $subject_id)) {
                    $message = 'Faculty assigned to subject successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Failed to assign faculty to subject.';
                    $message_type = 'danger';
                }
            } else {
                $message = 'Faculty is already assigned to this subject.';
                $message_type = 'warning';
            }
        } else {
            $message = 'Please select both a subject and a faculty member.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['remove_faculty'])) {
        // Remove faculty from subject
        $subject_id = intval($_POST['subject_id']);
        $faculty_id = intval($_POST['faculty_id']);

        if (!empty($subject_id) && !empty($faculty_id)) {
            if ($faculty_model->removeFromSubject($faculty_id, $subject_id)) {
                $message = 'Faculty removed from subject successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to remove faculty from subject.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Invalid request.';
            $message_type = 'danger';
        }
    }
}

// Get all subjects
$subjects = $subject_model->getAllSubjects();

// Get all faculty members
$faculty_members = $user_model->getUsersByRole('faculty');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects - <?php echo SITE_NAME; ?></title>
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

        .subjects-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .subjects-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .subjects-card-header {
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
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="subjects-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Subject Management</h1>
                    <p class="mb-0 opacity-75">Create, edit, and manage academic subjects and prerequisites</p>
                </div>
                <div class="text-end">
                    <div class="fs-5">Admin Portal</div>
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

        <!-- Add Subject Form -->
        <div class="card subjects-card">
            <div class="subjects-card-header">
                <h4 class="mb-0">Add New Subject</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="subject_code" class="form-label">Subject Code *</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="subject_name" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="credits" class="form-label">Credits *</label>
                            <input type="number" class="form-control" id="credits" name="credits" min="1" required>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" name="add_subject" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Add Subject
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Subjects List -->
        <div class="card subjects-card">
            <div class="subjects-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">All Subjects</h4>
                    <span class="badge bg-primary"><?php echo count($subjects); ?> Subjects</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($subjects) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Description</th>
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
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="editSubject(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_code']); ?>', '<?php echo addslashes($subject['subject_name']); ?>', <?php echo $subject['credits']; ?>, '<?php echo addslashes($subject['description']); ?>')">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="deleteSubject(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_name']); ?>')">
                                                <i class="fas fa-trash-alt me-1"></i>Delete
                                            </button>
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="managePrerequisites(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_name']); ?>')">
                                                <i class="fas fa-link me-1"></i>Prerequisites
                                            </button>
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="manageSchedule(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_name']); ?>')">
                                                <i class="fas fa-calendar me-1"></i>Schedule
                                            </button>
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="manageFacultyAssignment(<?php echo $subject['id']; ?>, '<?php echo addslashes($subject['subject_name']); ?>')">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>Faculty
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <h5>No Subjects Found</h5>
                        <p class="mb-0">No subjects have been created yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Subject Modal -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_subject_id" name="subject_id">
                        <div class="mb-3">
                            <label for="edit_subject_code" class="form-label">Subject Code *</label>
                            <input type="text" class="form-control" id="edit_subject_code" name="subject_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_subject_name" class="form-label">Subject Name *</label>
                            <input type="text" class="form-control" id="edit_subject_name" name="subject_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_credits" class="form-label">Credits *</label>
                            <input type="number" class="form-control" id="edit_credits" name="credits" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_subject" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Subject Modal -->
    <div class="modal fade" id="deleteSubjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete_subject_id" name="subject_id">
                        <p>Are you sure you want to delete the subject "<span id="delete_subject_name"></span>"?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_subject" class="btn btn-danger">Delete Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Schedule for <span id="schedule_subject_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="schedule_subject_id">

                    <!-- Add Schedule Form -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Add Class Schedule</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="subject_id" id="add_schedule_subject_id">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="class_code" class="form-label">Class Code *</label>
                                        <input type="text" class="form-control" id="class_code" name="class_code" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="unit_hours" class="form-label">Unit Hours *</label>
                                        <input type="text" class="form-control" id="unit_hours" name="unit_hours" placeholder="e.g., 2.00/1.00" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="time_from" class="form-label">Time From *</label>
                                        <input type="time" class="form-control" id="time_from" name="time_from" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="time_to" class="form-label">Time To *</label>
                                        <input type="time" class="form-control" id="time_to" name="time_to" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="days" class="form-label">Days *</label>
                                        <input type="text" class="form-control" id="days" name="days" placeholder="e.g., MWF or TTH" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="room" class="form-label">Room *</label>
                                        <input type="text" class="form-control" id="room" name="room" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="add_schedule" class="btn btn-primary">Add Schedule</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Schedules -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Current Schedules</h6>
                        </div>
                        <div class="card-body">
                            <div id="current_schedules">
                                <!-- Schedules will be loaded here -->
                                <p>Select a subject to view its schedules.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Faculty Assignment Modal -->
    <div class="modal fade" id="facultyAssignmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Faculty to <span id="faculty_subject_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="faculty_subject_id">

                    <!-- Assign Faculty Form -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Assign Faculty Member</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row">
                                <input type="hidden" name="subject_id" id="assign_subject_id">
                                <div class="col-md-8 mb-3">
                                    <select class="form-select" name="faculty_id" required>
                                        <option value="">Select a faculty member</option>
                                        <?php foreach ($faculty_members as $faculty): ?>
                                            <option value="<?php echo $faculty['id']; ?>">
                                                <?php echo htmlspecialchars($faculty['first_name'] . ' ' . $faculty['last_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button type="submit" name="assign_faculty" class="btn btn-primary">Assign Faculty</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Faculty Assignments -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Current Faculty Assignments</h6>
                        </div>
                        <div class="card-body">
                            <div id="current_faculty_assignments">
                                <!-- Faculty assignments will be loaded here -->
                                <p>Select a subject to view current faculty assignments.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Prerequisites Modal -->
    <div class="modal fade" id="prerequisitesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Prerequisites for <span id="prereq_subject_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="prereq_subject_id">

                    <!-- Add Prerequisite Form -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Add Prerequisite</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="row">
                                <input type="hidden" id="add_prereq_subject_id" name="subject_id">
                                <div class="col-md-8 mb-3">
                                    <select class="form-select" name="prerequisite_id" required>
                                        <option value="">Select a prerequisite subject</option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?php echo $subject['id']; ?>">
                                                <?php echo htmlspecialchars($subject['subject_code'] . ' - ' . $subject['subject_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <button type="submit" name="add_prerequisite" class="btn btn-primary">Add Prerequisite</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Prerequisites -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Current Prerequisites</h6>
                        </div>
                        <div class="card-body">
                            <div id="current_prerequisites">
                                <!-- Prerequisites will be loaded here via AJAX -->
                                <p>Select a subject to view its prerequisites.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function editSubject(id, code, name, credits, description) {
            document.getElementById('edit_subject_id').value = id;
            document.getElementById('edit_subject_code').value = code;
            document.getElementById('edit_subject_name').value = name;
            document.getElementById('edit_credits').value = credits;
            document.getElementById('edit_description').value = description;
            new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
        }

        function deleteSubject(id, name) {
            document.getElementById('delete_subject_id').value = id;
            document.getElementById('delete_subject_name').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteSubjectModal')).show();
        }

        function managePrerequisites(subjectId, subjectName) {
            document.getElementById('prereq_subject_id').value = subjectId;
            document.getElementById('add_prereq_subject_id').value = subjectId;
            document.getElementById('prereq_subject_name').textContent = subjectName;

            // Load current prerequisites
            loadPrerequisites(subjectId);

            new bootstrap.Modal(document.getElementById('prerequisitesModal')).show();
        }

        function loadPrerequisites(subjectId) {
            // In a real implementation, this would fetch prerequisites via AJAX
            document.getElementById('current_prerequisites').innerHTML = '<p>Prerequisites loading functionality would be implemented here.</p>';
        }

        function removePrerequisite(subjectId, prereqId) {
            if (confirm('Are you sure you want to remove this prerequisite?')) {
                // In a real implementation, this would remove the prerequisite via AJAX
                alert('Prerequisite removal functionality would be implemented here.');
            }
        }

        function manageSchedule(subjectId, subjectName) {
            document.getElementById('schedule_subject_id').value = subjectId;
            document.getElementById('add_schedule_subject_id').value = subjectId;
            document.getElementById('schedule_subject_name').textContent = subjectName;

            // Load current schedules
            loadSchedules(subjectId);

            new bootstrap.Modal(document.getElementById('scheduleModal')).show();
        }

        function loadSchedules(subjectId) {
            // In a real implementation, this would fetch schedules via AJAX
            document.getElementById('current_schedules').innerHTML = '<p>Schedule loading functionality would be implemented here.</p>';
        }

        function manageFacultyAssignment(subjectId, subjectName) {
            document.getElementById('faculty_subject_id').value = subjectId;
            document.getElementById('assign_subject_id').value = subjectId;
            document.getElementById('faculty_subject_name').textContent = subjectName;

            // Load current faculty assignments
            loadFacultyAssignments(subjectId);

            new bootstrap.Modal(document.getElementById('facultyAssignmentModal')).show();
        }

        function loadFacultyAssignments(subjectId) {
            // In a real implementation, this would fetch faculty assignments via AJAX
            document.getElementById('current_faculty_assignments').innerHTML = '<p>Faculty assignment loading functionality would be implemented here.</p>';
        }
    </script>
</body>

</html>