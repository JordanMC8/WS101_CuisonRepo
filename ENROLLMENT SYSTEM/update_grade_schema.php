<?php
require_once 'config/db.php';

try {
    // Modify the grade column to accept decimal values with more precision
    $sql = "ALTER TABLE enrollments MODIFY COLUMN grade DECIMAL(4,2)";
    $pdo->exec($sql);
    echo "Database schema updated successfully.\n";
} catch (PDOException $e) {
    echo "Error updating database schema: " . $e->getMessage() . "\n";
}
