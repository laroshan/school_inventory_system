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

                // Check if the action is user registration or admin adding a user
                if (isset($_POST['admin_action']) && $_POST['admin_action'] === 'add_user') {
                    // Redirect admin back to the dashboard
                    header("Location: user_details.php");
                    exit();
                } else {
                    // Automatically log in the user after registration
                    session_start();
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    header("Location: index.php");
                    exit();
                }
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