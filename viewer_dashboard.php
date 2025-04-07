<?php
    session_start();

    // Ensure the user is logged in as a viewer
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'viewer') {
        header("Location: index.php");
        exit();
    }

    // Get the logged-in storekeeper's full name
    $viewer_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Viewer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewer Dashboard - GSA Store Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 50px;
        }
        h1 {
            color: #333;
        }
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            max-width: 600px;
            margin: 30px auto;
        }
        .menu-button {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .menu-button:hover {
            background-color: #0056b3;
        }
        .logout-button {
            background-color: #dc3545;
        }
        .logout-button:hover {
            background-color: #b02a37;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($viewer_name); ?></h1>
    
    <div class="menu-container">
        <a href="make_requisition.php" class="menu-button">Make Requisition</a>
        <a href="view_requisitions.php" class="menu-button">View My Requisitions</a>
        <a href="logout.php" class="menu-button logout-button">Logout</a>
    </div>
</body>
</html>
