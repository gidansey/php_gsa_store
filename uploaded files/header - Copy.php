<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set security headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-Content-Type-Options: nosniff"); // Prevents MIME sniffing
header("Referrer-Policy: no-referrer"); // Hides referrer information

// Sanitize user role to prevent XSS
$user_role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') : 'Guest';

// Determine the correct dashboard based on user role
$dashboard_link = "dashboard.php"; // Default fallback
$role_based_dashboards = [
    'admin' => 'admin_dashboard.php',
    'storekeeper' => 'storekeeper_dashboard.php',
    'viewer' => 'viewer_dashboard.php'
];

if (isset($role_based_dashboards[$user_role])) {
    $dashboard_link = $role_based_dashboards[$user_role];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .navbar {
            background-color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        .navbar-left, .navbar-right {
            display: flex;
            gap: 15px;
        }

        .navbar-right span {
            color: white;
            padding: 10px;
        }

        h2, h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Styles */
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 450px;
            margin: 30px auto;
        }

        form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            text-align: left;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #004aad;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }

        form button:hover {
            background-color: #003580;
        }

        /* Table Styles */
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #004aad;
            color: white;
            font-weight: bold;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: center;
            }

            .navbar a {
                display: block;
                padding: 12px;
            }

            form {
                width: 90%;
            }

            table {
                width: 100%;
            }
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="navbar-left">
            <a href="<?= htmlspecialchars($dashboard_link, ENT_QUOTES, 'UTF-8'); ?>">Dashboard</a>
            <?php if ($user_role === 'admin'): ?>
                <a href="manage_users.php">Manage Users</a>
                <a href="upload_store_records.php">Upload Files</a>
            <?php endif; ?>
            <a href="create_item.php">Manage Items</a>
            <a href="receive_stock.php">Receive Stock</a>
            <a href="issue_stock.php">Issue Stock</a>
            <a href="manage_suppliers.php">Suppliers</a>
            <a href="view_reports.php">Reports</a>
        </div>

        <div class="navbar-right">
            <span>Hi, <?= htmlspecialchars($user_role, ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php" style="background:#dc3545; padding:8px 15px; border-radius:5px;">Logout</a>
        </div>
    </div>

    <div class="container">
