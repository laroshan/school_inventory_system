<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        <?php
        $title = "Inventory Management System";
        include('partials/sidenav.php');
        ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">
            <div class="page-container">
                <div class="row">
                    <div class="col-12">
                        <div class="card position-relative">
                            <form action="" method="POST" onsubmit="return validateForm()">
                                <div class="card-body">

                                    <h3 class="card-title mb-0 flex-grow-1"> Add to Inventory </h4>
                                        <br />
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-sm-0 mt-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Inventory Date :</label>
                                                    <input type="text" id="inventoryDate" name="inventoryDate"
                                                        class="form-control datepicker" placeholder="Select Date"
                                                        required>
                                                </div>

                                                <div>
                                                    <label for="category" class="form-label">Category</label>
                                                    <select class="form-select" id="category" name="category" required>
                                                        <option value="">Select Category</option>
                                                        <option value="Electronics">Electronics</option>
                                                        <option value="Furniture">Furniture</option>
                                                        <option value="Stationery">Stationery</option>
                                                        <option value="Tools">Tools</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="isSerialized" class="form-label">Is Serialized?</label>
                                                    <select class="form-select" id="isSerialized" name="isSerialized"
                                                        required>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="table-responsive">
                                                <table class="table text-center table-nowrap align-middle mb-0"
                                                    id="itemTable">
                                                    <thead>
                                                        <tr class="bg-light bg-opacity-50">
                                                            <th scope="col" class="border-0" style="width: 70px;">#</th>
                                                            <th scope="col" class="border-0 text-start">Item Name</th>
                                                            <th scope="col" class="border-0">Description</th>
                                                            <th scope="col" class="border-0" id="quantityOrSerialHeader"
                                                                style="width: 140px;">Quantity</th>
                                                            <th scope="col" class="border-0" style="width: 140px;">Unit
                                                                Price</th>
                                                            <th scope="col" class="border-0" style="width: 240px">Amount
                                                            </th>
                                                            <th scope="col" class="border-0" style="width: 50px;">•••
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itemTableBody">
                                                        <!-- Item rows will be dynamically added here -->
                                                    </tbody>
                                                </table><!--end table-->

                                                <div class="p-2">
                                                    <button type="button" class="btn btn-primary" id="addItemBtn"><i
                                                            class="ti ti-circle-plus me-1"></i> Add Items</button>
                                                </div>
                                            </div>
                                        </div>
                                </div> <!-- end card-body-->
                                <div class="mb-5">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:window.print()" class="btn btn-primary gap-1"><i
                                                class="ti ti-eye fs-16"></i> Preview</a>
                                        <button type="submit" name="saveInventory" class="btn btn-success gap-1"><i
                                                class="ti ti-device-floppy fs-16"></i> Save</button>
                                    </div>
                                </div>
                            </form>
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

    <!-- Flatpickr JS for date picker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for date fields
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d", // Format for database
            defaultDate: "today", // Set default date to today
        });

        // JavaScript for Dynamic Item Management
        document.addEventListener('DOMContentLoaded', function () {
            const itemTableBody = document.getElementById('itemTableBody');
            const addItemBtn = document.getElementById('addItemBtn');
            const isSerializedSelect = document.getElementById('isSerialized');
            const quantityOrSerialHeader = document.getElementById('quantityOrSerialHeader');
            let itemCounter = 1;

            // Update table header based on "Is Serialized" selection
            isSerializedSelect.addEventListener('change', function () {
                const isSerialized = this.value === '1';
                quantityOrSerialHeader.textContent = isSerialized ? 'Serial Number' : 'Quantity';

                // Clear existing rows when toggling between serialized and non-serialized
                const rows = itemTableBody.querySelectorAll('tr');
                rows.forEach(row => row.remove());
                itemCounter = 1; // Reset counter
            });

            // Function to add a new item row
            addItemBtn.addEventListener('click', function () {
                const isSerialized = isSerializedSelect.value === '1';
                const newRow = document.createElement('tr');
                newRow.classList.add('item-row');
                newRow.innerHTML = `
                    <th scope="row">${itemCounter}</th>
                    <td class="text-start">
                        <input type="text" name="itemName[]" class="form-control mb-1" placeholder="Item Name" required>
                    </td>
                    <td class="text-start">
                        <input type="text" name="itemDescription[]" class="form-control" placeholder="Item Description">
                    </td>
                    <td>
                        ${isSerialized ? `
                            <input type="text" name="serialNumber[]" class="form-control serial-number" placeholder="Serial Number" required>
                            <input type="hidden" name="quantity[]" value="1">
                        ` : `
                            <input type="number" name="quantity[]" class="form-control quantity" placeholder="Quantity" required>
                        `}
                    </td>
                    <td>
                        <input type="number" name="unitPrice[]" class="form-control unit-price" placeholder="Price" required>
                    </td>
                    <td>
                        <input type="number" name="amount[]" class="form-control amount" placeholder="$0.00" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn flex-shrink-0 rounded-circle btn-icon btn-ghost-danger removeItemBtn">
                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-20"></iconify-icon>
                        </button>
                    </td>
                `;
                itemTableBody.appendChild(newRow);
                itemCounter++;

                // Add event listeners for auto-calculation
                const quantityInput = newRow.querySelector('.quantity');
                const unitPriceInput = newRow.querySelector('.unit-price');
                const amountInput = newRow.querySelector('.amount');

                if (!isSerialized) {
                    quantityInput.addEventListener('input', calculateAmount);
                }
                unitPriceInput.addEventListener('input', calculateAmount);

                function calculateAmount() {
                    const quantity = parseFloat(quantityInput?.value || 1); // Default to 1 for serialized items
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const amount = quantity * unitPrice;
                    amountInput.value = amount.toFixed(2); // Format to 2 decimal places
                }
            });

            // Function to remove an item row
            itemTableBody.addEventListener('click', function (e) {
                if (e.target.closest('.removeItemBtn')) {
                    e.target.closest('tr').remove();
                    updateRowNumbers();
                }
            });

            // Function to update row numbers
            function updateRowNumbers() {
                const rows = itemTableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    row.querySelector('th').textContent = index + 1;
                });
            }
        });

        // Function to validate the form before submission
        function validateForm() {
            const itemRows = document.querySelectorAll('#itemTableBody tr');
            if (itemRows.length === 0) {
                alert("Please add at least one item before submitting the form.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        document.getElementById('isSerialized').addEventListener('change', function () {
            const serialNumbersSection = document.getElementById('serialNumbersSection');
            if (this.value === '1') {
                serialNumbersSection.style.display = 'block';
            } else {
                serialNumbersSection.style.display = 'none';
            }
        });
    </script>
</body>

</html>

<?php
// Database connection
require_once 'includes/db_connect.php';

// Save to database logic
if (isset($_POST['saveInventory'])) {
    $inventoryDate = $_POST['inventoryDate'];
    $category = $_POST['category'];
    $isSerialized = $_POST['isSerialized'];
    $itemNames = $_POST['itemName'];
    $itemDescriptions = $_POST['itemDescription'];
    $quantities = $_POST['quantity'];
    $unitPrices = $_POST['unitPrice'];
    $amounts = $_POST['amount'];
    $serialNumbers = isset($_POST['serialNumber']) ? $_POST['serialNumber'] : [];

    $sql = "INSERT INTO inventory (item_name, category, item_description, quantity, unit_price, amount, status, inventory_date, is_serialized) 
            VALUES (:itemName, :category, :itemDescription, :quantity, :unitPrice, :amount, :status, :inventoryDate, :isSerialized)";
    $stmt = $pdo->prepare($sql);

    for ($i = 0; $i < count($itemNames); $i++) {
        $status = ($quantities[$i] > 0) ? 'In Stock' : 'Out of Stock';
        $stmt->execute([
            ':itemName' => $itemNames[$i],
            ':category' => $category,
            ':itemDescription' => $itemDescriptions[$i],
            ':quantity' => $quantities[$i],
            ':unitPrice' => $unitPrices[$i],
            ':amount' => $amounts[$i],
            ':status' => $status,
            ':inventoryDate' => $inventoryDate,
            ':isSerialized' => $isSerialized
        ]);

        $inventoryId = $pdo->lastInsertId();

        if ($isSerialized == '1' && !empty($serialNumbers)) {
            $serialInsertSql = "INSERT INTO inventory_items (inventory_id, serial_number) VALUES (:inventoryId, :serialNumber)";
            $serialStmt = $pdo->prepare($serialInsertSql);

            foreach ($serialNumbers as $serialNumber) {
                $serialStmt->execute([
                    ':inventoryId' => $inventoryId,
                    ':serialNumber' => trim($serialNumber)
                ]);
            }
        }
    }
    echo "<script>alert('Inventory saved successfully!');</script>";
}
?>