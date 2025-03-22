<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php
        $title = "User Details";
        include('partials/sidenav.php');
        ?>
        <div class="page-content">
            <div class="page-container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom border-dashed">
                                <h3 class="card-title mb-0 flex-grow-1">User Details</h3>
                            </div>
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
                    "Username",
                    "Email",
                    "Role",
                    "Created At",
                    {
                        name: "Actions",
                        formatter: (cell, row) => {
                            const id = row.cells[0].data; // Get ID from the first column
                            return gridjs.html(`
                                <div class="d-flex gap-2 action-buttons">
                                    <a href="edit_user.php?edit_id=${id}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_user.php?delete_id=${id}" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            `);
                        }
                    }
                ],
                server: {
                    url: 'user_data.php',
                    then: data => data.map(user => [
                        user.id,
                        user.username,
                        user.email,
                        user.role,
                        user.created_at
                    ])
                },
                search: { enabled: true }, // 🔍 Enable search
                pagination: { limit: 10 }, // Enable pagination with 10 rows per page
                sort: true, // Enable sorting
                resizable: true,
                language: {
                    'search': {
                        'placeholder': 'Search users...'
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