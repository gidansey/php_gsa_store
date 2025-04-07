<?php
    require 'header.php';
    require 'db_connect.php';

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Set default values for date filtering and search
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records
    $countQuery = "SELECT COUNT(*) AS total_records FROM issued_stock isd
                   JOIN items i ON isd.item_id = i.item_id
                   JOIN users u ON isd.issued_by = u.user_id
                   WHERE ('$start_date' = '' OR isd.date_issued BETWEEN '$start_date' AND '$end_date')
                   AND ('$search' = '' OR i.item_name LIKE '%$search%' OR isd.description LIKE '%$search%' OR u.username LIKE '%$search%')";
    $countResult = $conn->query($countQuery);
    $total_records = $countResult->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch issued stock report with pagination
    $issuedQuery = "SELECT isd.*, i.item_name, u.username FROM issued_stock isd
                    JOIN items i ON isd.item_id = i.item_id
                    JOIN users u ON isd.issued_by = u.user_id
                    WHERE ('$start_date' = '' OR isd.date_issued BETWEEN '$start_date' AND '$end_date')
                    AND ('$search' = '' OR i.item_name LIKE '%$search%' OR isd.description LIKE '%$search%' OR u.username LIKE '%$search%')
                    ORDER BY isd.date_issued DESC
                    LIMIT $offset, $records_per_page";
    $issuedResult = $conn->query($issuedQuery);

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
    <title>Issued Stock Report - GSA Store Management System</title>
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
        .filter-panel.open { 
            right: 20px; 
        }

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
        .search-bar button:hover { 
            background: #1a252f; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Issued Stock Report</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by item, description, or user" value="<?= htmlspecialchars($search) ?>">
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

        <!-- Issued Stock Table -->
        <table>
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>Issued Quantity</th>
                <th>Price (GH¢)</th>
                <th>Date Issued</th>
                <th>Description</th>
                <th>Issued By</th>
                <th>Transferred From</th>
                <th>Transferred To</th>
                <th>Voucher</th>
            </tr>
            
            <?php 
                $numb = $offset + 1; // Start numbering from the correct offset
                while ($issued = $issuedResult->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $numb++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($issued['item_name']) ?></td>
                <td><?= htmlspecialchars($issued['issued_quantity']) ?></td>
                <td style="text-align: right;"><?= number_format($issued['price'], 2) ?></td>
                <td><?= htmlspecialchars($issued['date_issued']) ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($issued['description']) ?></td>
                <td><?= htmlspecialchars($issued['username']) ?></td>
                <td><?= htmlspecialchars($issued['transferred_from']) ?></td>
                <td><?= htmlspecialchars($issued['transferred_to']) ?></td>
                <td><?= htmlspecialchars($issued['store_issue_voucher']) ?></td>
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