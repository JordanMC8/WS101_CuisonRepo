<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_model = new User($pdo);

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $role = $_POST['role'] ?? 'student'; // Default role for new registrations

    // Validation
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($first_name) || empty($last_name) || empty($role)) {
        $message = 'All fields are required.';
        $message_type = 'danger';
    } elseif (!in_array($role, ['student', 'faculty'])) {
        $message = 'Invalid role selected.';
        $message_type = 'danger';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
        $message_type = 'danger';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $message_type = 'danger';
    } else {
        // Check if username or email already exists
        $existing_user = $user_model->getUserByUsername($username);
        if ($existing_user) {
            $message = 'Username already exists. Please choose another.';
            $message_type = 'danger';
        } else {
            // Check email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $message = 'Email already registered. Please use another email.';
                $message_type = 'danger';
            } else {
                // Create new user
                if ($user_model->createUser($username, $password, $email, $first_name, $last_name, $role)) {
                    $message = 'Registration successful! You can now login.';
                    $message_type = 'success';

                    // Clear form values
                    $username = $email = $first_name = $last_name = '';
                } else {
                    $message = 'Registration failed. Please try again.';
                    $message_type = 'danger';
                }
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
    <title>Register - <?php echo SITE_NAME; ?></title>
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
            background: linear-gradient(135deg, var(--primary-color), #1a2530);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .register-card {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: none;
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--secondary-color), #1a5ca3);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 25px;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-text);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card register-card">
                    <div class="register-header text-center">
                        <h3 class="mb-2">Create Account</h3>
                        <p class="mb-0 opacity-75">Sign up for <?php echo SITE_NAME; ?></p>
                    </div>
                    <div class="card-body p-4">

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" placeholder="Enter first name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" placeholder="Enter last name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" placeholder="Choose a username" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" placeholder="Enter your email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                                </div>
                                <div class="form-text">Password must be at least 6 characters long.</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="student" selected>Student</option>
                                        <option value="faculty">Faculty</option>
                                    </select>
                                </div>
                                <div class="form-text">Note: Administrator accounts can only be created by existing administrators.</div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
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