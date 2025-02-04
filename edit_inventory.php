<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Fetch item details for editing
$item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM inventory WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle update action
if (isset($_POST['updateInventory'])) {
    $id = $_POST['id'];
    $itemName = $_POST['itemName'];
    $category = $_POST['category'];
    $itemDescription = $_POST['itemDescription'];
    $quantity = $_POST['quantity'];
    $unitPrice = $_POST['unitPrice'];
    $amount = $quantity * $unitPrice;
    $status = $_POST['status'];
    $inventoryDate = $_POST['inventoryDate'];

    $sql = "UPDATE inventory 
            SET item_name = :itemName, category = :category, item_description = :itemDescription, 
                quantity = :quantity, unit_price = :unitPrice, amount = :amount, 
                status = :status, inventory_date = :inventoryDate 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':itemName' => $itemName,
        ':category' => $category,
        ':itemDescription' => $itemDescription,
        ':quantity' => $quantity,
        ':unitPrice' => $unitPrice,
        ':amount' => $amount,
        ':status' => $status,
        ':inventoryDate' => $inventoryDate,
        ':id' => $id
    ]);

    echo "<script>alert('Item updated successfully!'); window.location.href = 'inventory.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include('partials/sidenav.php'); ?>

        <div class="page-content">
            <div class="page-container">
                <div class="row">
                    <div class="col-12">
                        <div class="card position-relative">
                            <form action="" method="POST" onsubmit="return validateForm()">
                                <div class="card-body">
                                    <h3 class="card-title mb-0 flex-grow-1">Edit Inventory</h3>
                                    <br />

                                    <input type="hidden" name="id" value="<?= $item['id'] ?? '' ?>">

                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-sm-0 mt-3">
                                            <div class="mb-2">
                                                <label class="form-label">Inventory Date:</label>
                                                <input type="text" id="inventoryDate" name="inventoryDate"
                                                    class="form-control datepicker" placeholder="Select Date"
                                                    value="<?= $item['inventory_date'] ?? '' ?>" required>
                                            </div>

                                            <div class="mb-2">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="In Stock" <?= ($item['status'] ?? '') === 'In Stock' ? 'selected' : '' ?>>In Stock</option>
                                                    <option value="Out of Stock" <?= ($item['status'] ?? '') === 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="category" class="form-label">Category</label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="Electronics" <?= ($item['category'] ?? '') === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                                                    <option value="Furniture" <?= ($item['category'] ?? '') === 'Furniture' ? 'selected' : '' ?>>Furniture</option>
                                                    <option value="Stationery" <?= ($item['category'] ?? '') === 'Stationery' ? 'selected' : '' ?>>Stationery</option>
                                                    <option value="Tools" <?= ($item['category'] ?? '') === 'Tools' ? 'selected' : '' ?>>Tools</option>
                                                    <option value="Other" <?= ($item['category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="mb-3">
                                            <label for="itemName" class="form-label">Item Name</label>
                                            <input type="text" id="itemName" name="itemName" class="form-control"
                                                value="<?= $item['item_name'] ?? '' ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="itemDescription" class="form-label">Description</label>
                                            <input type="text" id="itemDescription" name="itemDescription"
                                                class="form-control" value="<?= $item['item_description'] ?? '' ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" id="quantity" name="quantity" class="form-control"
                                                value="<?= $item['quantity'] ?? '' ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="unitPrice" class="form-label">Unit Price</label>
                                            <input type="number" id="unitPrice" name="unitPrice" class="form-control"
                                                value="<?= $item['unit_price'] ?? '' ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" name="updateInventory" class="btn btn-success gap-1">
                                            <i class="ti ti-device-floppy fs-16"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('partials/footer.php'); ?>
    </div>

    <!-- Flatpickr JS for date picker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for date fields
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", // Format for database
            defaultDate: "today", // Set default date to today
        });
    </script>
</body>

</html>