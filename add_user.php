<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="app.css">
</head>

<body class="auth-bg">
    <div class="auth-container">
        <div class="auth-brand text-center">
            <h3>Add New User</h3>
        </div>
        <form action="add_user_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>

                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Add User</button>
        </form>
        <div class="text-center mt-3">
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>