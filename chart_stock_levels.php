<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Stock Levels Over Time";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Stock Levels Over Time</h1>
                <canvas id="stockLevelsChart"></canvas>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('stockLevelsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["January", "February", "March", "April", "May"], // Example months
                    datasets: [{
                        label: 'Stock Levels',
                        data: [120, 100, 140, 130, 150], // Example data
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        title: { display: true, text: 'Stock Levels Over Time' }
                    }
                }
            });
        });
    </script>
</body>

</html>