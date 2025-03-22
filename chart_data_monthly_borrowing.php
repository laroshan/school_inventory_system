<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT DATE_FORMAT(lending_date, '%Y-%m') AS month, COUNT(*) AS total_borrowed 
            FROM loan_records 
            GROUP BY month 
            ORDER BY month ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($data, 'month');
    $values = array_column($data, 'total_borrowed');

    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>