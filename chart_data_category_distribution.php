<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT category, SUM(quantity) AS total_quantity 
            FROM inventory 
            GROUP BY category 
            ORDER BY category ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($data, 'category');
    $values = array_column($data, 'total_quantity');

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>