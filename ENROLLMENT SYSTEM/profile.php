<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/User.php';
require_once 'includes/file_upload.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_model = new User($pdo);
$user = $user_model->getUserById($user_id);

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile information
        $email = trim($_POST['email']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);

        if (!empty($email) && !empty($first_name) && !empty($last_name)) {
            if ($user_model->updateUser($user_id, $email, $first_name, $last_name)) {
                // Update session variables
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                $message = 'Profile updated successfully.';
                $message_type = 'success';

                // Refresh user data
                $user = $user_model->getUserById($user_id);
            } else {
                $message = 'Failed to update profile.';
                $message_type = 'danger';
            }
        } else {
            $message = 'All fields are required.';
            $message_type = 'danger';
        }
    } elseif (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
        // Upload profile picture
        $uploadResult = uploadFile(
            $_FILES['profile_picture'],
            UPLOAD_PATH . 'profiles/',
            ALLOWED_IMAGE_TYPES,
            MAX_FILE_SIZE
        );

        if ($uploadResult['success']) {
            if ($user_model->updateProfilePicture($user_id, $uploadResult['filePath'])) {
                // Update session variable
                $_SESSION['profile_picture'] = $uploadResult['filePath'];

                $message = 'Profile picture uploaded successfully.';
                $message_type = 'success';

                // Refresh user data
                $user = $user_model->getUserById($user_id);
            } else {
                $message = 'Failed to save profile picture to database.';
                $message_type = 'danger';
            }
        } else {
            $message = $uploadResult['message'];
            $message_type = 'danger';
        }
    } elseif (isset($_FILES['signature']) && !empty($_FILES['signature']['name'])) {
        // Upload signature
        $uploadResult = uploadFile(
            $_FILES['signature'],
            UPLOAD_PATH . 'signatures/',
            ALLOWED_IMAGE_TYPES,
            MAX_FILE_SIZE
        );

        if ($uploadResult['success']) {
            if ($user_model->updateSignature($user_id, $uploadResult['filePath'])) {
                // Update session variable
                $_SESSION['signature'] = $uploadResult['filePath'];

                $message = 'Signature uploaded successfully.';
                $message_type = 'success';

                // Refresh user data
                $user = $user_model->getUserById($user_id);
            } else {
                $message = 'Failed to save signature to database.';
                $message_type = 'danger';
            }
        } else {
            $message = $uploadResult['message'];
            $message_type = 'danger';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo SITE_NAME; ?></title>
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
        <div class="profile-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Profile</h1>
                    <p class="mb-0 opacity-75">Manage your personal information and documents</p>
                </div>
                <div class="text-end">
                    <div class="fs-5"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                    <div class="small opacity-75">Role: <?php echo ucfirst(htmlspecialchars($user['role'])); ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card profile-card">
                    <div class="profile-card-header">
                        <h4 class="mb-0">Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" disabled>
                            </div>

                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card profile-card">
                            <div class="profile-card-header">
                                <h4 class="mb-0">Profile Picture</h4>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($user['profile_picture'])): ?>
                                    <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="img-fluid profile-img mb-3">
                                <?php else: ?>
                                    <div class="py-4">
                                        <i class="fas fa-user-circle fa-4x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No profile picture uploaded yet</p>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                        <div class="form-text">Allowed formats: JPG, JPEG, PNG. Max size: 2MB</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>Upload Picture
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card profile-card">
                            <div class="profile-card-header">
                                <h4 class="mb-0">Signature</h4>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($user['signature'])): ?>
                                    <img src="<?php echo $user['signature']; ?>" alt="Signature" class="img-fluid signature-img mb-3">
                                <?php else: ?>
                                    <div class="py-4">
                                        <i class="fas fa-file-signature fa-4x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No signature uploaded yet</p>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="signature" class="form-label">Upload New Signature</label>
                                        <input type="file" class="form-control" id="signature" name="signature" accept="image/*">
                                        <div class="form-text">Allowed formats: JPG, JPEG, PNG. Max size: 2MB</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-2"></i>Upload Signature
                                    </button>
                                </form>
                            </div>
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