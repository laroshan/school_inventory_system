<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/email_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $comment = $_POST['comment'];
    $borrowerId = $_SESSION['user_id'];
    $borrowerName = $_SESSION['username']; // Fetch username from session

    try {
        $sql = "INSERT INTO loan_records (item_id, borrower_id, quantity_borrowed, status, lending_date, comments) 
                VALUES (:itemId, :borrowerId, :quantity, 'requested', CURDATE(), :comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':itemId' => $itemId,
            ':borrowerId' => $borrowerId,
            ':quantity' => $quantity,
            ':comment' => $comment
        ]);

        // Fetch admin details
        $adminQuery = "SELECT id, email FROM users WHERE role = 'admin'";
        $adminStmt = $pdo->query($adminQuery);
        $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($admins as $admin) {
            // Create in-app notification for admin
            $message = "A new item request has been made by user '{$borrowerName}'.";
            $pdo->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (:user_id, :message, 0)")
                ->execute([':user_id' => $admin['id'], ':message' => $message]);

            // Send email to admin
            $subject = "New Item Request";
            $message = "A new item request has been made by user '{$borrowerName}'.";
            sendEmail($admin['email'], $subject, $message);
        }

        header('Location: inventory.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$itemId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$itemId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
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
                        <h3>Request Item</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <div class="mb-3">
                                <label>Item Name</label>
                                <input type="text" class="form-control" value="<?= $item['item_name'] ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Available Quantity</label>
                                <input type="number" class="form-control" value="<?= $item['quantity'] ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Request Quantity</label>
                                <input type="number" name="quantity" class="form-control" required min="1"
                                    max="<?= $item['quantity'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Comment (Optional)</label>
                                <textarea name="comment" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>
</body>

</html>