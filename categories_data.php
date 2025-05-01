<?php
require_once 'includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT DISTINCT category FROM inventory ORDER BY category ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($categories);
} catch (Exception $e) {
    echo json_encode([]);
    error_log("Error fetching categories: " . $e->getMessage());
}
?>