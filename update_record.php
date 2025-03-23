<?php
session_start();
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recordId = $_POST['record_id'];
    $dueDate = $_POST['due_date'];
    $comment = $_POST['comment'] ?? '';

    try {
        // Update the record
        $sql = "UPDATE loan_records 
                SET due_date = :dueDate, comments = :comment 
                WHERE id = :recordId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dueDate' => $dueDate,
            ':comment' => $comment,
            ':recordId' => $recordId
        ]);

        header('Location: lended_item_list.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>