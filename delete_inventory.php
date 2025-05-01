<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $inventoryId = $_GET['delete_id'];

    try {
        // Check if the inventory item is referenced in loan_records
        $checkQuery = "SELECT COUNT(*) FROM loan_records WHERE item_id = :inventoryId";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([':inventoryId' => $inventoryId]);
        $referenceCount = $stmt->fetchColumn();

        if ($referenceCount > 0) {
            // Prevent deletion if the item is referenced
            echo "<script>alert('Cannot delete this item because it is referenced in loan records.'); window.location.href = 'inventory.php';</script>";
            exit();
        }

        // Delete serialized items from inventory_items
        $deleteSerializedQuery = "DELETE FROM inventory_items WHERE inventory_id = :inventoryId";
        $stmt = $pdo->prepare($deleteSerializedQuery);
        $stmt->execute([':inventoryId' => $inventoryId]);

        // Delete the inventory item
        $deleteQuery = "DELETE FROM inventory WHERE id = :inventoryId";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->execute([':inventoryId' => $inventoryId]);

        echo "<script>alert('Item deleted successfully.'); window.location.href = 'inventory.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: inventory.php');
    exit();
}

// Fetch all inventory items
$sql = "SELECT * FROM inventory";
$stmt = $pdo->query($sql);
$inventoryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>