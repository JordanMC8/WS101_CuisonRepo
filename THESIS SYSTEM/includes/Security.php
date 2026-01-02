<?php
class Security {
    
    /**
     * Sanitize user input
     */
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email format
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Validate file upload
     */
    public static function validateFileUpload($file, $allowedTypes = [], $maxSize = 10485760) { // 10MB default
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'File upload error occurred.'];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'File size exceeds maximum allowed size.'];
        }
        
        // Check file type if specified
        if (!empty($allowedTypes)) {
            $fileType = mime_content_type($file['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                return ['valid' => false, 'error' => 'Invalid file type.'];
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCSRFToken($token) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate secure password hash
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password against hash
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Check if user is authorized to access a resource
     */
    public static function isAuthorized($userId, $resourceOwnerId) {
        // For now, simple check - in a real app, you might have more complex authorization logic
        return $userId == $resourceOwnerId;
    }
    
    /**
     * Prevent XSS attacks by sanitizing output
     */
    public static function escapeOutput($output) {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Rate limiting for login attempts
     */
    public static function checkRateLimit($identifier, $maxAttempts = 5, $window = 900) { // 15 minutes
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $key = 'rate_limit_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'timestamp' => time()];
        }
        
        $data = $_SESSION[$key];
        
        // Reset counter if window has passed
        if (time() - $data['timestamp'] > $window) {
            $_SESSION[$key] = ['attempts' => 0, 'timestamp' => time()];
            return true;
        }
        
        // Check if limit exceeded
        if ($data['attempts'] >= $maxAttempts) {
            return false;
        }
        
        // Increment attempts
        $_SESSION[$key]['attempts']++;
        return true;
    }
    
    /**
     * Reset rate limit counter
     */
    public static function resetRateLimit($identifier) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $key = 'rate_limit_' . md5($identifier);
        unset($_SESSION[$key]);
    }
}
?>