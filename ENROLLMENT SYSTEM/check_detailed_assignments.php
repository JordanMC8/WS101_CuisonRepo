<?php
require_once 'config/db.php';

$stmt = $pdo->query('
    SELECT fa.id, u.username as faculty_username, u.first_name as faculty_first, u.last_name as faculty_last, 
           s.subject_code, s.subject_name
    FROM faculty_assignments fa
    JOIN users u ON fa.faculty_id = u.id
    JOIN subjects s ON fa.subject_id = s.id
    ORDER BY fa.id
');
$assignments = $stmt->fetchAll();

echo "Detailed faculty assignments:\n";
foreach ($assignments as $assignment) {
    echo "Assignment ID: " . $assignment['id'] . "\n";
    echo "  Faculty: " . $assignment['faculty_first'] . " " . $assignment['faculty_last'] . " (" . $assignment['faculty_username'] . ")\n";
    echo "  Subject: " . $assignment['subject_code'] . " - " . $assignment['subject_name'] . "\n";
    echo "\n";
}
