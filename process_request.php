<?php
session_start();
require_once 'includes/db_connect.php';

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
        }

        header('Location: lended_item_list.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>