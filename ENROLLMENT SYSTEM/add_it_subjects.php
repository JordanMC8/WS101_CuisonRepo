<?php
// Script to populate the database with the provided subjects and prerequisites

require_once 'config/db.php';

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Define the subjects
    $subjects = [
        ['URD_CAPTD1_IT', 'Capstone Project 1', 3],
        ['URD_ELES1_IT', 'Elective 1 (Web Systems and Technologies 2)', 3],
        ['URD_ELEC1_IT_DA', 'Elective 1 (Data Warehousing 2)', 3],
        ['URD_ELES2_IT', 'Elective 2 (Mobile Application Development 2)', 3],
        ['URD_ELEC2_IT_DA', 'Elective 2(Data Mining 2)', 3],
        ['URD_DEVURD_GED', 'Elective (Mobile Application Development 2)', 3],
        ['URD_IAS101_IT', 'Information Assurance and Security 1', 3],
        ['URD_ID', 'Personality Development', 3],
        ['URD_IPT101_IT', 'Integrative Programming and Technologies 1', 3],
        ['URD_TECH101_IT', 'Technopreneurship', 3],
        // Prerequisites that need to be added as subjects
        ['URD_CC106', 'Unknown Subject (Prerequisite)', 3],
        ['ITURD_MS102_IT', 'Unknown Subject (Prerequisite)', 3],
        ['SHD_WST03_IT', 'Unknown Subject (Prerequisite)', 3],
        ['URD_DA_DW101', 'Unknown Subject (Prerequisite)', 3],
        ['URD_MD101_IT', 'Unknown Subject (Prerequisite)', 3],
        ['URD_DA_DM101', 'Unknown Subject (Prerequisite)', 3],
        ['URD_CC106_IT', 'Unknown Subject (Prerequisite)', 3],
        ['URD_WST01_IT', 'Unknown Subject (Prerequisite)', 3]
    ];

    // Insert subjects
    $subjectIds = [];
    foreach ($subjects as $subject) {
        // Check if subject already exists
        $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
        $stmt->execute([$subject[0]]);
        $existing = $stmt->fetch();

        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_code, subject_name, credits) VALUES (?, ?, ?)");
            $stmt->execute($subject);
            $subjectIds[$subject[0]] = $pdo->lastInsertId();
        } else {
            $subjectIds[$subject[0]] = $existing['id'];
        }
    }

    echo "Subjects added/verified successfully.\n";

    // Define prerequisites
    $prerequisites = [
        ['URD_CAPTD1_IT', ['URD_CC106', 'ITURD_MS102_IT']],
        ['URD_ELES1_IT', ['SHD_WST03_IT']],
        ['URD_ELEC1_IT_DA', ['URD_DA_DW101']],
        ['URD_ELES2_IT', ['URD_MD101_IT']],
        ['URD_ELEC2_IT_DA', ['URD_DA_DM101']],
        ['URD_IAS101_IT', ['URD_CC106_IT']],
        ['URD_IPT101_IT', ['URD_CC106_IT']],
        ['URD_TECH101_IT', ['URD_WST01_IT']]
    ];

    // Add prerequisites
    foreach ($prerequisites as $prereq) {
        $subjectCode = $prereq[0];
        $prereqCodes = $prereq[1];

        if (!isset($subjectIds[$subjectCode])) {
            echo "Warning: Subject $subjectCode not found\n";
            continue;
        }

        $subjectId = $subjectIds[$subjectCode];

        foreach ($prereqCodes as $prereqCode) {
            if (!isset($subjectIds[$prereqCode])) {
                echo "Warning: Prerequisite subject $prereqCode not found for $subjectCode\n";
                continue;
            }

            $prereqId = $subjectIds[$prereqCode];

            // Check if prerequisite already exists
            $stmt = $pdo->prepare("SELECT id FROM prerequisites WHERE subject_id = ? AND prerequisite_subject_id = ?");
            $stmt->execute([$subjectId, $prereqId]);
            $existing = $stmt->fetch();

            if (!$existing) {
                $stmt = $pdo->prepare("INSERT INTO prerequisites (subject_id, prerequisite_subject_id) VALUES (?, ?)");
                $stmt->execute([$subjectId, $prereqId]);
                echo "Added prerequisite: $subjectCode requires $prereqCode\n";
            }
        }
    }

    // Commit transaction
    $pdo->commit();
    echo "All prerequisites added successfully.\n";
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}
