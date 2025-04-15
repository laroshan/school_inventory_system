<?php
require_once 'includes/db_connect.php';
require_once 'includes/email_helper.php';

try {
    $sql = "SELECT id, item_name FROM inventory WHERE quantity = 0";
    $stmt = $pdo->query($sql);
    $outOfStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($outOfStockItems) {
        $adminQuery = "SELECT email FROM users WHERE role = 'admin'";
        $adminStmt = $pdo->query($adminQuery);
        $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($admins as $admin) {
            $subject = "Out of Stock Alert";
            $message = "The following items are out of stock:<br>";
            foreach ($outOfStockItems as $item) {
                $message .= "- {$item['item_name']}<br>";
            }
            sendEmail($admin['email'], $subject, $message);
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
?>