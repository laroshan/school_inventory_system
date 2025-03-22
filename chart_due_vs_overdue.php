<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Due vs. Overdue Loans";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Due vs. Overdue Loans</h1>
                <div class="chart-container" style="position: relative; height: 50vh; width: 80vw;">
                    <canvas id="dueVsOverdueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch('chart_data_due_vs_overdue.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('dueVsOverdueChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels, // Due and Overdue
                            datasets: [{
                                data: data.values, // Counts
                                backgroundColor: ['#36A2EB', '#FF6384']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true },
                                title: { display: true, text: 'Due vs. Overdue Loans' }
                            }
                        }
                    });
                });
        });
    </script>
</body>

</html>