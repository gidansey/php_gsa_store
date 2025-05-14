<?php
    // Manage Users (Admin Only)
    require 'header.php';
    require 'db_connect.php';

    // Fetch all users
    $users = mysqli_query($conn, "SELECT user_id, username, full_name, role, created_at FROM users");

    // Handle user actions (Add/Edit/Delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_user'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = mysqli_real_escape_string($conn, $_POST['role']);

            // Insert new user
            $query = "INSERT INTO users (username, full_name, password, role, created_at) VALUES ('$username', '$full_name', '$password', '$role', NOW())";
            if (mysqli_query($conn, $query)) {
                echo "<p class='success-message'>User added successfully!</p>";
            } else {
                echo "<p class='error-message'>Error: " . mysqli_error($conn) . "</p>";
            }

            header('Location: manage_users.php');
            exit();
        }
    }
?>
<head>
    <title>Add Users - GSA Store Management System</title>
    
</head>

    <h2>Add Users</h2>

    <!-- User Registration Form -->
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="storekeeper">Storekeeper</option>
            <option value="viewer">Viewer</option>
        </select>
        <button type="submit" name="add_user">Add User</button>
    </form>

<?php require 'footer.php'; ?>
