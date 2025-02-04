<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM inventory WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $delete_id]);

    echo "<script>alert('Item updated successfully!'); window.location.href = 'inventory.php';</script>";
}

// Fetch all inventory items
$sql = "SELECT * FROM inventory";
$stmt = $pdo->query($sql);
$inventoryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>