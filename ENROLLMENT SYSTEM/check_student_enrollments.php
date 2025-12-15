<?php
require_once 'config/db.php';

// Get the test student ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute(['student1']);
$student = $stmt->fetch();

if (!$student) {
    echo "Test student not found.\n";
    exit(1);
}

$student_id = $student['id'];

// Get student's enrollments with subject details
$stmt = $pdo->prepare('
    SELECT e.id, e.status, s.subject_code, s.subject_name
    FROM enrollments e
    JOIN subjects s ON e.subject_id = s.id
    WHERE e.student_id = ?
    ORDER BY s.subject_code
');
$stmt->execute([$student_id]);
$enrollments = $stmt->fetchAll();

echo "Student enrollments:\n";
foreach ($enrollments as $enrollment) {
    echo "Enrollment ID: " . $enrollment['id'] . "\n";
    echo "  Status: " . $enrollment['status'] . "\n";
    echo "  Subject: " . $enrollment['subject_code'] . " - " . $enrollment['subject_name'] . "\n";
    echo "\n";
}
