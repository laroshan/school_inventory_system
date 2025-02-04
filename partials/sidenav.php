<!-- Sidenav Menu Start -->
<div class="sidenav-menu" id="sidebar">

    <!-- Brand Logo -->
    <a href="index.php" class="logo">
        <span class="logo-dark">
            <span class="logo-lg"><img src="assets/images/sis_logo.png" alt="dark logo"></span>
        </span>
    </a>

    <!-- Sidebar Toggle Buttons -->

    <button class="sidenav-toggle-button">
        <i class="fas fa-bars"></i>
    </button>

    <button class="button-close-fullsidebar sidenav-close">
        <i class="fas fa-times"></i>
    </button>


    <div data-simplebar>

        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center text-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                    type="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/avatar.jpg" width="46" class="rounded-circle" alt="user-image">
                    <span class="d-flex gap-1 sidenav-user-name my-2">
                        <span>
                            <span class="mb-0 fw-semibold lh-base fs-15">User 1</span>
                            <p class="my-0 fs-13 text-muted">Admin</p>
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
            <li class="side-nav-item">
                <a href="inventory.php" class="side-nav-link">
                    <i class="fas fa-box"></i> View Inventory
                </a>
            </li>
            <li class="side-nav-item">
                <a href="add_remove_inventory.php" class="side-nav-link">
                    <i class="fas fa-plus-circle"></i> Add To Inventory
                </a>
            </li>
            <li class="side-nav-item">
                <a href="borrow-items.php" class="side-nav-link">
                    <i class="fas fa-hand-holding"></i> Borrow Items
                </a>
            </li>
            <li class="side-nav-item">
                <a href="add_user.php" class="side-nav-link">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarContacts" class="side-nav-link">
                    <i class="fas fa-users"></i> Users <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="sidebarContacts">
                    <ul class="sub-menu">
                        <li><a href="apps-user-contacts.php">Contacts</a></li>
                        <li><a href="apps-user-profile.php">Profile</a></li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a href="apps-file-manager.php" class="side-nav-link">
                    <i class="fas fa-folder"></i> File Manager
                </a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCharts" class="side-nav-link">
                    <i class="fas fa-chart-line"></i> Charts <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="sidebarCharts">
                    <ul class="sub-menu">
                        <li><a href="charts-apex-area.php">Inventory Distribution</a></li>
                        <li><a href="charts-apex-bar.php">Cost Chart</a></li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTables" class="side-nav-link">
                    <i class="fas fa-table"></i> Sample Content <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="sub-menu">
                        <li><a href="tables-basic.php">Sample 1</a></li>
                        <li><a href="tables-gridjs.php">Sample 2</a></li>
                    </ul>
                </div>
            </li>
        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->