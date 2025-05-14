<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role']; // Assume this is stored in session

    // Check if the user is an Admin or Storekeeper
    if ($role === 'admin' || $role === 'storekeeper') {
        $query = "SELECT r.request_id, i.item_name, r.date_requested, r.requested_quantity, r.status, r.remarks, r.requested_by 
                  FROM requisition_requests r 
                  JOIN items i ON r.item_id = i.item_id
                  ORDER BY r.date_requested DESC";
        $stmt = $conn->prepare($query);
    } else {
        // Fix: Add item_name for viewers
        $query = "SELECT r.request_id, i.item_name, r.date_requested, r.status 
                  FROM requisition_requests r 
                  JOIN items i ON r.item_id = i.item_id 
                  WHERE r.requested_by = ? 
                  ORDER BY r.date_requested DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    }

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requisitions - GSA Store Management System</title>
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                    body: `request_id=${requestId}&status=${newStatus}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        alert("Requisition updated successfully!");
                        location.reload(); // Refresh to update the status
                    } else {
                        alert("Error updating requisition.");
                    }
                })
                .catch(error => console.error("Error:", error));
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
                                <td><?= htmlspecialchars($row['requested_by']) ?></td>
                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Pending'): ?>
                                        <button class="approve-btn" data-id="<?= $row['request_id'] ?>">Approve</button>
                                        <button class="dismiss-btn" data-id="<?= $row['request_id'] ?>">Dismiss</button>
                                    <?php else: ?>
                                        <?= htmlspecialchars($row['status']) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php require 'footer.php'; ?>
