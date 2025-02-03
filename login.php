<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <?php
    $title = "Log In";
    include('partials/title-meta.php');
    include('partials/head-css.php');
    ?>

</head>
<body>
    <div class="auth-bg d-flex min-vh-100 align-items-center justify-content-center">
        <div class="auth-container p-4 rounded shadow-lg">
            <a href="index.php" class="auth-brand d-flex justify-content-center mb-4">
                <img src="assets/images/sis_logo.png" alt="School Inventory System Logo" height="50">
            </a>

            <h4 class="text-center mb-4">Sign In</h4>

            <?php
            if (isset($_SESSION['login_error'])) {
                echo '<div class="alert alert-danger text-center">' . $_SESSION['login_error'] . '</div>';
                unset($_SESSION['login_error']);
            }
            ?>

            <form action="scripts/process_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

            <p class="text-center mt-3">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>