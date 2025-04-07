<?php
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'storekeeper') {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storekeeper Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px;
            text-align: center;
        }
        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
        }
        .navbar a:hover {
            background-color: #575757;
        }
        .container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Responsive grid */
            gap: 15px;
            margin-top: 20px;
            justify-content: center; /* Centers items */
        }
        .grid-container a {
            display: block;
            padding: 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .grid-container a:hover {
            background: #0056b3;
        }
        .logout {
            background: #dc3545;
        }
        .logout:hover {
            background: #c82333;
        }
        footer {
            text-align: center;
            padding: 10px;
            background: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <!-- <div class="navbar">
        <a href="storekeeper_dashboard.php">Dashboard</a>
        <a href="receive_stock.php">Receive Stock</a>
        <a href="issue_stock.php">Issue Stock</a>
        <a href="manage_suppliers.php">Manage Suppliers</a>
        <a href="view_reports.php">View Reports</a>
        <a href="logout.php" class="logout">Logout</a>
    </div> -->

    <div class="container">
        <h1>Welcome, Storekeeper</h1>
        <div class="grid-container">
            <a href="receive_stock.php">Receive Stock</a>
            <a href="issue_stock.php">Issue Stock</a>
            <a href="manage_suppliers.php">Manage Suppliers</a>
            <a href="view_reports.php">View Reports</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Store Management System
    </footer>
</body>
</html>
