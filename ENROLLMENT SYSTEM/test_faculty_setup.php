<?php
// Test script to set up faculty assignments for testing purposes

require_once 'config/db.php';

try {
    // Create a test faculty user if not exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['faculty1']);
    $faculty = $stmt->fetch();

    if (!$faculty) {
        // Create faculty user
        $hashedPassword = password_hash('faculty123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['faculty1', $hashedPassword, 'faculty1@enrollmentsystem.com', 'John', 'Professor', 'faculty']);
        $facultyId = $pdo->lastInsertId();
        echo "Created faculty user with ID: $facultyId\n";
    } else {
        $facultyId = $faculty['id'];
        echo "Found faculty user with ID: $facultyId\n";
    }

    // Get a sample subject
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
    $stmt->execute(['MATH101']);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Sample subject MATH101 not found. Please run populate_sample_data.php first.\n";
        exit;
    }

    $subjectId = $subject['id'];

    // Assign faculty to subject
    $stmt = $pdo->prepare("SELECT * FROM faculty_assignments WHERE faculty_id = ? AND subject_id = ?");
    $stmt->execute([$facultyId, $subjectId]);
    $assignment = $stmt->fetch();

    if (!$assignment) {
        $stmt = $pdo->prepare("INSERT INTO faculty_assignments (faculty_id, subject_id) VALUES (?, ?)");
        $stmt->execute([$facultyId, $subjectId]);
        echo "Assigned faculty to MATH101 subject.\n";
    } else {
        echo "Faculty already assigned to MATH101 subject.\n";
    }

    echo "Setup complete! You can now log in as faculty1 with password 'faculty123' and test the faculty dashboard.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
