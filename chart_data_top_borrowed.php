<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT i.item_name, COUNT(*) AS times_borrowed 
            FROM loan_records lr 
            JOIN inventory i ON lr.item_id = i.id 
            GROUP BY i.item_name 
            ORDER BY times_borrowed DESC 
            LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($data, 'item_name');
    $values = array_column($data, 'times_borrowed');

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>