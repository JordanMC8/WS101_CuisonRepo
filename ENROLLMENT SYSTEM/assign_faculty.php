<?php
// Script to assign faculty to teach a subject

require_once 'config/db.php';

try {
    // Get faculty ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(['faculty1']);
    $faculty = $stmt->fetch();

    if (!$faculty) {
        echo "Faculty member not found.\n";
        exit;
    }

    $facultyId = $faculty['id'];

    // Get subject ID (using MATH101 as an example)
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
    $stmt->execute(['MATH101']);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Subject not found.\n";
        exit;
    }

    $subjectId = $subject['id'];

    // Assign faculty to subject
    $stmt = $pdo->prepare("INSERT INTO faculty_assignments (faculty_id, subject_id) VALUES (?, ?)");
    $stmt->execute([$facultyId, $subjectId]);

    echo "Faculty member assigned to teach MATH101 successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
