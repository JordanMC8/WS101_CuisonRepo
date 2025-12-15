<?php
// Site configuration
define('SITE_NAME', 'Enrollment System');
define('SITE_URL', 'http://localhost/enrollment-system');

// File upload settings
define('UPLOAD_PATH', 'assets/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour

// Security settings
define('CSRF_TOKEN_NAME', 'csrf_token');
