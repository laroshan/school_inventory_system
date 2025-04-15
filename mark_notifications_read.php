<?php
require_once 'includes/db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

try {
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>