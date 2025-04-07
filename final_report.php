<?php
    require 'header.php';
    require 'db_connect.php';

    // Check if user is logged in with the correct role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Set default values for date filtering
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Fetch total number of records
    $count_query = "
        SELECT COUNT(*) AS total_records
        FROM (
            SELECT r.date_received AS transaction_date
            FROM received_stock r
            WHERE ('$start_date' = '' OR r.date_received BETWEEN '$start_date' AND '$end_date')
            UNION ALL
            SELECT isd.date_issued AS transaction_date
            FROM issued_stock isd
            WHERE ('$start_date' = '' OR isd.date_issued BETWEEN '$start_date' AND '$end_date')
        ) AS combined
    ";
    $count_result = $conn->query($count_query);
    $total_records = $count_result->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch transaction data (received and issued items) with pagination
    $query = "
        SELECT 
            r.date_received AS transaction_date, 
            i.item_name, 
            r.received_quantity AS quantity, 
            r.cost AS price, 
            (r.received_quantity * r.cost) AS amount, 
            'Received' AS transaction_type,
            s.supplier_name AS description, 
            '' AS transferred_from, 
            'GSA Stores' AS transferred_to, 
            u.username AS handled_by
        FROM received_stock r
        JOIN items i ON r.item_id = i.item_id
        JOIN suppliers s ON r.supplier_id = s.supplier_id
        JOIN users u ON r.received_by = u.user_id
        WHERE ('$start_date' = '' OR r.date_received BETWEEN '$start_date' AND '$end_date')

        UNION ALL

        SELECT 
            isd.date_issued AS transaction_date, 
            i.item_name, 
            ABS(isd.issued_quantity) AS quantity, 
            isd.price AS price, 
            (isd.issued_quantity * isd.price) AS amount, 
            'Issued' AS transaction_type,
            isd.description AS description, 
            isd.transferred_from, 
            isd.transferred_to, 
            u.username AS handled_by
        FROM issued_stock isd
        JOIN items i ON isd.item_id = i.item_id
        JOIN users u ON isd.issued_by = u.user_id
        WHERE ('$start_date' = '' OR isd.date_issued BETWEEN '$start_date' AND '$end_date')
        ORDER BY transaction_date DESC
        LIMIT $offset, $records_per_page
    ";

    // Execute query and check for errors
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Transaction Trail - GSA Store Management</title>
    <style>
        .container {
            max-width: 90%; 
            margin: 10px auto; 
            padding: 5px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }

        .filter-panel {
            position: fixed;
            top: 80px; /* Push it below the menu bar */
            right: -300px;
            width: 250px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 15px;
            transition: right 0.3s ease-in-out;
            border-radius: 5px;
            z-index: 9999;
        }

        .filter-panel.open {
            right: 20px;
        }

        .filter-button {
            position: fixed;
            top: 80px; /* Align with menu bar */
            right: 20px;
            background: #2c3e50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            z-index: 10000;
        }

        .filter-button:hover {
            background: #1a252f;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Stock Transaction Trail</h2>

        <!-- Filter Button -->
        <button id="toggleFilter" class="filter-button">Filter</button>

        <!-- Filter Panel -->
        <div id="filterPanel" class="filter-panel">
            <form method="GET">
                <label>Start Date:</label>
                <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                <label>End Date:</label>
                <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                <button type="submit">Apply Filter</button>
            </form>
        </div>

        <!-- Stock Transactions Table -->
        <table>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price/Cost (GH¢)</th>
                <th>Amount (GH¢)</th>
                <th>Transaction Type</th>
                <th>Transferred From</th>
                <th>Transferred To</th>
                <th>Handled By</th>
            </tr>
            
            <?php 
                $numb = $offset + 1; // Start numbering from the correct offset
                $total_received = 0;
                $total_issued = 0;

                while ($row = $result->fetch_assoc()): 
                    if ($row['transaction_type'] === 'Received') {
                        $total_received += $row['quantity'];
                    } elseif ($row['transaction_type'] === 'Issued') {
                        $total_issued += $row['quantity'];
                    }
            ?>
            <tr>
                <td><?= $numb++ ?></td>
                <td><?= date("d M Y", strtotime($row['transaction_date'])) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['item_name']) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($row['description']) ?></td>
                <td><?= number_format($row['quantity']) ?></td>
                <td style="text-align: right;"><?= number_format($row['price'], 2) ?></td>
                <td style="text-align: right;"><?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['transaction_type'] ?></td>
                <td><?= htmlspecialchars($row['transferred_from']) ?></td>
                <td><?= htmlspecialchars($row['transferred_to']) ?></td>
                <td><?= htmlspecialchars($row['handled_by']) ?></td>
            </tr>
            <?php endwhile; ?>

            <!-- Display Totals -->
            <!-- <tr class="totals-row">
                <td colspan="3"><strong>Totals</strong></td>
                <td><strong>Received: <?= number_format($total_received) ?></strong></td>
                <td colspan="2"><strong>Issued: <?= number_format($total_issued) ?></strong></td>
                <td colspan="2"><strong>Available: <?= number_format($total_received - $total_issued) ?></strong></td>
                <td colspan="3"></td>
            </tr> -->
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

    <script>
        document.getElementById("toggleFilter").addEventListener("click", function () {
            document.getElementById("filterPanel").classList.toggle("open");
        });
    </script>

</body>
</html>

<?php require 'footer.php'; ?>