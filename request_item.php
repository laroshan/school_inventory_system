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

$itemId = $_GET['id'] ?? null;
if (!$itemId) {
    echo "<script>alert('Invalid item ID.'); window.location.href = 'inventory.php';</script>";
    exit();
}

$query = "SELECT * FROM inventory WHERE id = :itemId";
$stmt = $pdo->prepare($query);
$stmt->execute([':itemId' => $itemId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "<script>alert('Item not found.'); window.location.href = 'inventory.php';</script>";
    exit();
}
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
                    <form method="POST" onsubmit="return validateForm()">
                        <div class="card-body">
                            <h3 class="card-title">Request Item</h3>
                            <br />

                            <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Item Name:</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($item['item_name']) ?>" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category:</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($item['category']) ?>" disabled>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Quantity:</label>
                                    <?php if ($item['is_serialized']) { ?>
                                        <input type="number" name="quantity" class="form-control" value="1" readonly>
                                        <small class="text-muted">Note: Only 1 quantity can be requested per request for
                                            serialized items.</small>
                                    <?php } else { ?>
                                        <input type="number" name="quantity" class="form-control" min="1"
                                            max="<?= htmlspecialchars($item['quantity']) ?>" required>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Comments (Optional):</label>
                                    <textarea name="comment" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-success">Submit Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>

    <script>
        function validateForm() {
            const quantityInput = document.querySelector('input[name="quantity"]');
            if (quantityInput && quantityInput.value <= 0) {
                alert('Quantity must be greater than 0.');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>