<?php

/**
 * Test script to verify grade conversion functionality
 */

require_once 'config/db.php';

function convertGradeToNumeric($grade)
{
    $formatted_grade = '';

    // Check if grade is a letter grade
    $upper_grade = strtoupper($grade);
    switch ($upper_grade) {
        case 'A':
            $formatted_grade = '1.25';
            break;
        case 'B':
            $formatted_grade = '1.50';
            break;
        case 'C':
            $formatted_grade = '2.00';
            break;
        case 'D':
            $formatted_grade = '2.50';
            break;
        case 'F':
            $formatted_grade = '3.00';
            break;
        default:
            // Check if grade is numeric
            if (is_numeric($grade)) {
                $numeric_grade = floatval($grade);
                // Validate numeric grade range (0.00-4.00)
                if ($numeric_grade >= 0.00 && $numeric_grade <= 4.00) {
                    $formatted_grade = number_format($numeric_grade, 2, '.', '');
                }
            }
    }

    return $formatted_grade;
}

// Test cases
$test_cases = [
    'A' => '1.25',
    'B' => '1.50',
    'C' => '2.00',
    'D' => '2.50',
    'F' => '3.00',
    'a' => '1.25',  // Test lowercase
    '1.25' => '1.25',
    '2.75' => '2.75',
    '4.00' => '4.00',
    '0.00' => '0.00'
];

echo "Testing grade conversion functionality...\n\n";

foreach ($test_cases as $input => $expected) {
    $result = convertGradeToNumeric($input);
    $status = ($result === $expected) ? "PASS" : "FAIL";
    echo "Input: '$input' -> Output: '$result' (Expected: '$expected') [$status]\n";
}

echo "\nTest completed.\n";
