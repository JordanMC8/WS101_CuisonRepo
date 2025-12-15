<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'models/Schedule.php';

// This script populates sample schedule data for testing purposes
$schedule_model = new Schedule($pdo);

// Sample schedule data
$schedules = [
    [
        'subject_id' => 1, // MATH101
        'class_code' => '10114',
        'unit_hours' => '2.00/1.00',
        'time_from' => '15:00:00',
        'time_to' => '17:00:00',
        'days' => 'MWF',
        'room' => 'AB1-207'
    ],
    [
        'subject_id' => 1, // MATH101
        'class_code' => '10113',
        'unit_hours' => '2.00/1.00',
        'time_from' => '14:00:00',
        'time_to' => '16:00:00',
        'days' => 'TTH',
        'room' => 'AB1-206'
    ],
    [
        'subject_id' => 2, // MATH102
        'class_code' => '10066',
        'unit_hours' => '2.00/1.00',
        'time_from' => '13:00:00',
        'time_to' => '15:00:00',
        'days' => 'MWF',
        'room' => 'AB1-204'
    ],
    [
        'subject_id' => 3, // PHYS101
        'class_code' => '10065',
        'unit_hours' => '2.00/1.00',
        'time_from' => '13:00:00',
        'time_to' => '15:00:00',
        'days' => 'TTH',
        'room' => 'AB1-206'
    ]
];

echo "Populating sample schedule data...\n";

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
        echo "Added schedule for subject ID {$schedule['subject_id']}\n";
    } else {
        echo "Failed to add schedule for subject ID {$schedule['subject_id']}\n";
    }
}

echo "Sample schedule data population complete.\n";
