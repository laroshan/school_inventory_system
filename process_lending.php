<?php
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrowerId = $_POST['borrower_id'];
    $lendingDate = $_POST['lending_date'];
    $dueDate = $_POST['due_date'];
    $itemIds = $_POST['item_ids'];
    $quantities = $_POST['quantity'];

    try {
        $pdo->beginTransaction();

        foreach ($itemIds as $itemId) {
            $quantity = $quantities[$itemId];

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

        $pdo->commit();
        header("Location: inventory.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>