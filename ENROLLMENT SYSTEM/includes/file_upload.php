<?php
function uploadFile($file, $uploadDir, $allowedTypes = [], $maxSize = 0)
{
    // Check if file was uploaded without errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error.'];
    }

    // Check file size
    if ($maxSize > 0 && $file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File size exceeds maximum allowed size.'];
    }

    // Get file info
    $fileName = basename($file['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type
    if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type.'];
    }

    // Generate unique filename
    $newFileName = uniqid() . '_' . $fileName;
    $targetFilePath = $uploadDir . $newFileName;

    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Upload file
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return ['success' => true, 'filePath' => $targetFilePath];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}
