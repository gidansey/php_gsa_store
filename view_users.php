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
    <title>Current Users - GSA Store Management System</title>
    <style>
        .container {
            max-width: 60%; 
            margin: 20px auto; 
            padding: 10px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }

        .filter-form, .table-container { 
            width: 100%; 
            text-align: center; 
        }

        .filter-form input, .filter-form button { 
            margin: 5px; 
            padding: 8px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td { 
            padding: 10px; 
            border: 1px solid #ddd; 
            text-align: center; 
        }

        th { 
            background: #2c3e50; 
            color: white; 
        }

        tr:hover { 
            background: #f5f5f5; 
        }

        .table-container { 
            overflow-x: auto; 
        }

        .pagination { 
            text-align: center; 
            margin-top: 10px; 
        }

        .pagination button { 
            padding: 5px 10px; 
            margin: 2px; 
            cursor: pointer; 
        }

        @media (max-width: 768px) { 
            table { font-size: 14px; } 
        }
    </style>

</head>

    <h2>Current Users</h2>

    <!-- Display Users -->
    <table>
        <tr>
            <th>No</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created At</th>
        </tr>
            <?php 
                $count = 1; 
                while ($user = mysqli_fetch_assoc($users)): 
            ?>
            <tr>
                <td><?= $count++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= date("d M Y, H:i A", strtotime($user['created_at'])) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

<?php require 'footer.php'; ?>
