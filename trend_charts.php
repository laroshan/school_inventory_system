<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Trend Charts";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Trend Charts</h1>
                <ul class="list-group">
                    <li class="list-group-item"><a href="chart_stock_levels.php">Stock Levels Over Time (Line Chart)</a>
                    </li>
                    <li class="list-group-item"><a href="chart_category_distribution.php">Category-wise Inventory
                            Distribution (Pie Chart)</a></li>
                    <li class="list-group-item"><a href="chart_monthly_borrowing.php">Monthly Borrowing Trends (Bar
                            Chart)</a></li>
                    <li class="list-group-item"><a href="chart_top_borrowed.php">Top Borrowed Items (Bar Chart)</a></li>
                    <li class="list-group-item"><a href="chart_loan_vs_available.php">Loan vs. Available Inventory
                            (Stacked Bar Chart)</a></li>
                    <li class="list-group-item"><a href="chart_due_vs_overdue.php">Due vs. Overdue Loans (Doughnut
                            Chart)</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>
</body>

</html>