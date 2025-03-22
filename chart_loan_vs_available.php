<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Loan vs. Available Inventory";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Loan vs. Available Inventory</h1>
                <div class="chart-container" style="position: relative; height: 50vh; width: 80vw;">
                    <canvas id="loanVsAvailableChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('loanVsAvailableChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Category A", "Category B", "Category C"], // Example categories
                    datasets: [
                        {
                            label: 'Borrowed',
                            data: [30, 50, 40], // Example data
                            backgroundColor: 'rgba(255, 159, 64, 0.7)'
                        },
                        {
                            label: 'Available',
                            data: [70, 50, 60], // Example data
                            backgroundColor: 'rgba(75, 192, 192, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        title: { display: true, text: 'Loan vs. Available Inventory' }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>

</html>