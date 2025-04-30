<?php
include 'header.php';
require_once 'includes/db_connect.php';

// Fetch item details
$itemIds = isset($_GET['id']) ? [$_GET['id']] : explode(',', $_GET['ids']);
$items = [];
if (!empty($itemIds)) {
    $query = "SELECT * FROM inventory WHERE id IN (" . implode(',', array_map('intval', $itemIds)) . ")";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch serialized item details
$serializedItems = [];
if (!empty($itemIds)) {
    $serializedQuery = "SELECT ii.id, ii.serial_number, ii.inventory_id 
                        FROM inventory_items ii 
                        WHERE ii.inventory_id IN (" . implode(',', array_map('intval', $itemIds)) . ") 
                        AND ii.status = 'available'";
    $stmt = $pdo->prepare($serializedQuery);
    $stmt->execute();
    $serializedItems = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
}

// Fetch borrower list
$borrowerQuery = "SELECT id, username FROM users";
$stmt = $pdo->prepare($borrowerQuery);
$stmt->execute();
$borrowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="wrapper">
        <?php
        $title = "Lend Items";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form action="process_lending.php" method="POST" onsubmit="return validateForm()">
                                <div class="card-body">
                                    <h3 class="card-title">Lend Items</h3>
                                    <br />

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Borrower:</label>
                                            <select name="borrower_id" class="form-control" required>
                                                <option value="">Select Borrower</option>
                                                <?php foreach ($borrowers as $borrower) { ?>
                                                    <option value="<?= htmlspecialchars($borrower['id']) ?>">
                                                        <?= htmlspecialchars($borrower['username']) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Lending Date:</label>
                                            <input type="date" name="lending_date" id="lending_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Due Date:</label>
                                            <input type="date" name="due_date" id="due_date" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <h4 class="mt-4">Item Details</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $item) { ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($item['item_name']) ?>
                                                        <input type="hidden" name="item_ids[]"
                                                            value="<?= htmlspecialchars($item['id']) ?>">
                                                    </td>
                                                    <td>
                                                        <?php if ($item['is_serialized']) { ?>
                                                            <select name="serial_numbers[<?= htmlspecialchars($item['id']) ?>]"
                                                                class="form-control" required>
                                                                <option value="">Select Serial Number</option>
                                                                <?php if (isset($serializedItems[$item['id']])) {
                                                                    foreach ($serializedItems[$item['id']] as $serial) { ?>
                                                                        <option value="<?= htmlspecialchars($serial['id']) ?>">
                                                                            <?= htmlspecialchars($serial['serial_number']) ?>
                                                                        </option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <input type="number"
                                                                name="quantity[<?= htmlspecialchars($item['id']) ?>]"
                                                                class="form-control" min="1"
                                                                max="<?= htmlspecialchars($item['quantity']) ?>" required>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-4 text-center">
                                        <button type="submit" class="btn btn-success">Confirm Lending</button>
                                    </div>
                                </div>
                            </form>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        function validateForm() {
            const lendingDate = document.getElementById('lending_date').value;
            const dueDate = document.getElementById('due_date').value;

            if (new Date(dueDate) < new Date(lendingDate)) {
                alert('Due date cannot be before the lending date.');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>