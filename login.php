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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            <span class="logo-dark">
            <span class="logo-lg"><img src="assets/images/sis_logo.png" alt="dark logo"></span>
        </span>                    </a>
        <h3 class="text-center mb-4 fw-bold text-primary" style="font-size: 1.75rem;">School Inventory System</h3>

        <h4 class="text-center mb-4 fw-semibold text-dark" style="font-size: 1.5rem;">
                Sign In
            </h4>
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