<?php
require_once 'includes/db_connect.php';
require_once 'includes/email_helper.php'; // Helper for sending emails

try {
    $thresholdDays = 3; // Notify if due date is within 3 days
    $sql = "SELECT lr.id, lr.due_date, lr.borrower_id, u.email AS borrower_email, u.username AS borrower_name, 
                   a.email AS admin_email, i.item_name 
            FROM loan_records lr
            JOIN users u ON lr.borrower_id = u.id
            JOIN inventory i ON lr.item_id = i.id
            CROSS JOIN (SELECT email FROM users WHERE role = 'admin') a
            WHERE lr.status = 'borrowed' AND lr.due_date <= DATE_ADD(CURDATE(), INTERVAL :thresholdDays DAY)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':thresholdDays' => $thresholdDays]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        // Create in-app notifications
        $message = "The item '{$item['item_name']}' is nearing its due date ({$item['due_date']}).";
        $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)")
            ->execute([':user_id' => $item['borrower_id'], ':message' => $message]);

        // Send email to borrower
        sendEmail($item['borrower_email'], "Item Due Reminder", $message);

        // Send email to admin
        sendEmail($item['admin_email'], "Item Due Reminder", $message);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
?>