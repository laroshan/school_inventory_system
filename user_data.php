<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

try {
    // Fetch user data
    $sql = "SELECT id, username, email, role, created_at FROM users";
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