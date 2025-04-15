<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'vendor/autoload.php'; // Ensure Composer's autoloader is included

use setasign\Fpdi\Fpdi; // Use the correct namespace for FPDI

// Fetch all usernames for the dropdown
$usernames = [];
$userStmt = $pdo->query("SELECT DISTINCT username FROM users ORDER BY username ASC");
$usernames = $userStmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['report_type'];
    $username = $_POST['username'] ?? null; // Optional filter for username
    $stockLevel = $_POST['stock_level'] ?? null; // Optional filter for stock levels

    // Build SQL query based on filters
    $sql = match ($reportType) {
        'inventory' => "SELECT * FROM inventory WHERE (:stockLevel IS NULL OR quantity <= :stockLevel)",
        'loan_records' => "SELECT lr.*, u.username FROM loan_records lr JOIN users u ON lr.borrower_id = u.id WHERE (:username IS NULL OR u.username = :username)",
        default => null,
    };

    if ($sql) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':stockLevel' => $stockLevel,
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate PDF using FPDI
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, ucfirst($reportType) . ' Report', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);

        if (!empty($data)) {
            foreach ($data as $row) {
                foreach ($row as $key => $value) {
                    $pdf->Cell(50, 10, ucfirst($key) . ':', 0, 0);
                    $pdf->Cell(0, 10, $value, 0, 1);
                }
                $pdf->Ln(5);
            }
        } else {
            $pdf->Cell(0, 10, 'No data available for the selected filters.', 0, 1, 'C');
        }

        $pdf->Output('D', "$reportType-report.pdf");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'partials/sidenav.php'; ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Generate Reports</h1>
                <form method="POST" class="p-4 border rounded">
                    <div class="mb-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select id="report_type" name="report_type" class="form-select" required
                            onchange="toggleFilters()">
                            <option value="inventory">Inventory</option>
                            <option value="loan_records">Loan Records</option>
                        </select>
                    </div>
                    <div class="mb-3" id="username_filter" style="display: none;">
                        <label for="username" class="form-label">Username (for Loan Records)</label>
                        <select id="username" name="username" class="form-select">
                            <option value="">Select Username</option>
                            <?php foreach ($usernames as $user): ?>
                                <option value="<?= htmlspecialchars($user) ?>"><?= htmlspecialchars($user) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="stock_level_filter">
                        <label for="stock_level" class="form-label">Stock Level (for Inventory)</label>
                        <input type="number" id="stock_level" name="stock_level" class="form-control"
                            placeholder="Enter maximum stock level">
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'partials/footer.php'; ?>

    <script>
        function toggleFilters() {
            const reportType = document.getElementById('report_type').value;
            const usernameFilter = document.getElementById('username_filter');
            const stockLevelFilter = document.getElementById('stock_level_filter');

            if (reportType === 'loan_records') {
                usernameFilter.style.display = 'block';
                stockLevelFilter.style.display = 'none';
            } else if (reportType === 'inventory') {
                usernameFilter.style.display = 'none';
                stockLevelFilter.style.display = 'block';
            }
        }

        // Initialize the filters based on the default selected report type
        document.addEventListener('DOMContentLoaded', toggleFilters);
    </script>
</body>

</html>