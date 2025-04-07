<?php
    // Start the session and include necessary files
    ob_start(); // Start output buffering
    session_start();
    require 'db_connect.php';

    // Redirect to login if the user is not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    // Set security headers
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer");

    // Define user details
    $user_id = $_SESSION['user_id'];
    $user_role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') : 'Guest';
    $header_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest";

    // Determine dashboard link
    $dashboard_link = "dashboard.php";
    $role_based_dashboards = [
        'admin' => 'admin_dashboard.php',
        'storekeeper' => 'storekeeper_dashboard.php',
        'viewer' => 'viewer_dashboard.php'
    ];

    if (isset($role_based_dashboards[$user_role])) {
        $dashboard_link = $role_based_dashboards[$user_role];
    }

    // Fetch unread notifications count
    $unreadCount = 0;
    if (!empty($user_id)) {
        $sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0 AND user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $unreadCount = $row['unread_count'] ?? 0;
            }
            $stmt->close();
        }
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
            padding-top: 70px; /* Adjust according to the navbar height */
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
            position: fixed;  /* Fixed position */
            top: 0;  /* Stick to the top */
            left: 0;
            width: 100%;  /* Full width */
            z-index: 1000;  /* Ensure it's above other content */
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
            margin: 100px auto 30px auto; /* Add top margin to account for the fixed navbar */
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
        footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 40px;
        }

        /* Ensure dropdown stays within viewport */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #2c3e50;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 4px;
            top: 100%; /* Position the dropdown below the parent */
            left: 0; /* Default to left alignment */
            white-space: nowrap;
        }

        /* Align dropdown to the right if close to the edge */
        .dropdown.right .dropdown-content {
            left: auto;
            right: 0;
        }

        .dropdown-content a {
            color: #ecf0f1;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #34495e;
        }

        .dropdown:hover .dropdown-content {
            display: block;
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

        .notification-icon {
            position: relative;
            display: inline-block;
            position: ;
        }

        .notification-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            font-size: 12px;
            padding: 3px 7px;
            border-radius: 50%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
                $(document).ready(function () {
            let lastUnreadCount = -1; // Store last unread count to prevent unnecessary updates
            const $notificationCounter = $('.notification-counter');

            function fetchNotifications() {
                $.ajax({
                    url: 'get_unread_notifications.php',
                    method: 'GET',
                    dataType: 'json',
                    cache: false, // Prevent browser caching
                    success: function (response) {
                        if (response.unreadCount !== lastUnreadCount) {
                            lastUnreadCount = response.unreadCount;
                            if (lastUnreadCount > 0) {
                                $notificationCounter.text(lastUnreadCount).fadeIn();
                            } else {
                                $notificationCounter.fadeOut();
                            }
                        }
                    },
                    error: function () {
                        console.error("Error fetching notifications.");
                    }
                });
            }

            function markNotificationsRead() {
                $.ajax({
                    url: 'mark_notifications.php',
                    method: 'POST',
                    data: { markRead: true }, // Use a POST payload for security
                    success: function (response) {
                        if (response === "success") {
                            lastUnreadCount = 0; // Reset counter
                            $notificationCounter.fadeOut();
                        }
                    },
                    error: function () {
                        console.error("Error marking notifications as read.");
                    }
                });
            }

            // Fetch notifications every 10 seconds instead of 5 to reduce load
            setInterval(fetchNotifications, 10000);

            // Mark notifications as read when the dropdown is opened
            $(".notification-icon").on("click", function () {
                markNotificationsRead();
            });

            // Initial fetch to show notifications on page load
            fetchNotifications();
        });
    </script>
</head>
<body>

    <div class="navbar">
        <div class="navbar-left">
            
            <a href="<?= htmlspecialchars($dashboard_link, ENT_QUOTES, 'UTF-8'); ?>">Dashboard</a>
            
            <?php if ($user_role === 'admin'): ?>
                <div class="dropdown">
                    <a href="manage_users.php" class="dropbtn">Manage Users</a>
                    <div class="dropdown-content">
                        <a href="add_user.php">Add User</a>
                        <a href="view_users.php">View Users</a>
                        <a href="reset_user_password.php">Reset Password</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="create_item.php" class="dropbtn">Manage Items</a>
                    <div class="dropdown-content">
                        <a href="add_item.php">Add Item</a>
                        <a href="view_item.php">View Items</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="receive_stock.php" class="dropbtn">Store Receipts</a>
                    <div class="dropdown-content">
                        <a href="add_receipt.php">Receive Items</a>
                        <a href="view_receipt.php">View Receipt</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="issue_stock.php" class="dropbtn">Store Issues</a>
                    <div class="dropdown-content">
                        <a href="issue_items.php">Issue Items</a>
                        <a href="view_issues.php">View Issued Items</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="manage_suppliers.php" class="dropbtn">Suppliers</a>
                    <div class="dropdown-content">
                        <a href="new_supplier.php">Add Supplier</a>
                        <a href="edit_suppliers.php">Edit Suppliers</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="activity_report.php" class="dropbtn">Reports</a>
                    <div class="dropdown-content">
                        <a href="final_report.php">All Transaction</a>
                        <!-- <a href="#">Edit Suppliers</a> -->
                    </div>
                </div>
                <!-- <a href="upload_store_records.php">Upload Files</a> -->
                <!-- <a href="upload_description_for_issued_stock.php">Upload Desc</a> -->
            
            <?php elseif ($user_role === 'storekeeper'): ?>
                <div class="dropdown">
                    <a href="create_item.php" class="dropbtn">Manage Items</a>
                    <div class="dropdown-content">
                        <a href="add_item.php">Add Item</a>
                        <a href="view_item.php">View Items</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="receive_stock.php" class="dropbtn">Store Receipts</a>
                    <div class="dropdown-content">
                        <a href="add_receipt.php">Receive Items</a>
                        <a href="view_receipt.php">View Receipt</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="issue_stock.php" class="dropbtn">Store Issues</a>
                    <div class="dropdown-content">
                        <a href="issue_items.php">Issue Items</a>
                        <a href="view_issues.php">View Issued Items</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="manage_suppliers.php" class="dropbtn">Suppliers</a>
                    <div class="dropdown-content">
                        <a href="new_supplier.php">Add Supplier</a>
                        <a href="edit_suppliers.php">Edit Suppliers</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="activity_report.php" class="dropbtn">Reports</a>
                    <div class="dropdown-content">
                        <a href="final_report.php">All Transaction</a>
                        <!-- <a href="#">Edit Suppliers</a> -->
                    </div>
                </div>
            
            <?php elseif ($user_role === 'viewer'): ?>
                <a href="make_requisition.php" class="active">Make Requisition</a>
                <a href="view_requisitions.php">View My Requisitions</a>
            
            <?php endif; ?>
        </div>

        <div class="navbar-right">
            <div class="notification-icon">
                <a href="view_notifications.php" onclick="markNotificationsRead()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" alt="Notifications" width="30" height="30">
                        <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
                    </svg>
                    <span class="notification-counter" style="display: <?= $unreadCount > 0 ? 'inline' : 'none'; ?>;">
                        <?= $unreadCount; ?>
                    </span>
                </a>
            </div>


            <div class="dropdown">
                <span class="dropbtn">Welcome, <?= htmlspecialchars($header_name, ENT_QUOTES, 'UTF-8'); ?></span>
                <div class="dropdown-content">
                    <a href="reset_password.php">Reset Password</a>
                    <a href="logout.php" class="logout">Logout</a>
                </div>
            </div>
            
        </div>
    </div>


    <div class="container">