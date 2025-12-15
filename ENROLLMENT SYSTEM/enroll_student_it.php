<?php
// Script to enroll student in an IT subject

require_once 'config/db.php';

try {
    // Get student ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['student1']);
    $student = $stmt->fetch();

    if (!$student) {
        echo "Student not found.\n";
        exit;
    }

    $studentId = $student['id'];

    // Get subject ID (using URD_CAPTD1_IT as an example)
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
    $stmt->execute(['URD_CAPTD1_IT']);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Subject not found.\n";
        exit;
    }

    $subjectId = $subject['id'];

    // Check if student is already enrolled
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE student_id = ? AND subject_id = ?");
    $stmt->execute([$studentId, $subjectId]);
    $existing = $stmt->fetch();

    if (!$existing) {
        // Enroll student in subject
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
        $stmt->execute([$studentId, $subjectId]);
        echo "Student enrolled in URD_CAPTD1_IT successfully.\n";
    } else {
        echo "Student already enrolled in URD_CAPTD1_IT.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
