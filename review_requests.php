<?php
session_start();
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];
    $dueDate = $_POST['due_date'];
    $comment = $_POST['comment'];

    $sql = "UPDATE loan_records 
            SET status = :status, due_date = :dueDate, comments = :comment 
            WHERE id = :requestId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':dueDate' => $dueDate,
        ':comment' => $comment,
        ':requestId' => $requestId
    ]);

    if ($status === 'borrowed') {
        $updateInventory = "UPDATE inventory i 
                            JOIN loan_records lr ON i.id = lr.item_id 
                            SET i.quantity = i.quantity - lr.quantity_borrowed 
                            WHERE lr.id = :requestId";
        $stmt = $pdo->prepare($updateInventory);
        $stmt->execute([':requestId' => $requestId]);
    }

    header('Location: review_requests.php');
    exit();
}

$requests = $pdo->query("SELECT lr.*, i.item_name, u.username 
                         FROM loan_records lr 
                         JOIN inventory i ON lr.item_id = i.id 
                         JOIN users u ON lr.borrower_id = u.id 
                         WHERE lr.status = 'requested'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'partials/sidenav.php'; ?>
        <div class="page-content">
            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h3>Review Requests</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Requestor</th>
                                    <th>Quantity</th>
                                    <th>Request Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($request['item_name']) ?></td>
                                        <td><?= htmlspecialchars($request['username']) ?></td>
                                        <td><?= htmlspecialchars($request['quantity_borrowed']) ?></td>
                                        <td><?= htmlspecialchars($request['lending_date']) ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                                <select name="status" required>
                                                    <option value="borrowed">Approve</option>
                                                    <option value="rejected">Reject</option>
                                                </select>
                                                <input type="date" name="due_date" required>
                                                <textarea name="comment" placeholder="Comment (Optional)"></textarea>
                                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>

</html>