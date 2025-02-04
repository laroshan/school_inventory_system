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
                            <form action="inventory_process.php" method="POST">
                                <div class="card-body">
                                    <h3 class="card-title mb-0 flex-grow-1">Borrow Items</h4>
                                        <br />

                                        <div class="row">
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-sm-0 mt-3">
                                                <div class="mb-2">
                                                    <label class="form-label">Inventory Date :</label>
                                                    <input type="text" name="inventoryDate" data-provider="flatpickr"
                                                        data-date-format="d M, Y" data-deafult-date="today"
                                                        class="form-control" placeholder="Select Date">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Due Date :</label>
                                                    <input type="text" name="dueDate" data-provider="flatpickr"
                                                        data-altFormat="F j, Y" class="form-control"
                                                        placeholder="Select Date">
                                                </div>

                                                <div class="mb-2">
                                                    <label for="InventoryStatus" class="form-label">Inventory
                                                        Status</label>
                                                    <select class="form-select" id="InventoryStatus"
                                                        name="InventoryStatus">
                                                        <option value="">Select Status</option>
                                                        <option value="In Stock">In Stock</option>
                                                        <option value="Out of Stock">Out of Stock</option>
                                                        <option value="Borrowed">Borrowed</option>
                                                        <option value="Returned">Returned</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="InventoryCategory" class="form-label">Category</label>
                                                    <select class="form-select" id="InventoryCategory"
                                                        name="InventoryCategory">
                                                        <option value="">Select Category</option>
                                                        <option value="Electronics">Electronics</option>
                                                        <option value="Furniture">Furniture</option>
                                                        <option value="Stationery">Stationery</option>
                                                        <option value="Tools">Tools</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="mb-4">
                                                    <label class="form-label">Supplier Details :</label>
                                                    <div class="mb-2 pb-1">
                                                        <input type="text" id="supplierName" name="supplierName"
                                                            class="form-control" placeholder="Supplier Name">
                                                    </div>
                                                    <div class="mb-2 pb-1">
                                                        <textarea type="text" id="supplierAddress"
                                                            name="supplierAddress" rows="3" class="form-control"
                                                            placeholder="Supplier Address"></textarea>
                                                    </div>
                                                    <div>
                                                        <input type="text" id="supplierNumber" name="supplierNumber"
                                                            class="form-control" placeholder="Supplier Phone Number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mt-sm-0 mt-3">
                                                <div class="mb-4">
                                                    <label class="form-label">Borrower Details :</label>
                                                    <div class="mb-2 pb-1">
                                                        <input type="text" id="borrowerName" name="borrowerName"
                                                            class="form-control" placeholder="Borrower Name">
                                                    </div>
                                                    <div class="mb-2 pb-1">
                                                        <textarea type="text" id="borrowerAddress"
                                                            name="borrowerAddress" rows="3" class="form-control"
                                                            placeholder="Borrower Address"></textarea>
                                                    </div>
                                                    <div>
                                                        <input type="text" id="borrowerNumber" name="borrowerNumber"
                                                            class="form-control" placeholder="Borrower Phone Number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="table-responsive">
                                                <table class="table text-center table-nowrap align-middle mb-0">
                                                    <thead>
                                                        <tr class="bg-light bg-opacity-50">
                                                            <th scope="col" class="border-0" style="width: 70px;">#</th>
                                                            <th scope="col" class="border-0 text-start">Item Details
                                                            </th>
                                                            <th scope="col" class="border-0" style="width: 140px">
                                                                Quantity
                                                            </th>
                                                            <th scope="col" class="border-0" style="width: 140px;">Unit
                                                                price</th>
                                                            <th scope="col" class="border-0" style="width: 240px">Amount
                                                            </th>
                                                            <th scope="col" class="border-0" style="width: 50px;">•••
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">01</th>
                                                            <td class="text-start">
                                                                <input type="text" id="item-detail-one"
                                                                    name="itemDetail[]" class="form-control mb-1"
                                                                    placeholder="Item One">
                                                                <input type="text" id="item-desc-one" name="itemDesc[]"
                                                                    class="form-control" placeholder="Item description">
                                                            </td>
                                                            <td>
                                                                <input type="number" id="item-quantity-one"
                                                                    name="itemQuantity[]" class="form-control"
                                                                    placeholder="Quantity">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="itemPrice[]"
                                                                    class="form-control" placeholder="Price">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="itemAmount[]"
                                                                    class="form-control  w-auto" placeholder="$0.00">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn flex-shrink-0 rounded-circle btn-icon btn-ghost-danger"><iconify-icon
                                                                        icon="solar:trash-bin-trash-bold-duotone"
                                                                        class="fs-20"></iconify-icon></button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">02</th>
                                                            <td class="text-start">
                                                                <input type="text" id="item-detail-two"
                                                                    name="itemDetail[]" class="form-control mb-1"
                                                                    placeholder="Item Two">
                                                                <input type="text" id="item-desc-two" name="itemDesc[]"
                                                                    class="form-control" placeholder="Item description">
                                                            </td>
                                                            <td>
                                                                <input type="number" id="item-quantity-two"
                                                                    name="itemQuantity[]" class="form-control"
                                                                    placeholder="Quantity">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="itemPrice[]"
                                                                    class="form-control" placeholder="Price">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="itemAmount[]"
                                                                    class="form-control  w-auto" placeholder="$0.00">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn flex-shrink-0 rounded-circle btn-icon btn-ghost-danger"><iconify-icon
                                                                        icon="solar:trash-bin-trash-bold-duotone"
                                                                        class="fs-20"></iconify-icon></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table><!--end table-->

                                                <div class="p-2">
                                                    <button type="button" class="btn btn-primary"><i
                                                            class="ti ti-circle-plus me-1"></i> Add Items</button>
                                                </div>
                                            </div>
                                            <div>
                                                <table
                                                    class="table table-sm table-borderless table-nowrap align-middle mb-0 ms-auto"
                                                    style="width:300px">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-medium">Subtotal</td>
                                                            <td class="text-end">
                                                                <div class="ms-auto" style="width: 160px;">
                                                                    <input type="number" id="itemSubtotal"
                                                                        name="itemSubtotal" class="form-control"
                                                                        placeholder="$0.00">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Shipping</td>
                                                            <td class="text-end">
                                                                <div class="ms-auto" style="width: 160px;">
                                                                    <input type="number" id="itemShipping"
                                                                        name="itemShipping" class="form-control"
                                                                        placeholder="$0.00">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Discount <small
                                                                    class="text-muted">(10%)</small></td>
                                                            <td class="text-end">
                                                                <div class="ms-auto" style="width: 160px;">
                                                                    <input type="number" id="itemDiscount"
                                                                        name="itemDiscount" class="form-control"
                                                                        placeholder="$0.00">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-medium">Tax <small
                                                                    class="text-muted">(18%)</small></td>
                                                            <td class="text-end">
                                                                <div class="ms-auto" style="width: 160px;">
                                                                    <input type="number" id="itemTaxes" name="itemTaxes"
                                                                        class="form-control" placeholder="$0.00">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="fs-15">
                                                            <th scope="row" class="fw-bold">Total Amount</th>
                                                            <th class="text-end">
                                                                <div class="ms-auto" style="width: 160px;">
                                                                    <input type="number" id="itemTotalAmount" disabled
                                                                        class="form-control" placeholder="$0.00">
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!--end table-->
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label" for="InventoryNote"> Note : </label>
                                            <textarea class="form-control" id="InventoryNote" name="InventoryNote"
                                                placeholder="Additional notes " rows="3"></textarea>
                                        </div>
                                </div> <!-- end card-body-->
                                <div class="mb-5">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:window.print()" class="btn btn-primary gap-1"><i
                                                class="ti ti-eye fs-16"></i> Preview</a>
                                        <button type="submit" class="btn btn-success gap-1"><i
                                                class="ti ti-device-floppy fs-16"></i> Save</button>
                                        <a href="javascript: void(0);" class="btn btn-info gap-1"><i
                                                class="ti ti-send fs-16"></i> Send Details</a>
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

</body>

</html>