<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT 
                SUM(CASE WHEN status = 'due' THEN 1 ELSE 0 END) AS due, 
                SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue 
            FROM loan_records";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $labels = ['Due', 'Overdue'];
    $values = [$data['due'], $data['overdue']];

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>