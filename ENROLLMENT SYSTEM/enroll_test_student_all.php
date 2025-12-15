<?php
require_once 'config/db.php';

// Get the student ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute(['student1']);
$student = $stmt->fetch();

if (!$student) {
    echo "Test student not found.\n";
    exit(1);
}

$student_id = $student['id'];

// Enroll the student in all subjects (1-8)
$subjects_to_enroll = range(1, 8);

foreach ($subjects_to_enroll as $subject_id) {
    // Check if already enrolled
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE student_id = ? AND subject_id = ? AND status != 'dropped'");
    $stmt->execute([$student_id, $subject_id]);

    if (!$stmt->fetch()) {
        // Enroll student
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
        if ($stmt->execute([$student_id, $subject_id])) {
            echo "Enrolled student in subject ID $subject_id\n";
        } else {
            echo "Failed to enroll student in subject ID $subject_id\n";
        }
    } else {
        echo "Student already enrolled in subject ID $subject_id\n";
    }
}

echo "Test student enrollment in all subjects complete.\n";
