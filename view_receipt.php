<?php
    require 'header.php';
    require 'db_connect.php';

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Set default values for filters
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records
    $countQuery = "SELECT COUNT(*) AS total_records FROM received_stock r
                   JOIN items i ON r.item_id = i.item_id
                   JOIN suppliers s ON r.supplier_id = s.supplier_id
                   JOIN users u ON r.received_by = u.user_id
                   WHERE ('$start_date' = '' OR r.date_received BETWEEN '$start_date' AND '$end_date')
                   AND ('$search' = '' OR i.item_name LIKE '%$search%' OR s.supplier_name LIKE '%$search%' OR u.username LIKE '%$search%')";
    $countResult = $conn->query($countQuery);
    $total_records = $countResult->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch received stock report with pagination
    $receivedQuery = "SELECT r.*, i.item_name, s.supplier_name, u.username 
                      FROM received_stock r
                      JOIN items i ON r.item_id = i.item_id
                      JOIN suppliers s ON r.supplier_id = s.supplier_id
                      JOIN users u ON r.received_by = u.user_id
                      WHERE ('$start_date' = '' OR r.date_received BETWEEN '$start_date' AND '$end_date')
                      AND ('$search' = '' OR i.item_name LIKE '%$search%' OR s.supplier_name LIKE '%$search%' OR u.username LIKE '%$search%')
                      ORDER BY r.date_received DESC
                      LIMIT $offset, $records_per_page";
    $receivedResult = $conn->query($receivedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Stock Report - GSA Store Management System</title>
    <style>
        .container { max-width: 90%; margin: 10px auto; padding: 5px; }
        h2 { text-align: center; color: #2c3e50; }

        .filter-panel {
            position: fixed;
            top: 80px;
            right: -300px;
            width: 250px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 15px;
            transition: right 0.3s ease-in-out;
            border-radius: 5px;
            z-index: 9999;
        }
        .filter-panel.open { right: 20px; }

        .filter-button {
            position: fixed;
            top: 80px;
            right: 20px;
            background: #2c3e50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            z-index: 10000;
        }
        .filter-button:hover { background: #1a252f; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #2c3e50; color: white; }
        tr:hover { background: #f5f5f5; }

        .search-bar {
            text-align: center;
            margin-bottom: 10px;
        }
        .search-bar input {
            padding: 5px;
            width: 50%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-bar button {
            padding: 6px 10px;
            border: none;
            background: #2c3e50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-bar button:hover { background: #1a252f; }

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
        <h2>Received Stock Report</h2>
        
        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by item, supplier, or user" value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
        </div>

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

        <!-- Received Stock Table -->
        <table>
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>Supplier</th>
                <th>Received Quantity</th>
                <th>Cost (GH¢)</th>
                <th>Date Received</th>
                <th>Received By</th>
            </tr>
            
            <?php 
                $numb = $offset + 1; // Start numbering from the correct offset
                while ($received = $receivedResult->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $numb++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($received['item_name']) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($received['supplier_name']) ?></td>
                <td><?= htmlspecialchars($received['received_quantity']) ?></td>
                <td style="text-align: right;"><?= number_format($received['cost'], 2) ?></td>
                <td><?= htmlspecialchars($received['date_received']) ?></td>
                <td><?= htmlspecialchars($received['username']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search=<?= $search ?>">Previous</a>
            <?php else: ?>
                <a class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search=<?= $search ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search=<?= $search ?>">Next</a>
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