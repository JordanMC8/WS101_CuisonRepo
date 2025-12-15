<?php
// Script to populate the database with sample subjects and prerequisites for testing

require_once 'config/db.php';

try {
    // Insert sample subjects
    $subjects = [
        ['MATH101', 'Introduction to Mathematics', 3, 'Basic mathematics concepts'],
        ['MATH102', 'Advanced Mathematics', 3, 'Advanced mathematics concepts'],
        ['PHYS101', 'Introduction to Physics', 4, 'Basic physics concepts'],
        ['PHYS102', 'Advanced Physics', 4, 'Advanced physics concepts'],
        ['CHEM101', 'Introduction to Chemistry', 3, 'Basic chemistry concepts'],
        ['ENG101', 'English Composition', 3, 'Writing and composition skills']
    ];

    foreach ($subjects as $subject) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO subjects (subject_code, subject_name, credits, description) VALUES (?, ?, ?, ?)");
        $stmt->execute($subject);
    }

    echo "Sample subjects added successfully.\n";

    // Add prerequisites (MATH102 requires MATH101, PHYS102 requires PHYS101 and MATH102)
    // First, get subject IDs
    $stmt = $pdo->prepare("SELECT id, subject_code FROM subjects WHERE subject_code IN (?, ?, ?)");
    $stmt->execute(['MATH101', 'MATH102', 'PHYS101']);
    $subjectIds = [];
    while ($row = $stmt->fetch()) {
        $subjectIds[$row['subject_code']] = $row['id'];
    }

    // Add prerequisites
    if (isset($subjectIds['MATH102']) && isset($subjectIds['MATH101'])) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO prerequisites (subject_id, prerequisite_subject_id) VALUES (?, ?)");
        $stmt->execute([$subjectIds['MATH102'], $subjectIds['MATH101']]);
        echo "Added prerequisite: MATH102 requires MATH101\n";
    }

    if (isset($subjectIds['PHYS102']) && isset($subjectIds['PHYS101'])) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO prerequisites (subject_id, prerequisite_subject_id) VALUES (?, ?)");
        $stmt->execute([$subjectIds['PHYS102'], $subjectIds['PHYS101']]);
        echo "Added prerequisite: PHYS102 requires PHYS101\n";
    }

    if (isset($subjectIds['PHYS102']) && isset($subjectIds['MATH102'])) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO prerequisites (subject_id, prerequisite_subject_id) VALUES (?, ?)");
        $stmt->execute([$subjectIds['PHYS102'], $subjectIds['MATH102']]);
        echo "Added prerequisite: PHYS102 requires MATH102\n";
    }

    echo "Prerequisites added successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
