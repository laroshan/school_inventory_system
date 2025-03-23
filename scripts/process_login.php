<?php
session_start();
require_once '../includes/db_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT id, username, role, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = strtolower($user['role']); // Normalize role to lowercase
    header('Location: ../index.php');
} else {
    $_SESSION['login_error'] = 'Invalid username or password';
    header('Location: ../login.php');
}
exit();
?>