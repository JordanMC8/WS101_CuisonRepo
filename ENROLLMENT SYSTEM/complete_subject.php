<?php
// Script to simulate completing a subject for testing purposes

require_once 'config/db.php';

try {
    // Simulate completing MATH101 for student1
    // First, get the student ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['student1']);
    $student = $stmt->fetch();

    if (!$student) {
        echo "Student not found.\n";
        exit;
    }

    $studentId = $student['id'];

    // Get the subject ID for MATH101
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
    $stmt->execute(['MATH101']);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Subject MATH101 not found.\n";
        exit;
    }

    $subjectId = $subject['id'];

    // Check if student is enrolled in MATH101
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE student_id = ? AND subject_id = ?");
    $stmt->execute([$studentId, $subjectId]);
    $enrollment = $stmt->fetch();

    if (!$enrollment) {
        // Enroll student in MATH101 first
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
        $stmt->execute([$studentId, $subjectId]);
        $enrollmentId = $pdo->lastInsertId();
        echo "Student enrolled in MATH101.\n";
    } else {
        $enrollmentId = $enrollment['id'];
    }

    // Mark the subject as completed with a grade
    $stmt = $pdo->prepare("UPDATE enrollments SET grade = 'A', status = 'completed' WHERE id = ?");
    $stmt->execute([$enrollmentId]);

    echo "Subject MATH101 marked as completed for student1 with grade A.\n";
    echo "Student can now enroll in subjects that require MATH101 as a prerequisite.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
