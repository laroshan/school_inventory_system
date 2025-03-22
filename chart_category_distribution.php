<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Category-wise Inventory Distribution";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Category-wise Inventory Distribution</h1>
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('categoryDistributionChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Electronics", "Furniture", "Stationery"], // Example categories
                    datasets: [{
                        data: [40, 30, 30], // Example data
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        title: { display: true, text: 'Category-wise Inventory Distribution' }
                    }
                }
            });
        });
    </script>
</body>

</html>