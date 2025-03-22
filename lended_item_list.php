<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">

            <div class="page-container">

                <div class="row">
                    <div class="col-12">
                        <div class="card position-relative">
                            <div class="card-body">
                                <h3 class="card-title mb-0 flex-grow-1">Lended Items List</h3>
                                <br />

                                <div class="table-responsive">
                                    <div id="table-gridjs"></div>
                                </div>
                            </div> <!-- end card-body-->
                        </div><!-- end card -->
                    </div>
                </div>

            </div> <!-- container -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?php include('partials/footer.php'); ?>

    <!-- gridjs js -->
    <script src="assets/vendor/gridjs/gridjs.umd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById("table-gridjs");
            container.innerHTML = ""; // Ensure the container is empty

            new gridjs.Grid({
                columns: [
                    "ID",
                    "Item Name",
                    "Category",
                    "Borrower",
                    "Quantity Borrowed",
                    "Lending Date",
                    "Due Date",
                    "Status"
                ],
                server: {
                    url: 'lended_item_data.php',
                    then: data => data.map(item => [
                        item.id,
                        item.item_name,
                        item.category,
                        item.borrower,
                        item.quantity_borrowed,
                        item.lending_date,
                        item.due_date,
                        item.status
                    ])
                },
                search: { enabled: true }, // 🔍 Enable search
                pagination: { limit: 10 }, // Enable pagination with 10 rows per page
                sort: true, // Enable sorting
                resizable: true,
                language: {
                    'search': {
                        'placeholder': 'Search lended items...'
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