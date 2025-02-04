<?php
$host = "localhost";
$dbname = "school_inventory";
$username = "root";
$password = "password";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log the error (optional)
    error_log("Database Connection Error: " . $e->getMessage(), 0);
    // Redirect to the error page
    header("Location: error.php");
    exit();
}
?>