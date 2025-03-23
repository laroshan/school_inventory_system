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
                    "Returned Date", // New column for returned date
                    "Status",
                    "Comments", // Ensure comments column is included
                    {
                        name: "Actions",
                        formatter: (cell, row) => {
                            const status = row.cells[8].data; // Adjusted index for "Status" column
                            const id = row.cells[0].data; // Get ID from the first column

                            if (status === 'requested') {
                                return gridjs.html(`
                                    <form method="POST" action="process_request.php" class="d-flex gap-2" onsubmit="return validateApproveForm(this)">
                                        <input type="hidden" name="request_id" value="${id}">
                                        <input type="date" name="due_date" class="form-control form-control-sm" min="${new Date().toISOString().split('T')[0]}">
                                        <textarea name="comment" class="form-control form-control-sm" placeholder="Optional comment"></textarea>
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                `);
                            } else {
                                return gridjs.html(`
                                    <form method="POST" action="update_record.php" class="d-flex gap-2">
                                        <input type="hidden" name="record_id" value="${id}">
                                        <input type="date" name="due_date" class="form-control form-control-sm" value="${row.cells[6].data || ''}" min="${new Date().toISOString().split('T')[0]}">
                                        <textarea name="comment" class="form-control form-control-sm" placeholder="Optional comment">${row.cells[9]?.data || ''}</textarea>
                                        <button type="submit" name="action" value="update" class="btn btn-sm btn-primary" disabled>Update</button>
                                    </form>
                                `);
                            }
                        }
                    },
                    {
                        name: "Mark as Returned",
                        formatter: (cell, row) => {
                            const status = row.cells[8].data; // Adjusted index for "Status" column
                            const id = row.cells[0].data; // Get ID from the first column

                            if (status === 'borrowed') {
                                return gridjs.html(`
                                    <form method="POST" action="update_record.php" class="d-flex gap-2">
                                        <input type="hidden" name="record_id" value="${id}">
                                        <button type="submit" name="action" value="mark_returned" class="btn btn-sm btn-warning">Mark as Returned</button>
                                    </form>
                                `);
                            }
                            return '';
                        }
                    }
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
                        item.returned_date, // Map returned date to the new column
                        item.status,
                        item.comments // Ensure comments are mapped correctly
                    ])
                },
                search: { enabled: true }, // Enable search
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

            // Add event listeners for the update button after the table is rendered
            container.addEventListener('input', function (event) {
                const target = event.target;
                if (target.matches('input[name="due_date"], textarea[name="comment"]')) {
                    const form = target.closest('form');
                    const updateButton = form.querySelector('button[name="action"][value="update"]');
                    if (updateButton) {
                        updateButton.disabled = false;
                    }
                }
            });
        });

        // Client-side validation for the approve form
        function validateApproveForm(form) {
            const dueDate = form.querySelector('input[name="due_date"]').value;
            if (!dueDate) {
                alert("Please select a due date before approving the request.");
                return false; // Prevent form submission
            }
            const today = new Date().toISOString().split('T')[0];
            if (dueDate < today) {
                alert("Due date cannot be in the past.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</body>

</html>
<?php
// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
require_once 'includes/db_connect.php';

// Set the content type to JSON
if (!headers_sent()) { // Ensure headers are not already sent
    header('Content-Type: application/json');
}

try {
    // Fetch lended items data
    $sql = "SELECT lr.id, i.item_name, i.category, u.username AS borrower, lr.quantity_borrowed, lr.lending_date, lr.due_date, lr.returned_date, lr.status, lr.comments 
            FROM loan_records lr 
            JOIN inventory i ON lr.item_id = i.id 
            JOIN users u ON lr.borrower_id = u.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Handle other errors
    echo json_encode([
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
?>
<?php
// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];
    $dueDate = $_POST['due_date'] ?? null;
    $comment = $_POST['comment'] ?? '';

    try {
        if ($action === 'approve') {
            if (empty($dueDate)) {
                echo "Error: Due date is required to approve the request.";
                exit();
            }

            // Fetch the lending date for validation
            $fetchLendingDate = "SELECT lending_date FROM loan_records WHERE id = :requestId";
            $stmt = $pdo->prepare($fetchLendingDate);
            $stmt->execute([':requestId' => $requestId]);
            $lendingDate = $stmt->fetchColumn();

            if ($dueDate < $lendingDate) {
                echo "Error: Due date cannot be earlier than the lending date.";
                exit();
            }

            // Approve the request
            $sql = "UPDATE loan_records 
                    SET status = 'borrowed', due_date = :dueDate, comments = :comment 
                    WHERE id = :requestId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dueDate' => $dueDate,
                ':comment' => $comment,
                ':requestId' => $requestId
            ]);

            // Update inventory quantity
            $updateInventory = "UPDATE inventory i 
                                JOIN loan_records lr ON i.id = lr.item_id 
                                SET i.quantity = i.quantity - lr.quantity_borrowed 
                                WHERE lr.id = :requestId";
            $stmt = $pdo->prepare($updateInventory);
            $stmt->execute([':requestId' => $requestId]);
        } elseif ($action === 'reject') {
            // Reject the request
            $sql = "UPDATE loan_records 
                    SET status = 'rejected', comments = :comment 
                    WHERE id = :requestId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':comment' => $comment,
                ':requestId' => $requestId
            ]);
        }

        header('Location: lended_item_list.php');
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>