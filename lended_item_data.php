<?php
// Include the database connection file
require_once 'includes/db_connect.php';

// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Fetch lended items data
    $sql = "SELECT lr.id, i.item_name, i.category, u.username AS borrower, lr.quantity_borrowed, lr.lending_date, lr.due_date, lr.returned_date, lr.status, lr.comments, i.is_serialized,
            (CASE WHEN i.is_serialized = 1 THEN (
                SELECT JSON_ARRAYAGG(JSON_OBJECT('id', ii.id, 'serial_number', ii.serial_number))
                FROM inventory_items ii
                WHERE ii.inventory_id = i.id AND ii.status = 'available'
            ) ELSE NULL END) AS available_serial_numbers
        FROM loan_records lr
        JOIN inventory i ON lr.item_id = i.id
        JOIN users u ON lr.borrower_id = u.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Handle other errors
    echo json_encode([
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
?>