<?php
    // Manage Users (Admin Only)
    require 'header.php';
    require 'db_connect.php';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records
    $countQuery = "SELECT COUNT(*) AS total_records FROM users";
    $countResult = mysqli_query($conn, $countQuery);
    $total_records = mysqli_fetch_assoc($countResult)['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch users with pagination
    $usersQuery = "SELECT user_id, username, full_name, role, created_at FROM users LIMIT $offset, $records_per_page";
    $users = mysqli_query($conn, $usersQuery);

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Users - GSA Store Management System</title>
    <style>
        .container {
            max-width: 95%; 
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

        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            color: #2c3e50;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }

        .pagination a.active {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
            font-weight: bold;
        }

        .pagination a.disabled {
            color: #ccc;
            pointer-events: none;
            cursor: not-allowed;
            background-color: #f4f4f4;
            border-color: #ddd;
        }

        @media (max-width: 768px) { 
            table { font-size: 14px; } 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Current Users</h2>

        <!-- Display Users -->
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
            <?php 
                $count = $offset + 1; // Start numbering from the correct offset
                while ($user = mysqli_fetch_assoc($users)): 
            ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= date("d M Y, H:i A", strtotime($user['created_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>">Previous</a>
            <?php else: ?>
                <a class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>">Next</a>
            <?php else: ?>
                <a class="disabled">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php require 'footer.php'; ?>