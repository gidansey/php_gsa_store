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
    <!-- <title>Store Management System</title> -->
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar */
        .navbar {
            background-color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: #ecf0f1;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #34495e;
        }

        .navbar-left, .navbar-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-right span {
            color: #ecf0f1;
            font-weight: 500;
        }

        .navbar-right a.logout {
            background-color: #e74c3c;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .navbar-right a.logout:hover {
            background-color: #c0392b;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Headings */
        h1, h2, h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
        }

        h2 {
            font-size: 2rem;
            font-weight: 500;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 500;
        }

        /* Forms */
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 30px auto;
        }

        form label {
            font-weight: 500;
            margin-top: 15px;
            display: block;
            color: #2c3e50;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        form input:focus, form select:focus, form textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        form button {
            width: 100%;
            padding: 14px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: #fff;
            font-weight: 600;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Footer */
        /*footer {
            text-align: center;
            padding: 20px;
            background: #2c3e50;
            color: #ecf0f1;
            margin-top: 40px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }*/

        /* Footer */
        footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .navbar-left, .navbar-right {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .navbar a {
                width: 100%;
                text-align: center;
            }

            form {
                width: 90%;
            }

            table {
                width: 100%;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        
        <div class="navbar-left">
            <a href="<?= htmlspecialchars($dashboard_link, ENT_QUOTES, 'UTF-8'); ?>">Dashboard</a>
            <?php if ($user_role === 'admin'): ?>
                <a href="manage_users.php">Manage Users</a>
                <a href="create_item.php">Manage Items</a>
                <a href="receive_stock.php">Receive Stock</a>
                <a href="issue_stock.php">Issue Stock</a>
                <a href="manage_suppliers.php">Suppliers</a>
                <a href="view_reports.php">Reports</a>
            
            <?php elseif ($user_role === 'storekeeper'): ?>
                <a href="create_item.php">Manage Items</a>
                <a href="receive_stock.php">Receive Stock</a>
                <a href="issue_stock.php">Issue Stock</a>
                <a href="manage_suppliers.php">Suppliers</a>
                <a href="view_reports.php">Reports</a>

            <?php elseif ($user_role === 'viewer'): ?>
                <a href="make_requisition.php" class="active">Make Requisition</a>
                <a href="view_requisitions.php">View My Requisitions</a>
            <?php endif; ?>
        </div>

        <div class="navbar-right">
            <span>Hi, <?= htmlspecialchars($user_role, ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <div class="container">