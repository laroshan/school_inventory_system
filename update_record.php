<?php
session_start();
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recordId = $_POST['record_id'];
    $action = $_POST['action'] ?? null;

    try {
        if ($action === 'mark_returned') {
            // Fetch loan record details
            $loanQuery = "SELECT lr.item_id, lr.item_instance_id, lr.quantity_borrowed, i.is_serialized 
                          FROM loan_records lr
                          JOIN inventory i ON lr.item_id = i.id
                          WHERE lr.id = :recordId";
            $stmt = $pdo->prepare($loanQuery);
            $stmt->execute([':recordId' => $recordId]);
            $loanRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$loanRecord) {
                throw new Exception("Loan record not found.");
            }

            $itemId = $loanRecord['item_id'];
            $itemInstanceId = $loanRecord['item_instance_id'];
            $quantityBorrowed = $loanRecord['quantity_borrowed'];
            $isSerialized = $loanRecord['is_serialized'];

            if ($isSerialized) {
                // Handle serialized items
                if (!$itemInstanceId) {
                    throw new Exception("Serialized item instance ID is missing.");
                }

                // Mark the specific serial number as available
                $updateSerialStatus = "UPDATE inventory_items 
                                       SET status = 'available' 
                                       WHERE id = :itemInstanceId";
                $stmt = $pdo->prepare($updateSerialStatus);
                $stmt->execute([':itemInstanceId' => $itemInstanceId]);

                // Increase the quantity in the inventory table
                $updateInventory = "UPDATE inventory 
                                    SET quantity = quantity + 1 
                                    WHERE id = :itemId";
                $stmt = $pdo->prepare($updateInventory);
                $stmt->execute([':itemId' => $itemId]);
            } else {
                // Handle non-serialized items
                // Increase the quantity in the inventory table
                $updateInventory = "UPDATE inventory 
                                    SET quantity = quantity + :quantityBorrowed 
                                    WHERE id = :itemId";
                $stmt = $pdo->prepare($updateInventory);
                $stmt->execute([
                    ':quantityBorrowed' => $quantityBorrowed,
                    ':itemId' => $itemId
                ]);
            }

            // Mark the loan record as returned
            $updateLoanRecord = "UPDATE loan_records 
                                 SET status = 'returned', returned_date = NOW() 
                                 WHERE id = :recordId";
            $stmt = $pdo->prepare($updateLoanRecord);
            $stmt->execute([':recordId' => $recordId]);

            echo "<script>alert('Item marked as returned successfully.'); window.location.href = 'lended_item_list.php';</script>";
        } elseif ($action === 'approve') {
            // Fetch loan record details
            $loanQuery = "SELECT lr.item_id, lr.item_instance_id, lr.quantity_borrowed, i.is_serialized 
                          FROM loan_records lr
                          JOIN inventory i ON lr.item_id = i.id
                          WHERE lr.id = :recordId";
            $stmt = $pdo->prepare($loanQuery);
            $stmt->execute([':recordId' => $recordId]);
            $loanRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$loanRecord) {
                throw new Exception("Loan record not found.");
            }

            $itemId = $loanRecord['item_id'];
            $itemInstanceId = $loanRecord['item_instance_id'];
            $quantityBorrowed = $loanRecord['quantity_borrowed'];
            $isSerialized = $loanRecord['is_serialized'];

            echo '<script>console.log("Approving item ID: ' . $itemId . '");</script>';

            if ($isSerialized) {
                // Reduce inventory quantity by 1 for serialized items
                if (!$itemInstanceId) {
                    throw new Exception("Serialized item instance ID is missing.");
                }


                // Update the inventory table to reduce the quantity
                $updateInventory = "UPDATE inventory 
                                    SET quantity = quantity - 1
                                    WHERE id = :itemId";
                $stmt = $pdo->prepare($updateInventory);
                $stmt->execute([':itemId' => $itemId]);

                // // Update the inventory_items table to mark the serial number as loaned
                // $updateSerialStatus = "UPDATE inventory_items 
                //                        SET status = 'loaned' 
                //                        WHERE id = :itemInstanceId";
                // $stmt = $pdo->prepare($updateSerialStatus);
                // $stmt->execute([':itemInstanceId' => $itemInstanceId]);

                error_log("Marked serial number ID: $itemInstanceId as loaned");
            } else {
                // Reduce inventory quantity by the quantity borrowed for non-serialized items
                error_log("Reducing inventory quantity for non-serialized item ID: $itemId by $quantityBorrowed");

                $updateInventory = "UPDATE inventory 
                                    SET quantity = quantity - :quantityBorrowed 
                                    WHERE id = :itemId";
                $stmt = $pdo->prepare($updateInventory);
                $stmt->execute([
                    ':quantityBorrowed' => $quantityBorrowed,
                    ':itemId' => $itemId
                ]);
            }

            // Mark the loan record as approved
            $updateLoanRecord = "UPDATE loan_records 
                                 SET status = 'borrowed', lending_date = NOW() 
                                 WHERE id = :recordId";
            $stmt = $pdo->prepare($updateLoanRecord);
            $stmt->execute([':recordId' => $recordId]);

            error_log("Approved loan record ID: $recordId");
            echo '<script>alert("Item approved successfully."); window.location.href = "lended_item_list.php";</script>';
        } else {
            $dueDate = $_POST['due_date'];
            $comment = $_POST['comment'] ?? '';

            // Update the record
            $sql = "UPDATE loan_records 
                    SET due_date = :dueDate, comments = :comment 
                    WHERE id = :recordId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dueDate' => $dueDate,
                ':comment' => $comment,
                ':recordId' => $recordId
            ]);

            header('Location: lended_item_list.php');
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>