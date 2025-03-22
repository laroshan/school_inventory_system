<?php
require 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'role' => $role
                ]);
                header("Location: user_details.php?success=1");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Invalid email format!";
        }
    } else {
        echo "All fields are required!";
    }
}
?>