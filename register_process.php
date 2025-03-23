<?php
require 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = strtolower($_POST['role']); // Normalize role to lowercase

    if (!empty($username) && !empty($password) && in_array($role, ['admin', 'teacher', 'student'])) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password,
                'role' => $role // Assign the selected role
            ]);
            header("Location: login.php?success=1");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required, and role must be valid!";
    }
}
?>