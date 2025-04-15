<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr CSS for date picker -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <!-- Iconify for icons -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <link href="assets/vendor/gridjs/gridjs.css" rel="stylesheet" type="text/css" />

    <?php
    $title = "School Inventory System Dashboard";
    include('partials/title-meta.php');

    include('partials/head-css.php');
    ?>
    <?php include('partials/sidenav.php'); ?>

    <script src="path/to/bootstrap.bundle.min.js"></script>

    <script src="assets/js/sidebar-toggle.js"></script>
</head>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="notificationDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i> Notifications <span id="notificationCount" class="badge bg-danger">0</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationList">
            <!-- Notifications will be dynamically loaded here -->
        </ul>
    </div>
</div>

<script>
    function fetchNotifications() {
        fetch('fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                const notificationCount = document.getElementById('notificationCount');
                notificationList.innerHTML = '';
                notificationCount.textContent = data.unread_count;

                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        const li = document.createElement('li');
                        li.className = 'dropdown-item';
                        li.textContent = notification.message;
                        notificationList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.className = 'dropdown-item text-muted';
                    li.textContent = 'No new notifications';
                    notificationList.appendChild(li);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function markNotificationsAsRead() {
        fetch('mark_notifications_read.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('notificationCount').textContent = '0';
                }
            })
            .catch(error => console.error('Error marking notifications as read:', error));
    }

    document.addEventListener("DOMContentLoaded", function () {
        fetchNotifications(); // Initial fetch
        setInterval(fetchNotifications, 5000); // Fetch notifications every 5 seconds

        const notificationDropdown = document.getElementById('notificationDropdown');
        notificationDropdown.addEventListener('click', markNotificationsAsRead);
    });
</script>