<?php
require 'includes/db_connect.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate inputs
    if (!empty($username) && !empty($password) && !empty($role)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password,
                'role' => $role
            ]);
            header("Location: add_user.php?success=1");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required!";
    }
}
?>