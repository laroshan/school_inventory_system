<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT category, 
                   SUM(CASE WHEN lr.status = 'borrowed' THEN lr.quantity_borrowed ELSE 0 END) AS borrowed, 
                   SUM(i.quantity) AS available 
            FROM inventory i 
            LEFT JOIN loan_records lr ON i.id = lr.item_id 
            GROUP BY category 
            ORDER BY category ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($data, 'category');
    $borrowed = array_column($data, 'borrowed');
    $available = array_column($data, 'available');

    echo json_encode(['labels' => $labels, 'borrowed' => $borrowed, 'available' => $available]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>