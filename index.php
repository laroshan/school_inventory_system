<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Fetch item counts dynamically
$total_items = $pdo->query("SELECT COUNT(*) FROM inventory")->fetchColumn();
$borrowed_items = $pdo->query("SELECT COUNT(*) FROM inventory WHERE status = 'borrowed'")->fetchColumn();
$out_of_stock = $pdo->query("SELECT COUNT(*) FROM inventory WHERE quantity = 0")->fetchColumn();
$low_stock = $pdo->query("SELECT COUNT(*) FROM inventory WHERE quantity < 5 AND quantity > 0")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body class="bg-light">
    <div class="wrapper">
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center text-3xl font-bold p-6 text-dark">School Inventory Dashboard</h1>

                <!-- Dashboard Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <div class="card dashboard-card border-0 shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title">Total Items</h4>
                        </div>
                        <div class="card-body text-center">
                            <i class="ri-box-3-line text-primary icon-lg"></i>
                            <h2 class="display-4 font-weight-bold"><?= $total_items ?></h2>
                        </div>
                    </div>

                    <div class="card dashboard-card border-0 shadow-lg">
                        <div class="card-header bg-success text-white">
                            <h4 class="card-title">Borrowed Items</h4>
                        </div>
                        <div class="card-body text-center">
                            <i class="ri-bookmark-line text-success icon-lg"></i>
                            <h2 class="display-4 font-weight-bold"><?= $borrowed_items ?></h2>
                        </div>
                    </div>

                    <div class="card dashboard-card border-0 shadow-lg">
                        <div class="card-header bg-danger text-white">
                            <h4 class="card-title">Out of Stock</h4>
                        </div>
                        <div class="card-body text-center">
                            <i class="ri-alert-line text-danger icon-lg"></i>
                            <h2 class="display-4 font-weight-bold"><?= $out_of_stock ?></h2>
                        </div>
                    </div>

                    <div class="card dashboard-card border-0 shadow-lg">
                        <div class="card-header bg-warning text-dark">
                            <h4 class="card-title">Low Stock Items</h4>
                        </div>
                        <div class="card-body text-center">
                            <i class="ri-error-warning-line text-warning icon-lg"></i>
                            <h2 class="display-4 font-weight-bold"><?= $low_stock ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('partials/footer.php'); ?>
    </div>


</body>
</html>
