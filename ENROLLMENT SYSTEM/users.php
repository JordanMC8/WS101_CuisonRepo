<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/User.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$user_model = new User($pdo);

$message = '';
$message_type = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        // Add new user
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $email = trim($_POST['email']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $role = $_POST['role'];

        if (!empty($username) && !empty($password) && !empty($email) && !empty($first_name) && !empty($last_name) && !empty($role)) {
            // Check if username or email already exists
            $existing_user = $user_model->getUserByUsername($username);
            if (!$existing_user) {
                if ($user_model->createUser($username, $password, $email, $first_name, $last_name, $role)) {
                    $message = 'User added successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Failed to add user.';
                    $message_type = 'danger';
                }
            } else {
                $message = 'Username already exists.';
                $message_type = 'danger';
            }
        } else {
            $message = 'Please fill in all required fields.';
            $message_type = 'danger';
        }
    } elseif (isset($_POST['delete_user'])) {
        // Delete user
        $id = intval($_POST['user_id']);

        // Prevent deleting oneself
        if ($id == $_SESSION['user_id']) {
            $message = 'You cannot delete yourself.';
            $message_type = 'danger';
        } else {
            if ($user_model->deleteUser($id)) {
                $message = 'User deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to delete user.';
                $message_type = 'danger';
            }
        }
    }
}

// Get all users
$users = $user_model->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - <?php echo SITE_NAME; ?></title>
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

        .users-header {
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .users-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .users-card-header {
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
        <div class="users-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">User Management</h1>
                    <p class="mb-0 opacity-75">Create, edit, and manage system users and their roles</p>
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

        <!-- Add User Form -->
        <div class="card users-card">
            <div class="users-card-header">
                <h4 class="mb-0">Add New User</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </button>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="card users-card">
            <div class="users-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">All Users</h4>
                    <span class="badge bg-primary"><?php echo count($users); ?> Users</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php if ($user['role'] == 'admin'): ?>
                                                <span class="badge bg-danger">Administrator</span>
                                            <?php elseif ($user['role'] == 'faculty'): ?>
                                                <span class="badge bg-info">Faculty</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Student</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo addslashes($user['username']); ?>')">
                                                    <i class="fas fa-trash-alt me-1"></i>Delete
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <h5>No Users Found</h5>
                        <p class="mb-0">No users have been created yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <p>Are you sure you want to delete the user "<span id="delete_user_name"></span>"?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_user" class="btn btn-danger">Delete User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function deleteUser(id, name) {
            document.getElementById('delete_user_id').value = id;
            document.getElementById('delete_user_name').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }
    </script>
</body>

</html>