<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/db_connect.php';

// Fetch user details for editing
$user = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle update action
if (isset($_POST['updateUser'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':role' => $role,
                ':id' => $id
            ]);
            header("Location: user_details.php?success=1");
            exit();
        } else {
            echo "Invalid email format!";
        }
    } else {
        echo "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include('partials/sidenav.php'); ?>
        <div class="page-content">
            <div class="page-container">
                <h1 class="text-center">Edit User</h1>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control"
                            value="<?= $user['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="updateUser" class="btn btn-primary w-100">Update User</button>
                </form>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>
</body>

</html>