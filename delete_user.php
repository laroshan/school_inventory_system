<?php
require_once 'includes/db_connect.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $delete_id]);
        header("Location: user_details.php?success=1");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>