<?php

/**
 * Migration script to convert existing percentage grades to GPA scale (0.00-4.00)
 * This script converts grades using a standard 10-point scale:
 * 90-100% -> 4.0
 * 80-89%  -> 3.0
 * 70-79%  -> 2.0
 * 60-69%  -> 1.0
 * 0-59%   -> 0.0
 */

require_once 'config/db.php';

function convertPercentageToGPA($percentage)
{
    if ($percentage >= 90) {
        return 4.0;
    } elseif ($percentage >= 80) {
        return 3.0;
    } elseif ($percentage >= 70) {
        return 2.0;
    } elseif ($percentage >= 60) {
        return 1.0;
    } else {
        return 0.0;
    }
}

try {
    echo "Starting grade migration from percentage to GPA...\n";

    // Get all enrollments with grades
    $stmt = $pdo->query("SELECT id, grade FROM enrollments WHERE grade IS NOT NULL AND grade != ''");
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $convertedCount = 0;

    foreach ($enrollments as $enrollment) {
        $enrollmentId = $enrollment['id'];
        $currentGrade = $enrollment['grade'];

        // Check if the grade is numeric (percentage)
        if (is_numeric($currentGrade)) {
            $percentage = floatval($currentGrade);

            // Convert to GPA
            $gpaGrade = convertPercentageToGPA($percentage);

            // Update the enrollment with the new GPA grade
            $updateStmt = $pdo->prepare("UPDATE enrollments SET grade = ? WHERE id = ?");
            $updateStmt->execute([number_format($gpaGrade, 2, '.', ''), $enrollmentId]);

            echo "Converted enrollment ID {$enrollmentId}: {$percentage}% -> {$gpaGrade} GPA\n";
            $convertedCount++;
        }
    }

    echo "Migration completed. Converted {$convertedCount} grades from percentage to GPA.\n";
} catch (PDOException $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
}
