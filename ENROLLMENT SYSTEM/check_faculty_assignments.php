<?php
require_once 'config/db.php';

$stmt = $pdo->query('SELECT * FROM faculty_assignments');
$assignments = $stmt->fetchAll();

echo "Faculty assignments: " . count($assignments) . "\n";
foreach ($assignments as $assignment) {
    echo "ID: " . $assignment['id'] . ", Faculty: " . $assignment['faculty_id'] . ", Subject: " . $assignment['subject_id'] . "\n";
}
