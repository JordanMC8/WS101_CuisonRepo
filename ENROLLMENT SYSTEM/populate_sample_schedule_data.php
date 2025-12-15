<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Schedule.php';

// This script populates sample schedule data that matches the format provided
$schedule_model = new Schedule($pdo);

// Sample schedule data matching the format provided
$schedules = [
    // First entry
    [
        'subject_id' => 1, // Assuming this corresponds to URD_SIA101_IT
        'class_code' => '10114',
        'unit_hours' => '2.00/1.00',
        'time_from' => '15:00:00', // 03:00p
        'time_to' => '17:00:00',   // 05:00p
        'days' => 'HM', // H=Thursday, M=Monday
        'room' => 'AB1-207'
    ],
    [
        'subject_id' => 2, // Assuming this corresponds to URD_IPT101_IT
        'class_code' => '10114',
        'unit_hours' => '2.00/1.00',
        'time_from' => '11:00:00', // 11:00a
        'time_to' => '17:00:00',   // 05:00p
        'days' => 'HM', // H=Thursday, M=Monday
        'room' => 'AB1-206'
    ],

    // Second entry
    [
        'subject_id' => 3, // Assuming this corresponds to URD_SA101_IT
        'class_code' => '10113',
        'unit_hours' => '2.00/1.00',
        'time_from' => '14:00:00', // 02:00p
        'time_to' => '16:00:00',   // 04:00p
        'days' => 'FT', // F=Friday, T=Tuesday
        'room' => 'AB1-206'
    ],
    [
        'subject_id' => 4, // Assuming this corresponds to URD_IAS101_IT
        'class_code' => '10113',
        'unit_hours' => '2.00/1.00',
        'time_from' => '11:00:00', // 11:00a
        'time_to' => '14:00:00',   // 02:00p
        'days' => 'FT', // F=Friday, T=Tuesday
        'room' => 'TBA AB1-206'
    ],

    // Third entry
    [
        'subject_id' => 5, // Assuming this corresponds to URD_MD101_IT
        'class_code' => '10066',
        'unit_hours' => '2.00/1.00',
        'time_from' => '13:00:00', // 01:00p
        'time_to' => '15:00:00',   // 03:00p
        'days' => 'HF', // H=Thursday, F=Friday
        'room' => 'AB1-204'
    ],
    [
        'subject_id' => 6, // Assuming this corresponds to URD_CC105_IT
        'class_code' => '10066',
        'unit_hours' => '2.00/1.00',
        'time_from' => '07:00:00', // 07:00a
        'time_to' => '10:00:00',   // 10:00a
        'days' => 'HF', // H=Thursday, F=Friday
        'room' => 'AB1-204'
    ],
    // Note: URD_IM102_IT and URD_WD101_IT would need to be added as subjects first

    // Fourth entry
    [
        'subject_id' => 7, // Assuming this corresponds to URD_WS101_IT
        'class_code' => '10065',
        'unit_hours' => '2.00/1.00',
        'time_from' => '13:00:00', // 01:00p
        'time_to' => '15:00:00',   // 03:00p
        'days' => 'WT', // W=Wednesday, T=Tuesday
        'room' => 'AB1-206'
    ],
    [
        'subject_id' => 8, // Assuming this corresponds to URD_CC105_IT (duplicate)
        'class_code' => '10065',
        'unit_hours' => '2.00/1.00',
        'time_from' => '02:00:00', // 02:00a
        'time_to' => '06:00:00',   // 06:00a
        'days' => 'WT', // W=Wednesday, T=Tuesday
        'room' => 'AB1-206'
    ]
    // Note: URD_IM102_IT and URD_WD101_IT would need to be added as subjects first
];

echo "Populating sample schedule data that matches the provided format...\n";

// First, let's make sure we have enough subjects
$stmt = $pdo->query("SELECT COUNT(*) as count FROM subjects");
$result = $stmt->fetch();
$current_subject_count = $result['count'];

// If we don't have enough subjects, let's create some more
if ($current_subject_count < 8) {
    $needed_subjects = 8 - $current_subject_count;
    echo "Creating $needed_subjects additional subjects...\n";

    for ($i = $current_subject_count + 1; $i <= 8; $i++) {
        $subject_code = "SUBJ" . sprintf("%03d", $i);
        $subject_name = "Sample Subject " . $i;
        $credits = 3;
        $description = "Sample subject for schedule testing";

        $stmt = $pdo->prepare("INSERT INTO subjects (subject_code, subject_name, credits, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$subject_code, $subject_name, $credits, $description]);
        echo "Created subject: $subject_code - $subject_name\n";
    }
}

// Now populate the schedules
foreach ($schedules as $schedule) {
    $result = $schedule_model->addSchedule(
        $schedule['subject_id'],
        $schedule['class_code'],
        $schedule['unit_hours'],
        $schedule['time_from'],
        $schedule['time_to'],
        $schedule['days'],
        $schedule['room']
    );

    if ($result) {
        echo "Added schedule for subject ID {$schedule['subject_id']} (Class Code: {$schedule['class_code']})\n";
    } else {
        echo "Failed to add schedule for subject ID {$schedule['subject_id']} (Class Code: {$schedule['class_code']})\n";
    }
}

echo "Sample schedule data population complete.\n";
