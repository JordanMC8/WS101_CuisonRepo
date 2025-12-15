<?php
// Script to create a test student account

require_once 'config/db.php';

try {
    $username = 'student1';
    $password = password_hash('student123', PASSWORD_DEFAULT);
    $email = 'student1@example.com';
    $first_name = 'John';
    $last_name = 'Doe';
    $role = 'student';

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $first_name, $last_name, $role]);

    echo "Test student account created successfully.\n";
    echo "Username: student1\n";
    echo "Password: student123\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
