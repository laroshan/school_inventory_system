<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT DATE_FORMAT(inventory_date, '%Y-%m') AS month, SUM(quantity) AS total_stock 
            FROM inventory 
            GROUP BY month 
            ORDER BY month ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($data, 'month');
    $values = array_column($data, 'total_stock');

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>