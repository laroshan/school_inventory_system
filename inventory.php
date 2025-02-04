<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "Inventory";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h3 class="card-title mb-0 flex-grow-1">Inventory</h4>
                            </div><!-- end card header -->
                            <div class="card-body">
                                <!-- Ensure the container is empty -->
                                <div id="table-gridjs"></div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
    <!-- END wrapper -->

    <?php include('partials/footer.php'); ?>

    <!-- gridjs js -->
    <script src="assets/vendor/gridjs/gridjs.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Clear the container before rendering
            const container = document.getElementById("table-gridjs");
            container.innerHTML = ""; // Ensure the container is empty

            new gridjs.Grid({
                columns: [
                    "ID",
                    "Item Name",
                    "Category",
                    "Description",
                    "Quantity",
                    "Unit Price",
                    "Amount",
                    "Status",
                    "Inventory Date"
                ],
                server: {
                    url: 'inventory_data.php',
                    then: data => data.map(item => [
                        item.id,
                        item.item_name,
                        item.category,
                        item.item_description,
                        item.quantity,
                        `$${item.unit_price}`,
                        `$${item.amount}`,
                        item.status,
                        item.inventory_date
                    ])
                },
                search: { enabled: true }, // 🔍 Enable search
                pagination: { limit: 10 }, // Enable pagination with 10 rows per page
                sort: true, // Enable sorting
                resizable: true,
                language: {
                    'search': {
                        'placeholder': 'Search inventory...'
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
            }).render(container); // Render the table in the cleared container
        });
    </script>
</body>

</html>