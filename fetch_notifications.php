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
    $sql = "SELECT id, message, is_read FROM notifications WHERE user_id = :user_id AND is_read != 1 ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $unreadCount = count(array_filter($notifications, fn($n) => !$n['is_read']));

    echo json_encode(['notifications' => $notifications, 'unread_count' => $unreadCount]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>