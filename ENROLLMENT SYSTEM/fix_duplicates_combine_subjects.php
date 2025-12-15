<?php
require_once 'config/db.php';
require_once 'models/Schedule.php';

echo "Analyzing and fixing duplicate subjects...\n";

// First, let's understand what we have
echo "\n=== CURRENT SUBJECTS ===\n";
$stmt = $pdo->query('SELECT * FROM subjects ORDER BY id');
$subjects = $stmt->fetchAll();
foreach ($subjects as $subject) {
    echo "ID: " . $subject['id'] . ", Code: " . $subject['subject_code'] . ", Name: '" . $subject['subject_name'] . "'\n";
}

echo "\n=== CURRENT SCHEDULES ===\n";
$stmt = $pdo->query('SELECT cs.*, s.subject_code, s.subject_name FROM class_schedules cs JOIN subjects s ON cs.subject_id = s.id ORDER BY cs.class_code, cs.days, cs.time_from');
$schedules = $stmt->fetchAll();

// Group schedules by class_code, time, days, and room to identify duplicates
$scheduleGroups = [];
foreach ($schedules as $schedule) {
    $key = $schedule['class_code'] . '|' . $schedule['time_from'] . '|' . $schedule['time_to'] . '|' . $schedule['days'] . '|' . $schedule['room'];
    if (!isset($scheduleGroups[$key])) {
        $scheduleGroups[$key] = [];
    }
    $scheduleGroups[$key][] = $schedule;
}

echo "\n=== SCHEDULE GROUPS WITH SAME TIME/DATE/ROOM ===\n";
foreach ($scheduleGroups as $key => $group) {
    if (count($group) > 1) {
        echo "Group with same time/date/room:\n";
        foreach ($group as $schedule) {
            echo "  - Subject ID: " . $schedule['subject_id'] . ", Code: " . $schedule['subject_code'] . ", Name: '" . $schedule['subject_name'] . "'\n";
        }
        echo "\n";
    }
}

// Now, let's implement the fix:
// 1. Remove duplicate subjects that have the same schedule time/date/room
// 2. Combine subjects with same schedule into a single entry

echo "\n=== IMPLEMENTING FIXES ===\n";

// Clear existing enrollments and schedules to avoid foreign key issues
echo "Clearing existing data...\n";
$pdo->exec("DELETE FROM enrollments");
$pdo->exec("DELETE FROM class_schedules");

// Create a mapping for combining subjects
$subjectMapping = [
    // Based on your original format, these subjects should be combined:
    // Class Code 10114 - Same time, date, room should be combined
    1 => 1, // URD_SIA101_IT stays as ID 1
    2 => 1, // URD_IPT101_IT should be combined with URD_SIA101_IT

    // Class Code 10113 - Same time, date, room should be combined
    3 => 3, // URD_SA101_IT stays as ID 3
    4 => 3, // URD_IAS101_IT should be combined with URD_SA101_IT

    // Class Code 10066 - Same time, date, room should be combined
    5 => 5,  // URD_MD101_IT stays as ID 5
    6 => 5,  // URD_CC105A_IT should be combined with URD_MD101_IT
    7 => 5,  // URD_IM102A_IT should be combined with URD_MD101_IT
    8 => 5,  // URD_WD101A_IT should be combined with URD_MD101_IT

    // Class Code 10065 - Same time, date, room should be combined
    9 => 9,  // URD_WS101_IT stays as ID 9
    10 => 9, // URD_CC105B_IT should be combined with URD_WS101_IT
    11 => 9, // URD_IM102B_IT should be combined with URD_WS101_IT
    12 => 9  // URD_WD101B_IT should be combined with URD_WS101_IT
];

// Create new combined schedule entries
echo "Creating combined schedule entries...\n";

$scheduleModel = new Schedule($pdo);

// Combined schedules based on your original format
$combinedSchedules = [
    // Class Code 10114 - Both subjects share the same time slot
    [
        'subject_id' => 1, // URD_SIA101_IT (represents both subjects)
        'class_code' => '10114',
        'unit_hours' => '2.00/1.00',
        'time_from' => '11:00:00', // Earliest time (11:00a)
        'time_to' => '17:00:00',   // Latest time (05:00p)
        'days' => 'HM', // H=Thursday, M=Monday
        'room' => 'AB1-207/AB1-206' // Combined rooms
    ],

    // Class Code 10113 - Both subjects share the same time slot
    [
        'subject_id' => 3, // URD_SA101_IT (represents both subjects)
        'class_code' => '10113',
        'unit_hours' => '2.00/1.00',
        'time_from' => '11:00:00', // Earliest time (11:00a)
        'time_to' => '16:00:00',   // Latest time (04:00p)
        'days' => 'FT', // F=Friday, T=Tuesday
        'room' => 'AB1-206/TBA AB1-206' // Combined rooms
    ],

    // Class Code 10066 - Four subjects share the same time slot
    [
        'subject_id' => 5, // URD_MD101_IT (represents all four subjects)
        'class_code' => '10066',
        'unit_hours' => '2.00/1.00',
        'time_from' => '07:00:00', // Earliest time (07:00a)
        'time_to' => '15:00:00',   // Latest time (03:00p)
        'days' => 'HF', // H=Thursday, F=Friday
        'room' => 'AB1-204' // Same room for all
    ],

    // Class Code 10065 - Four subjects share the same time slot
    [
        'subject_id' => 9, // URD_WS101_IT (represents all four subjects)
        'class_code' => '10065',
        'unit_hours' => '2.00/1.00',
        'time_from' => '02:00:00', // Earliest time (02:00a)
        'time_to' => '15:00:00',   // Latest time (03:00p)
        'days' => 'WT', // W=Wednesday, T=Tuesday
        'room' => 'AB1-206' // Same room for all
    ]
];

// Add the combined schedules
foreach ($combinedSchedules as $schedule) {
    $result = $scheduleModel->addSchedule(
        $schedule['subject_id'],
        $schedule['class_code'],
        $schedule['unit_hours'],
        $schedule['time_from'],
        $schedule['time_to'],
        $schedule['days'],
        $schedule['room']
    );

    if ($result) {
        echo "Added combined schedule for subject ID {$schedule['subject_id']} (Class Code: {$schedule['class_code']})\n";
    } else {
        echo "Failed to add combined schedule for subject ID {$schedule['subject_id']} (Class Code: {$schedule['class_code']})\n";
    }
}

// Remove duplicate subjects (IDs 2, 4, 6, 7, 8, 10, 11, 12)
$duplicateSubjectIds = [2, 4, 6, 7, 8, 10, 11, 12];
foreach ($duplicateSubjectIds as $id) {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $result = $stmt->execute([$id]);
    if ($result) {
        echo "Removed duplicate subject ID $id\n";
    } else {
        echo "Failed to remove duplicate subject ID $id\n";
    }
}

// Update remaining subjects to have proper names
$subjectUpdates = [
    1 => ['URD_SIA101_IT', 'COI PRE-REQUISITE'], // Represents both SIA101 and IPT101
    3 => ['URD_SA101_IT', 'COI PRE-REQUISITE'],  // Represents both SA101 and IAS101
    5 => ['URD_MD101_IT', 'COI PRE-REQUISITE'],  // Represents MD101, CC105, IM102, WD101
    9 => ['URD_WS101_IT', 'COI PRE-REQUISITE']   // Represents WS101, CC105, IM102, WD101
];

foreach ($subjectUpdates as $id => $data) {
    $stmt = $pdo->prepare("UPDATE subjects SET subject_code = ?, subject_name = ? WHERE id = ?");
    $result = $stmt->execute([$data[0], $data[1], $id]);
    if ($result) {
        echo "Updated subject ID $id to {$data[0]} - {$data[1]}\n";
    } else {
        echo "Failed to update subject ID $id\n";
    }
}

// Re-enroll the test student in the remaining subjects
echo "\nRe-enrolling test student in remaining subjects...\n";
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute(['student1']);
$student = $stmt->fetch();

if ($student) {
    $student_id = $student['id'];

    // Enroll in the 4 remaining subjects (1, 3, 5, 9)
    $remainingSubjects = [1, 3, 5, 9];
    foreach ($remainingSubjects as $subject_id) {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
        $result = $stmt->execute([$student_id, $subject_id]);

        if ($result) {
            echo "Enrolled student in subject ID $subject_id\n";
        } else {
            echo "Failed to enroll student in subject ID $subject_id\n";
        }
    }

    echo "Test student re-enrollment complete.\n";
} else {
    echo "Test student not found.\n";
}

echo "\nDuplicate removal and subject combination complete!\n";
echo "Remaining subjects now represent groups of subjects that share the same schedule.\n";
