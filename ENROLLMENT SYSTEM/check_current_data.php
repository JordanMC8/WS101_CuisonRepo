<?php
require_once 'config/db.php';

echo "=== CURRENT SUBJECTS ===\n";
$stmt = $pdo->query('SELECT * FROM subjects ORDER BY id');
$subjects = $stmt->fetchAll();
foreach ($subjects as $subject) {
    echo "ID: " . $subject['id'] . ", Code: " . $subject['subject_code'] . ", Name: '" . $subject['subject_name'] . "'\n";
}

echo "\n=== CURRENT SCHEDULES ===\n";
$stmt = $pdo->query('SELECT * FROM class_schedules ORDER BY class_code, days, time_from');
$schedules = $stmt->fetchAll();
foreach ($schedules as $schedule) {
    echo "Schedule ID: " . $schedule['id'] . ", Subject ID: " . $schedule['subject_id'] . ", Class Code: " . $schedule['class_code'] . ", ";
    echo "Time: " . $schedule['time_from'] . " - " . $schedule['time_to'] . ", Days: " . $schedule['days'] . ", Room: " . $schedule['room'] . "\n";
}

echo "\n=== STUDENT ENROLLMENTS ===\n";
$stmt = $pdo->query('SELECT * FROM enrollments WHERE student_id = (SELECT id FROM users WHERE username = \"student1\")');
$enrollments = $stmt->fetchAll();
foreach ($enrollments as $enrollment) {
    echo "Enrollment ID: " . $enrollment['id'] . ", Student ID: " . $enrollment['student_id'] . ", Subject ID: " . $enrollment['subject_id'] . "\n";
}
