<?php
// Script to display all subjects and their prerequisites

require_once 'config/db.php';

try {
    // Get all subjects
    $stmt = $pdo->query("SELECT * FROM subjects ORDER BY subject_code");
    $subjects = $stmt->fetchAll();

    echo "=== ALL SUBJECTS ===\n";
    foreach ($subjects as $subject) {
        echo "{$subject['subject_code']} - {$subject['subject_name']} ({$subject['credits']} units)\n";

        // Get prerequisites for this subject
        $stmt = $pdo->prepare("
            SELECT s.subject_code, s.subject_name
            FROM subjects s 
            JOIN prerequisites p ON s.id = p.prerequisite_subject_id 
            WHERE p.subject_id = ?
        ");
        $stmt->execute([$subject['id']]);
        $prerequisites = $stmt->fetchAll();

        if (count($prerequisites) > 0) {
            echo "  Prerequisites:\n";
            foreach ($prerequisites as $prereq) {
                echo "    - {$prereq['subject_code']} - {$prereq['subject_name']}\n";
            }
        } else {
            echo "  No prerequisites\n";
        }
        echo "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
