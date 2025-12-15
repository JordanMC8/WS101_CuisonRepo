<?php
require_once 'config/db.php';

echo "CURRENT SUBJECTS:\n";
$stmt = $pdo->query('SELECT * FROM subjects ORDER BY id');
while ($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . ", Code: " . $row['subject_code'] . ", Name: '" . $row['subject_name'] . "'\n";
}
