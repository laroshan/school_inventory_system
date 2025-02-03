<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="app.css">
</head>
<body class="auth-bg">
    <div class="wrapper d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card col-xxl-3">
            <div class="card-header text-center">
                <h3>Register</h3>
            </div>
            <div class="card-body">
                <form action="register_process.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="login.php">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
