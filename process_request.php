<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/email_helper.php'; // Corrected file reference

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    $dueDate = $_POST['due_date'] ?? null;
    $comment = $_POST['comment'] ?? '';

    try {
        if ($action === 'approve') {
            if (empty($dueDate)) {
                echo "Error: Due date is required to approve the request.";
                exit();
            }

            // Approve the request
            $sql = "UPDATE loan_records 
                    SET status = 'borrowed', due_date = :dueDate, comments = :comment 
                    WHERE id = :requestId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dueDate' => $dueDate,
                ':comment' => $comment,
                ':requestId' => $requestId
            ]);

            // Update inventory quantity
            $updateInventory = "UPDATE inventory i 
                                JOIN loan_records lr ON i.id = lr.item_id 
                                SET i.quantity = i.quantity - lr.quantity_borrowed 
                                WHERE lr.id = :requestId";
            $stmt = $pdo->prepare($updateInventory);
            $stmt->execute([':requestId' => $requestId]);

            // Fetch borrower details
            $borrowerQuery = "SELECT borrower_id, item_id, u.username AS borrower_name, u.email AS borrower_email 
                              FROM loan_records lr 
                              JOIN users u ON lr.borrower_id = u.id 
                              WHERE lr.id = :requestId";
            $stmt = $pdo->prepare($borrowerQuery);
            $stmt->execute([':requestId' => $requestId]);
            $borrower = $stmt->fetch(PDO::FETCH_ASSOC);

            // Create in-app notification for borrower
            $message = "Your request for item ID {$borrower['item_id']} has been approved.";
            $pdo->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (:user_id, :message, 0)")
                ->execute([':user_id' => $borrower['borrower_id'], ':message' => $message]);

            // Send email to borrower
            $subject = "Request Approved";
            sendEmail($borrower['borrower_email'], $subject, $message);

        } elseif ($action === 'reject') {
            // Reject the request
            $sql = "UPDATE loan_records 
                    SET status = 'rejected', comments = :comment 
                    WHERE id = :requestId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':comment' => $comment,
                ':requestId' => $requestId
            ]);

            // Fetch borrower details
            $borrowerQuery = "SELECT borrower_id, item_id, u.username AS borrower_name, u.email AS borrower_email 
                              FROM loan_records lr 
                              JOIN users u ON lr.borrower_id = u.id 
                              WHERE lr.id = :requestId";
            $stmt = $pdo->prepare($borrowerQuery);
            $stmt->execute([':requestId' => $requestId]);
            $borrower = $stmt->fetch(PDO::FETCH_ASSOC);

            // Create in-app notification for borrower
            $message = "Your request for item ID {$borrower['item_id']} has been rejected.";
            $pdo->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (:user_id, :message, 0)")
                ->execute([':user_id' => $borrower['borrower_id'], ':message' => $message]);

            // Send email to borrower
            $subject = "Request Rejected";
            sendEmail($borrower['borrower_email'], $subject, $message);
        }

        header('Location: lended_item_list.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>