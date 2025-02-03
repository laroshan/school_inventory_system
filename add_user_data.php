<?php
require 'includes/db_connect.php';

$username = 'admin';
$password = 'admin123';  // Plain text password

// Hash the password before inserting into the database
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashed_password,
        'role'     => 'admin'
    ]);
    echo "User created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
