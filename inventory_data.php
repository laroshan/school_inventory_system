<?php
// Include the database connection file
require_once 'includes/db_connect.php';
// Set the content type to JSON
header('Content-Type: application/json');

try {
    // Fetch inventory data
    $sql = "SELECT id, item_name, category, item_description, quantity, unit_price, amount, status, inventory_date 
            FROM inventory";
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
