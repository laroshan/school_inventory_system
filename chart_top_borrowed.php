<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Top Borrowed Items";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Top Borrowed Items</h1>
                <div class="chart-container" style="position: relative; height: 50vh; width: 80vw;">
                    <canvas id="topBorrowedChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('topBorrowedChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Item A", "Item B", "Item C", "Item D", "Item E"], // Example items
                    datasets: [{
                        label: 'Times Borrowed',
                        data: [120, 100, 90, 80, 70], // Example data
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        title: { display: true, text: 'Top Borrowed Items' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>

</html>