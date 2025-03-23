<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Unauthorized Access";
    include('partials/title-meta.php');
    include('partials/head-css.php');
    ?>
</head>

<body>
    <div class="auth-bg d-flex min-vh-100 align-items-center justify-content-center">
        <div class="container text-center">
            <h1 class="text-danger">403</h1>
            <h3 class="mt-3 mb-2">Unauthorized Access</h3>
            <p class="text-muted mb-3">
                You do not have permission to access this page.<br>
                Please return to the dashboard or contact the administrator if you believe this is an error.
            </p>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home me-1"></i> Back to Dashboard
            </a>
            <a href="/login.php" class="btn btn-primary">Go to Login</a>

        </div>
    </div>
</body>

</html>