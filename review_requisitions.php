<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role']; // Assume this is stored in session

    // Pagination logic
    $records_per_page = 50; // Number of records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($current_page - 1) * $records_per_page; // Offset for SQL query

    // Check if the user is an Admin or Storekeeper
    if ($role === 'admin' || $role === 'storekeeper') {
        // Fetch total number of records
        $total_records_query = "SELECT COUNT(*) AS total FROM requisition_requests";
        $total_records_result = $conn->query($total_records_query);
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Fetch paginated records
        $query = "SELECT r.request_id, i.item_name, r.date_requested, r.requested_quantity, r.status, r.remarks, u.full_name 
                  FROM requisition_requests r 
                  JOIN items i ON r.item_id = i.item_id
                  JOIN users u ON r.requested_by = u.user_id
                  ORDER BY r.date_requested DESC
                  LIMIT $records_per_page OFFSET $offset";
        $stmt = $conn->prepare($query);
    } else {
        // Fetch total number of records for viewers
        $total_records_query = "SELECT COUNT(*) AS total FROM requisition_requests WHERE requested_by = ?";
        $total_records_stmt = $conn->prepare($total_records_query);
        $total_records_stmt->bind_param("i", $user_id);
        $total_records_stmt->execute();
        $total_records_result = $total_records_stmt->get_result();
        $total_records = $total_records_result->fetch_assoc()['total'];

        // Fetch paginated records for viewers
        $query = "SELECT r.request_id, i.item_name, r.date_requested, r.status, u.full_name 
                  FROM requisition_requests r 
                  JOIN items i ON r.item_id = i.item_id 
                  JOIN users u ON r.requested_by = u.user_id
                  WHERE r.requested_by = ? 
                  ORDER BY r.date_requested DESC
                  LIMIT $records_per_page OFFSET $offset";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    }

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requisitions - GSA Store Management System</title>
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

        /* Style for action buttons */
        .approve-btn, .dismiss-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .approve-btn {
            background-color: #28a745; /* Green */
            color: white;
        }

        .dismiss-btn {
            background-color: #dc3545; /* Red */
            color: white;
        }

        .approve-btn:hover {
            background-color: #218838; /* Darker green */
            transform: scale(1.05);
        }

        .dismiss-btn:hover {
            background-color: #c82333; /* Darker red */
            transform: scale(1.05);
        }

        .approve-btn:active, .dismiss-btn:active {
            transform: scale(0.95);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const csrfToken = '<?= $_SESSION['csrf_token'] ?>'; // Pass CSRF token to JavaScript

            document.querySelectorAll(".approve-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let requestId = this.getAttribute("data-id");
                    updateRequisitionStatus(requestId, "Approved");
                });
            });

            document.querySelectorAll(".dismiss-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let requestId = this.getAttribute("data-id");
                    updateRequisitionStatus(requestId, "Dismissed");
                });
            });

            function updateRequisitionStatus(requestId, newStatus) {
                fetch("update_requisition_status.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `request_id=${requestId}&status=${newStatus}&csrf_token=${csrfToken}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect the updated status
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred. Please try again.");
                });
            }
        });
    </script>

</head>
<body>
    <div class="container">
        <h2>Requisitions</h2>
        <table>
            <thead>
                <tr>
                    <th>Requisition ID</th>
                    <th>Request Date</th>
                    <th>Requested Item</th> <!-- New Column for Viewers -->
                    <th>Status</th>
                    <?php if ($role === 'admin' || $role === 'storekeeper'): ?>
                        <th>Requested Quantity</th>
                        <th>Requested By</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="8">No requisitions found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['request_id']) ?></td>
                            <td><?= htmlspecialchars($row['date_requested']) ?></td>
                            <td><?= htmlspecialchars($row['item_name']) ?></td> <!-- Display Requested Item -->
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <?php if ($role === 'admin' || $role === 'storekeeper'): ?>
                                <td><?= htmlspecialchars($row['requested_quantity']) ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td> <!-- Display Full Name -->
                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Pending'): ?>
                                        <button class="approve-btn" data-id="<?= $row['request_id'] ?>">Approve</button>
                                        <button class="dismiss-btn" data-id="<?= $row['request_id'] ?>">Dismiss</button>
                                    <?php else: ?>
                                        <span style="color: <?= $row['status'] === 'Approved' ? '#28a745' : '#dc3545'; ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
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