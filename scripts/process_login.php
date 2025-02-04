<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Log the received username and password (for debugging only, remove in production)
    file_put_contents('login_debug.log', "Received Username: $username\n", FILE_APPEND);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log if user was found
        if ($user) {
            file_put_contents('login_debug.log', "User found: " . json_encode($user) . "\n", FILE_APPEND);
        } else {
            file_put_contents('login_debug.log', "No user found for username: $username\n", FILE_APPEND);
        }

        // Check password verification
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            file_put_contents('login_debug.log', "Password verified successfully for user: $username\n", FILE_APPEND);

            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid username or password.";
            file_put_contents('login_debug.log', "Password verification failed for user: $username\n", FILE_APPEND);
            header('Location: ../login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
        file_put_contents('login_debug.log', "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
        header('Location: ../login.php');
        exit();
    }
}
?>