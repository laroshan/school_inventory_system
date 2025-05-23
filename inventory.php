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
                                <h3 class="card-title mb-0 flex-grow-1">Inventory</h3>
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
            const container = document.getElementById("table-gridjs");
            container.innerHTML = ""; // Ensure the container is empty

            // Create a standalone category filter dropdown
            const categoryFilterContainer = document.createElement('div');
            categoryFilterContainer.className = 'mb-3'; // Add margin for spacing
            categoryFilterContainer.innerHTML = `
                <label for="categoryFilter" class="form-label">Filter by Category</label>
                <select id="categoryFilter" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                </select>
            `;
            container.parentElement.insertBefore(categoryFilterContainer, container); // Insert above the table

            const categoryFilter = document.getElementById('categoryFilter');

            // Fetch categories and populate the dropdown
            fetch('categories_data.php')
                .then(response => response.json())
                .then(categories => {
                    categoryFilter.innerHTML += categories
                        .map(category => `<option value="${category}">${category}</option>`)
                        .join('');
                });

            // Add event listener to filter by category
            categoryFilter.addEventListener('change', function () {
                const selectedCategory = this.value;
                grid.updateConfig({
                    server: {
                        url: 'inventory_data.php',
                        then: data => data
                            .filter(item => !selectedCategory || item.category === selectedCategory)
                            .map(item => [
                                item.id,
                                item.item_name,
                                item.category,
                                item.item_description,
                                item.quantity,
                                `$${item.unit_price}`,
                                `$${item.amount}`,
                                item.status,
                                item.inventory_date,
                                item.is_serialized // Add is_serialized field
                            ])
                    }
                }).forceRender();
            });

            const grid = new gridjs.Grid({
                columns: [
                    "ID",
                    "Item Name",
                    "Category",
                    "Description",
                    "Quantity",
                    "Unit Price",
                    "Amount",
                    "Status",
                    "Inventory Date",
                    {
                        name: "Serialized",
                        formatter: (cell, row) => row.cells[9].data === 1 ? "Serialized" : "Non-Serialized" // Check is_serialized value
                    },
                    {
                        name: "Actions",
                        formatter: (cell, row) => {
                            const id = row.cells[0].data; // Get ID from the first column
                            const quantity = row.cells[4].data; // Get item quantity
                            const role = "<?= $_SESSION['role'] ?>"; // Get user role from session

                            if (role === 'admin') {
                                return gridjs.html(`
                                    <div class="d-flex gap-2 action-buttons">
                                        <a href="edit_inventory.php?edit_id=${id}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="lending_page.php?id=${id}" class="btn btn-sm btn-success ${quantity == 0 ? 'disabled' : ''}">
                                            <i class="fas fa-hand-holding"></i> Lend
                                        </a>
                                        <a href="delete_inventory.php?delete_id=${id}" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                `);
                            } else {
                                return gridjs.html(`
                                    <div class="d-flex gap-2 action-buttons">
                                        <a href="request_item.php?id=${id}" class="btn btn-sm btn-warning ${quantity == 0 ? 'disabled' : ''}">
                                            <i class="fas fa-paper-plane"></i> Request
                                        </a>
                                    </div>
                                `);
                            }
                        }
                    }
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
                        item.inventory_date,
                        item.is_serialized // Add is_serialized field
                    ])
                },
                search: { enabled: true }, // Enable search
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
                },
                html: {
                    search: `
                        <div class="d-flex align-items-center">
                            <input type="text" class="gridjs-search-input form-control" placeholder="Search inventory...">
                        </div>
                    `
                }
            });

            // Render the grid
            grid.render(container);
        });
    </script>
</body>

</html>