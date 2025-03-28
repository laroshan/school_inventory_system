<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Monthly Borrowing Trends";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Monthly Borrowing Trends</h1>
                <div class="chart-container" style="position: relative; height: 50vh; width: 80vw;">
                    <canvas id="monthlyBorrowingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('chart_data_monthly_borrowing.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('monthlyBorrowingChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels, // Months
                            datasets: [{
                                label: 'Items Borrowed',
                                data: data.values, // Borrowed quantities
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true },
                                title: { display: true, text: 'Monthly Borrowing Trends' }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
        });
    </script>
</body>

</html>