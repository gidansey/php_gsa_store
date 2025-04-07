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
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch issued stock report
$issuedQuery = "SELECT isd.*, i.item_name, u.username 
                FROM issued_stock isd
                JOIN items i ON isd.item_id = i.item_id
                JOIN users u ON isd.issued_by = u.user_id";

// Add filters to the query
$conditions = [];
if ($start_date && $end_date) {
    $conditions[] = "isd.date_issued BETWEEN '$start_date' AND '$end_date'";
}
if ($search) {
    $conditions[] = "(i.item_name LIKE '%$search%' OR u.username LIKE '%$search%')";
}
if (!empty($conditions)) {
    $issuedQuery .= " WHERE " . implode(" AND ", $conditions);
}

// Add pagination to the query
$issuedQuery .= " ORDER BY isd.date_issued DESC LIMIT $limit OFFSET $offset";
$issuedResult = $conn->query($issuedQuery);

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM issued_stock isd
               JOIN items i ON isd.item_id = i.item_id
               JOIN users u ON isd.issued_by = u.user_id";
if (!empty($conditions)) {
    $countQuery .= " WHERE " . implode(" AND ", $conditions);
}
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Stock Report - GSA Store Management System</title>
    <style>
        /* Page-specific Styles */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Filter Form */
        .filter-form, .search-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 30px auto;
            text-align: center;
        }

        .filter-form label, .search-form label {
            font-weight: 500;
            color: #2c3e50;
        }

        .filter-form input, .filter-form button, .search-form input, .search-form button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .filter-form button, .search-form button {
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .filter-form button:hover, .search-form button:hover {
            background-color: #2980b9;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Pagination */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            background: #3498db;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
        }

        .pagination a.active {
            background: #2980b9;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid #ccc;
            }
            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }
            td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                content: attr(data-label);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Issued Stock Report</h2>

    <!-- Filter Form -->
    <form class="filter-form" method="GET">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

        <button type="submit">Filter</button>
    </form>

    <!-- Search Form -->
    <form class="search-form" method="GET">
        <input type="text" name="search" placeholder="Search by item or user..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

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
        $number = ($page - 1) * $limit + 1; // Initialize numbering
        while ($issued = $issuedResult->fetch_assoc()): 
        ?>
            <tr>
                <td><?= $number++ ?></td>
                <td><?= htmlspecialchars($issued['item_name']) ?></td>
                <td><?= htmlspecialchars($issued['issued_quantity']) ?></td>
                <td><?= number_format($issued['price'], 2) ?></td>
                <td><?= htmlspecialchars($issued['date_issued']) ?></td>
                <td><?= htmlspecialchars($issued['description']) ?></td>
                <td><?= htmlspecialchars($issued['username']) ?></td>
                <td><?= htmlspecialchars($issued['transferred_from']) ?></td>
                <td><?= htmlspecialchars($issued['transferred_to']) ?></td>
                <td><?= htmlspecialchars($issued['store_issue_voucher']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&search=<?= $search ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>
<?php require 'footer.php'; ?>