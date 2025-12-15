<?php
// Script to assign faculty to teach IT subjects

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

    // Subjects to assign (using some of the IT subjects)
    $subjectsToAssign = ['URD_CAPTD1_IT', 'URD_IAS101_IT', 'URD_IPT101_IT'];

    foreach ($subjectsToAssign as $subjectCode) {
        // Get subject ID
        $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
        $stmt->execute([$subjectCode]);
        $subject = $stmt->fetch();

        if (!$subject) {
            echo "Subject $subjectCode not found.\n";
            continue;
        }

        $subjectId = $subject['id'];

        // Check if assignment already exists
        $stmt = $pdo->prepare("SELECT id FROM faculty_assignments WHERE faculty_id = ? AND subject_id = ?");
        $stmt->execute([$facultyId, $subjectId]);
        $existing = $stmt->fetch();

        if (!$existing) {
            // Assign faculty to subject
            $stmt = $pdo->prepare("INSERT INTO faculty_assignments (faculty_id, subject_id) VALUES (?, ?)");
            $stmt->execute([$facultyId, $subjectId]);
            echo "Faculty member assigned to teach $subjectCode successfully.\n";
        } else {
            echo "Faculty member already assigned to teach $subjectCode.\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
