<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? null;
?>
<!-- Sidenav Menu Start -->
<div class="sidenav-menu" id="sidebar">

    <!-- Brand Logo -->
    <a href="index.php" class="logo">
        <span class="logo-dark">
            <span class="logo-lg"><img src="assets/images/sis_logo.png" alt="dark logo"></span>
        </span>
    </a>

    <div data-simplebar>
        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center text-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                    type="button" aria-haspopup="false" aria-expanded="false">
                    <span class="d-flex gap-1 sidenav-user-name my-2">
                        <span>
                            <span class="mb-0 fw-semibold lh-base fs-15"><?= $_SESSION['username'] ?></span>
                            <p class="my-0 fs-13 text-muted"><?= $_SESSION['role'] ?></p>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="scripts/logout.php" class="dropdown-item active fw-semibold text-danger">
                        <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Sign Out</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="index.php" class="side-nav-link">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <?php if ($role === 'admin'): ?>
                <li class="side-nav-item">
                    <a href="inventory.php" class="side-nav-link">
                        <i class="fas fa-box"></i> View Inventory
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="add_inventory.php" class="side-nav-link">
                        <i class="fas fa-plus-circle"></i> Add To Inventory
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="lended_item_list.php" class="side-nav-link">
                        <i class="fas fa-hand-holding"></i> Lended Items List
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="add_user.php" class="side-nav-link">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="user_details.php" class="side-nav-link">
                        <i class="fas fa-users"></i> User Details
                    </a>
                </li>
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarCharts" class="side-nav-link">
                        <i class="fas fa-chart-line"></i> Charts <i class="fas fa-chevron-down float-end"></i>
                    </a>
                    <div class="collapse" id="sidebarCharts">
                        <ul class="sub-menu">
                            <li><a href="chart_stock_levels.php">Stock Levels Over Time</a></li>
                            <li><a href="chart_category_distribution.php">Category-wise Inventory Distribution</a></li>
                            <li><a href="chart_monthly_borrowing.php">Monthly Borrowing Trends</a></li>
                            <li><a href="chart_top_borrowed.php">Top Borrowed Items</a></li>
                            <li><a href="chart_loan_vs_available.php">Loan vs. Available Inventory</a></li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a href="generate_report.php" class="side-nav-link">
                        <i class="fas fa-file-alt"></i> Generate Reports
                    </a>
                </li>
            <?php elseif ($role === 'teacher'): ?>
                <li class="side-nav-item">
                    <a href="inventory.php" class="side-nav-link">
                        <i class="fas fa-box"></i> View Inventory
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="loan-history.php" class="side-nav-link">
                        <i class="fas fa-history"></i> Loan History
                    </a>
                </li>
            <?php elseif ($role === 'student'): ?>
                <li class="side-nav-item">
                    <a href="inventory.php" class="side-nav-link">
                        <i class="fas fa-box"></i> View Inventory
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="loan-history.php" class="side-nav-link">
                        <i class="fas fa-history"></i> Loan History
                    </a>
                </li>
            <?php endif; ?>
            <li class="side-nav-item">
                <a href="scripts/logout.php" class="side-nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->