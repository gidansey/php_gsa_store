<?php
    require 'header.php';
    require 'db_connect.php'; // Include database connection

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper', 'viewer'])) {
        header("Location: index.php");
        exit();
    }

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Query to get total number of records
    $countQuery = "SELECT COUNT(*) AS total_records FROM Items";
    $countResult = $conn->query($countQuery);
    $total_records = $countResult->fetch_assoc()['total_records'];
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages

    // Fetch current stock report with pagination
    $stockQuery = "SELECT * FROM Items LIMIT $offset, $records_per_page";
    $stockResult = $conn->query($stockQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Report - GSA Store Management System</title>
    <style>
        .container {
            max-width: 70%; 
            margin: 20px auto; 
            padding: 10px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
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

        @media (max-width: 768px) { 
            table { font-size: 14px; } 
        }
    </style>
    <script>
        function searchTable() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>GSA Store Ledger</h2>
        <input type="text" id="search" onkeyup="searchTable()" placeholder="Search for items...">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Current Price (GH¢)</th>
                    <th>Quantity</th>
                    <th>Amount (GH¢)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $totalAmount = 0;
                    $num = $offset + 1; // Start numbering from the correct offset
                    while ($stock = $stockResult->fetch_assoc()): 
                        $amount = $stock['current_price'] * $stock['quantity'];
                        $totalAmount += $amount;
                ?>
                    <tr>
                        <td><?= $num++ ?></td>
                        <td style="text-align: left;"><?= htmlspecialchars($stock['item_name']) ?></td>
                        <td><?= htmlspecialchars($stock['unit']) ?></td>
                        <td style="text-align: right;"><?= number_format($stock['current_price'], 2) ?></td>
                        <td><?= htmlspecialchars($stock['quantity']) ?></td>
                        <td style="text-align: right;"><?= number_format($amount, 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right;">Total Cost of Items</th>
                    <th style="text-align: right;"><?= number_format($totalAmount, 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>">Previous</a>
            <?php else: ?>
                <a class="disabled">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>">Next</a>
            <?php else: ?>
                <a class="disabled">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php require 'footer.php'; ?>