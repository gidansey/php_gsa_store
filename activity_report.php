<?php
    require 'header.php';
    require 'db_connect.php';

    // Fetch all items for selection dropdown
    $itemsQuery = "SELECT item_id, item_name FROM items";
    $itemsResult = $conn->query($itemsQuery);

    // Filters
    $item_id = isset($_GET['item_id']) ? $_GET['item_id'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records
    $countQuery = "
        SELECT COUNT(*) AS total_records
        FROM (
            SELECT date_received AS date
            FROM received_stock
            WHERE ('$item_id' = '' OR item_id = '$item_id')
            " . ($start_date && $end_date ? " AND date_received BETWEEN '$start_date' AND '$end_date'" : "") . "
            UNION ALL
            SELECT date_issued AS date
            FROM issued_stock
            WHERE ('$item_id' = '' OR item_id = '$item_id')
            " . ($start_date && $end_date ? " AND date_issued BETWEEN '$start_date' AND '$end_date'" : "") . "
        ) AS combined
    ";
    $countResult = $conn->query($countQuery);
    if (!$countResult) {
        die("Error in count query: " . $conn->error);
    }
    $total_records = $countResult->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Query to get stock activity with pagination
    $activityQuery = "
        SELECT date_received AS date, received_quantity AS quantity, cost AS price_cost, 'Received' AS type, s.supplier_name AS description, u.username AS handled_by, i.item_name
        FROM received_stock r
        JOIN suppliers s ON r.supplier_id = s.supplier_id
        JOIN users u ON r.received_by = u.user_id
        JOIN items i ON r.item_id = i.item_id
        WHERE ('$item_id' = '' OR r.item_id = '$item_id')
    ";

    if ($start_date && $end_date) {
        $activityQuery .= " AND date_received BETWEEN '$start_date' AND '$end_date'";
    }

    $activityQuery .= "
        UNION ALL
        SELECT date_issued AS date, issued_quantity AS quantity, price AS price_cost, 'Issued' AS type, i.description AS description, u.username AS handled_by, i2.item_name
        FROM issued_stock i
        JOIN users u ON i.issued_by = u.user_id
        JOIN items i2 ON i.item_id = i2.item_id
        WHERE ('$item_id' = '' OR i.item_id = '$item_id')
    ";

    if ($start_date && $end_date) {
        $activityQuery .= " AND date_issued BETWEEN '$start_date' AND '$end_date'";
    }

    $activityQuery .= " ORDER BY date DESC LIMIT $offset, $records_per_page";

    // Debugging: Print the query
    // echo "<pre>Query: $activityQuery</pre>";

    $activityResult = $conn->query($activityQuery);
    if (!$activityResult) {
        die("Error in activity query: " . $conn->error);
    }

    // Fetch the selected item's name (if an item is selected)
    $selectedItemName = '';
    if ($item_id) {
        $itemQuery = "SELECT item_name FROM items WHERE item_id = '$item_id'";
        $itemResult = $conn->query($itemQuery);
        if (!$itemResult) {
            die("Error in item query: " . $conn->error);
        }
        if ($itemResult->num_rows > 0) {
            $selectedItemName = $itemResult->fetch_assoc()['item_name'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Activity - GSA Store Management System</title>
    <style>
        .container {
            max-width: 80%; 
            margin: 10px auto; 
            padding: 5px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
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

        .filter-panel label, 
        .filter-panel select, 
        .filter-panel input, 
        .filter-panel button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }

        .filter-button {
            position: fixed;
            top: 80px; /* Same as the panel to keep alignment */
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
        <h2>Stock Activity</h2>

        <!-- Floating Filter Button -->
        <button class="filter-button" onclick="toggleFilterPanel()">Filters</button>

        <!-- Hidden Filter Panel -->
        <div id="filterPanel" class="filter-panel">
            <form method="GET">
                <label for="item_id">Select Item:</label>
                <select name="item_id" id="item_id">
                    <option value="">All Items</option>
                    <?php while ($item = $itemsResult->fetch_assoc()): ?>
                        <option value="<?= $item['item_id'] ?>" <?= ($item_id == $item['item_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($item['item_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="<?= $start_date ?>">

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="<?= $end_date ?>">

                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <!-- Display selected item name if an item is selected -->
        <?php if ($item_id && $selectedItemName): ?>
            <h3>Item: <?= htmlspecialchars($selectedItemName) ?></h3>
        <?php endif; ?>

        <table>
            <tr>
                <?php if (!$item_id): ?>
                    <th>No</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price/Cost (GH¢)</th>
                    <th>Amount (GH¢)</th>
                    <th>Type</th>
                    <th>Handled By</th>
                <?php else: ?>
                    <th>No</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price/Cost (GH¢)</th>
                    <th>Amount (GH¢)</th>
                    <th>Type</th>
                    <th>Handled By</th>
                <?php endif; ?>
            </tr>
            <?php 
                $count = $offset + 1; // Start numbering from the correct offset
                while ($row = $activityResult->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= date("d M Y", strtotime($row['date'])) ?></td>
                <?php if (!$item_id): ?>
                    <td style="text-align: left;"><?= htmlspecialchars($row['item_name']) ?></td>
                <?php endif; ?>
                <td style="text-align: left;"><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td style="text-align: right;"><?= number_format($row['price_cost'], 2) ?></td>
                <td style="text-align: right;"><?= number_format($row['quantity'] * $row['price_cost'], 2) ?></td>
                <td><?= $row['type'] ?></td>                    
                <td><?= htmlspecialchars($row['handled_by']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>&item_id=<?= $item_id ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Previous</a>
            <?php else: ?>
                <a class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&item_id=<?= $item_id ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>&item_id=<?= $item_id ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>">Next</a>
            <?php else: ?>
                <a class="disabled">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleFilterPanel() {
            var panel = document.getElementById("filterPanel");
            if (panel.classList.contains("open")) {
                panel.classList.remove("open");
            } else {
                panel.classList.add("open");
            }
        }
    </script>

</body>
</html>

<?php require 'footer.php'; ?>