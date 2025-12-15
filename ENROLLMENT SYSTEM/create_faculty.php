<?php
// Script to create a test faculty member

require_once 'config/db.php';

try {
    $username = 'faculty1';
    $password = password_hash('faculty123', PASSWORD_DEFAULT);
    $email = 'faculty1@example.com';
    $first_name = 'Dr. Jane';
    $last_name = 'Smith';
    $role = 'faculty';

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $first_name, $last_name, $role]);

    echo "Test faculty account created successfully.\n";
    echo "Username: faculty1\n";
    echo "Password: faculty123\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
