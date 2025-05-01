<?php
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrowerId = $_POST['borrower_id'];
    $lendingDate = $_POST['lending_date'];
    $dueDate = $_POST['due_date'];
    $itemIds = $_POST['item_ids'];
    $quantities = $_POST['quantity'] ?? []; // Ensure quantity is defined

    try {
        $pdo->beginTransaction();

        foreach ($itemIds as $itemId) {
            $quantity = $quantities[$itemId] ?? 0; // Default to 0 if not set

            // Check if the item is serialized
            $isSerializedQuery = "SELECT is_serialized FROM inventory WHERE id = :itemId";
            $stmt = $pdo->prepare($isSerializedQuery);
            $stmt->execute([':itemId' => $itemId]);
            $isSerialized = $stmt->fetchColumn();

            if ($isSerialized) {
                // Handle serialized items
                $serialNumbers = $_POST['serial_numbers'][$itemId] ?? [];
                if (count($serialNumbers) !== (int) $quantity) {
                    throw new Exception("The number of selected serial numbers does not match the quantity for item ID $itemId.");
                }

                foreach ($serialNumbers as $serialNumberId) {
                    // Insert loan record for each serial number
                    $loanQuery = "INSERT INTO loan_records (item_id, borrower_id, quantity_borrowed, status, lending_date, due_date, item_instance_id)
                                  VALUES (:itemId, :borrowerId, 1, 'borrowed', :lendingDate, :dueDate, :serialNumberId)";
                    $stmt = $pdo->prepare($loanQuery);
                    $stmt->execute([
                        ':itemId' => $itemId,
                        ':borrowerId' => $borrowerId,
                        ':lendingDate' => $lendingDate,
                        ':dueDate' => $dueDate,
                        ':serialNumberId' => $serialNumberId
                    ]);

                    // Update the status of the serial number
                    $updateSerialStatus = "UPDATE inventory_items 
                                           SET status = 'loaned' 
                                           WHERE id = :serialNumberId";
                    $stmt = $pdo->prepare($updateSerialStatus);
                    $stmt->execute([':serialNumberId' => $serialNumberId]);
                }
            } else {
                // Handle non-serialized items
                // Check if quantity is lendable
                $availableQuantity = $pdo->query("SELECT quantity FROM inventory WHERE id = $itemId")->fetchColumn();
                if ($quantity > $availableQuantity) {
                    throw new Exception("Quantity for item ID $itemId exceeds available stock.");
                }

                // Insert loan record
                $loanQuery = "INSERT INTO loan_records (item_id, borrower_id, quantity_borrowed, status, lending_date, due_date)
                              VALUES (:itemId, :borrowerId, :quantity, 'borrowed', :lendingDate, :dueDate)";
                $stmt = $pdo->prepare($loanQuery);
                $stmt->execute([
                    ':itemId' => $itemId,
                    ':borrowerId' => $borrowerId,
                    ':quantity' => $quantity,
                    ':lendingDate' => $lendingDate,
                    ':dueDate' => $dueDate
                ]);

                // Update inventory quantity
                $updateQuery = "UPDATE inventory SET quantity = quantity - :quantity WHERE id = :itemId";
                $stmt = $pdo->prepare($updateQuery);
                $stmt->execute([
                    ':quantity' => $quantity,
                    ':itemId' => $itemId
                ]);
            }
        }

        $pdo->commit();
        header("Location: inventory.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>