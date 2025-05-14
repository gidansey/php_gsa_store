<?php
    require 'header.php';
    require 'db_connect.php'; // Include database connection

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Filter options
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Fetch current stock report
    $stockQuery = "SELECT * FROM Items";
    $stockResult = $conn->query($stockQuery);

    // Fetch received stock report
    $receivedQuery = "SELECT r.*, i.item_name, s.supplier_name, u.username 
                      FROM Received_Stock r
                      JOIN Items i ON r.item_id = i.item_id
                      JOIN Suppliers s ON r.supplier_id = s.supplier_id
                      JOIN Users u ON r.received_by = u.user_id";
    if ($start_date && $end_date) {
        $receivedQuery .= " WHERE date_received BETWEEN '$start_date' AND '$end_date'";
    }
    $receivedResult = $conn->query($receivedQuery);

    // Fetch issued stock report
    $issuedQuery = "SELECT isd.*, i.item_name, u.username 
                    FROM Issued_Stock isd
                    JOIN Items i ON isd.item_id = i.item_id
                    JOIN Users u ON isd.issued_by = u.user_id";
    if ($start_date && $end_date) {
        $issuedQuery .= " WHERE date_issued BETWEEN '$start_date' AND '$end_date'";
    }
    $issuedResult = $conn->query($issuedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports - GSA Store Management System</title>
    <style>
        /* Page-specific Styles */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2, h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Filter Form */
        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
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

    <div class="container">
        <!-- Received Stock Report -->
        <h3>Received Stock</h3>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Supplier</th>
                <th>Received Quantity</th>
                <th>Cost</th>
                <th>Date Received</th>
                <th>Received By</th>
            </tr>
            <?php while ($received = $receivedResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $received['item_name'] ?></td>
                    <td><?= $received['supplier_name'] ?></td>
                    <td><?= $received['received_quantity'] ?></td>
                    <td><?= $received['cost'] ?></td>
                    <td><?= $received['date_received'] ?></td>
                    <td><?= $received['username'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Issued Stock Report -->
        <h3>Issued Stock</h3>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Issued Quantity</th>
                <th>Price</th>
                <th>Date Issued</th>
                <th>Issued By</th>
                <th>Transferred From</th>
                <th>Transferred To</th>
                <th>Voucher</th>
            </tr>
            <?php while ($issued = $issuedResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $issued['item_name'] ?></td>
                    <td><?= $issued['issued_quantity'] ?></td>
                    <td><?= $issued['price'] ?></td>
                    <td><?= $issued['date_issued'] ?></td>
                    <td><?= $issued['username'] ?></td>
                    <td><?= $issued['transferred_from'] ?></td>
                    <td><?= $issued['transferred_to'] ?></td>
                    <td><?= $issued['store_issue_voucher'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
<?php require 'footer.php'; ?>