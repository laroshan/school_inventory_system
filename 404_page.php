<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = "Error 404";
    include('partials/title-meta.php');
    include('partials/head-css.php');
    ?>
</head>

<body>

    <div class="auth-bg d-flex min-vh-100 align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-lg-5 col-md-6">
                    <!-- Logo -->
                    <div class="auth-brand text-center mb-3">
                        <a href="index.php">
                            <img src="assets/images/sis_logo.png" alt="Logo Dark" height="120" class="logo-dark">
                        </a>
                    </div>


                    <!-- 404 Card -->
                    <div class="card text-center p-6">
                        <h1 class="text-error">404</h1>
                        <h3 class="mt-3 mb-2">Page Not Found</h3>
                        <p class="text-muted mb-3">
                            It looks like you've taken a wrong turn. Don't worry, it happens to the best of us.
                            You might want to check your internet connection or go back to the homepage.
                        </p>
                        <a href="index.php" class="btn btn-primary">
                            <i class="ti ti-home fs-16 me-1"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('partials/footer.php'); ?>

</body>

</html>