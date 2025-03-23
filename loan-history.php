<?php
session_start();
require_once 'includes/db_connect.php';

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

$sql = "SELECT lr.id, i.item_name, lr.quantity_borrowed, lr.lending_date, lr.due_date, lr.returned_date, lr.status, lr.comments 
        FROM loan_records lr 
        JOIN inventory i ON lr.item_id = i.id 
        WHERE lr.borrower_id = :userId 
        ORDER BY lr.lending_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':userId' => $userId]);
$loanRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'partials/sidenav.php'; ?>
        <div class="page-content">
            <div class="container">
                <h3>Loan History</h3>
                <div id="loan-history-grid"></div>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>

    <!-- Include Grid.js -->
    <script src="assets/vendor/gridjs/gridjs.umd.js"></script>
    <link rel="stylesheet" href="assets/vendor/gridjs/gridjs.css">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById("loan-history-grid");
            container.innerHTML = ""; // Ensure the container is empty

            new gridjs.Grid({
                columns: [
                    "Item Name",
                    "Quantity",
                    "Lending Date",
                    "Due Date",
                    "Returned Date",
                    "Status",
                    "Comments"
                ],
                data: <?= json_encode(array_map(function ($record) {
                    return [
                        htmlspecialchars($record['item_name']),
                        htmlspecialchars($record['quantity_borrowed']),
                        htmlspecialchars($record['lending_date']),
                        htmlspecialchars($record['due_date']),
                        htmlspecialchars($record['returned_date'] ?? 'N/A'),
                        htmlspecialchars($record['status']),
                        htmlspecialchars($record['comments'] ?? 'N/A')
                    ];
                }, $loanRecords)) ?>,
                search: { enabled: true }, // Enable search
                pagination: { limit: 10 }, // Enable pagination with 10 rows per page
                sort: true, // Enable sorting
                resizable: true,
                language: {
                    'search': {
                        'placeholder': 'Search loan history...'
                    },
                    'pagination': {
                        'previous': 'Previous',
                        'next': 'Next',
                        'showing': 'Showing',
                        'to': 'to',
                        'of': 'of',
                        'results': 'results'
                    }
                }
            }).render(container); // Render the grid in the container
        });
    </script>
</body>

</html>