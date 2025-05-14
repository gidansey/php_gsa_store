<?php
    require 'header.php';
    require 'db_connect.php'; // Include database connection

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Handle form submission for receiving stock
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $item_id = $_POST['item_id'];
        $received_quantity = $_POST['received_quantity'];
        $cost = $_POST['cost'];
        $supplier_id = $_POST['supplier_id'];
        $received_by = $_SESSION['user_id'];

        // Insert received stock into Received_Stock table
        $insertQuery = "INSERT INTO Received_Stock (item_id, received_quantity, cost, supplier_id, received_by, date_received)
                        VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iidis", $item_id, $received_quantity, $cost, $supplier_id, $received_by);
        $stmt->execute();

        // Update the current_price in the Items table
        $updateQuery = "UPDATE Items SET current_price = ? WHERE item_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("di", $cost, $item_id);
        $stmt->execute();

        // Redirect to avoid form resubmission
        header("Location: received_stock.php");
        exit();
    }

    // Filter options
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records for received stock
    $countQuery = "SELECT COUNT(*) AS total_records 
                   FROM Received_Stock r
                   JOIN Items i ON r.item_id = i.item_id
                   JOIN Suppliers s ON r.supplier_id = s.supplier_id
                   JOIN Users u ON r.received_by = u.user_id";
    if ($start_date && $end_date) {
        $countQuery .= " WHERE date_received BETWEEN '$start_date' AND '$end_date'";
    }
    $countResult = $conn->query($countQuery);
    $total_records = $countResult->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch received stock report with pagination
    $receivedQuery = "SELECT r.*, i.item_name, s.supplier_name, u.username 
                      FROM Received_Stock r
                      JOIN Items i ON r.item_id = i.item_id
                      JOIN Suppliers s ON r.supplier_id = s.supplier_id
                      JOIN Users u ON r.received_by = u.user_id";
    if ($start_date && $end_date) {
        $receivedQuery .= " WHERE date_received BETWEEN '$start_date' AND '$end_date'";
    }
    $receivedQuery .= " ORDER BY date_received DESC LIMIT $offset, $records_per_page";
    $receivedResult = $conn->query($receivedQuery);

    // Insert notification for both Admin and Storekeeper
    $query = "INSERT INTO notifications (message) VALUES (?)";
    $stmt = $conn->prepare($query);
    $message = "A new store request has been made!";
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->close();

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

        /* Pagination Styles */
        .pagination {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
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
        <h2>Store Receipts</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Date Received</th>
                <th style="text-align: left;">Item Name</th>
                <th>Received Quantity</th>
                <th>Cost</th>                
                <th style="text-align: left;">Supplier</th>
                <th>Received By</th>
            </tr>
            <?php 
                $number = $offset + 1; // Start numbering from the correct offset
                while ($received = $receivedResult->fetch_assoc()): 
            ?>
                <tr>
                    <td><?= $number++ ?></td>
                    <td><?= htmlspecialchars($received['date_received']) ?></td>
                    <td style="text-align: left;"><?= htmlspecialchars($received['item_name']) ?></td>
                    <td><?= htmlspecialchars($received['received_quantity']) ?></td>
                    <td><?= htmlspecialchars($received['cost']) ?></td>                    
                    <td style="text-align: left;"><?= htmlspecialchars($received['supplier_name']) ?></td>
                    <td><?= htmlspecialchars($received['username']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Previous</a>
            <?php else: ?>
                <a class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next</a>
            <?php else: ?>
                <a class="disabled">Next</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
<?php require 'footer.php'; ?>