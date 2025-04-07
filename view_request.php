<?php
require 'header.php';
require 'db_connect.php';

// Ensure only admin or storekeeper can access this page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'storekeeper') {
    header("Location: dashboard.php");
    exit();
}

// Check if request ID is provided
if (!isset($_GET['request_id'])) {
    header("Location: view_requisitions.php");
    exit();
}

$request_id = intval($_GET['request_id']);

// Fetch request details
$sql = "SELECT * FROM issued_stock WHERE issue_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Request not found.</p>";
    require 'footer.php';
    exit();
}

$request = $result->fetch_assoc();

// Handle approval or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // 'approve' or 'reject'
    $remarks = trim($_POST['remarks']);

    if ($action === 'approve' || $action === 'reject') {
        $status = ($action === 'approve') ? 'Approved' : 'Rejected';
        
        // Update the request status and remarks
        $update_sql = "UPDATE issued_stock SET status = ?, remarks = ? WHERE issue_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $status, $remarks, $request_id);

        if ($update_stmt->execute()) {
            $message = "Request has been $status.";
        } else {
            $message = "Error updating request: " . $update_stmt->error;
        }
    } else {
        $message = "Invalid action.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requisition Request</title>
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .request-details {
            margin-bottom: 20px;
        }
        .request-details p {
            margin: 10px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .action-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .approve-btn {
            background: #28a745;
            color: white;
        }
        .reject-btn {
            background: #dc3545;
            color: white;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Requisition Request Details</h2>
        <?php if (isset($message)): ?>
            <p style="color: <?= $action === 'approve' ? 'green' : 'red'; ?>"><?= $message; ?></p>
        <?php endif; ?>

        <div class="request-details">
            <p><strong>Request ID:</strong> <?= htmlspecialchars($request['issue_id']); ?></p>
            <p><strong>Item ID:</strong> <?= htmlspecialchars($request['item_id']); ?></p>
            <p><strong>Quantity:</strong> <?= htmlspecialchars($request['issued_quantity']); ?></p>
            <p><strong>Date Issued:</strong> <?= htmlspecialchars($request['date_issued']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($request['description']); ?></p>
            <p><strong>Price:</strong> <?= htmlspecialchars($request['price']); ?></p>
            <p><strong>Transferred From:</strong> <?= htmlspecialchars($request['transferred_from']); ?></p>
            <p><strong>Transferred To:</strong> <?= htmlspecialchars($request['transferred_to']); ?></p>
            <p><strong>Store Issue Voucher:</strong> <?= htmlspecialchars($request['store_issue_voucher']); ?></p>
            <p><strong>Issued By:</strong> <?= htmlspecialchars($request['issued_by']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($request['status']); ?></p>
            <p><strong>Remarks:</strong> <?= htmlspecialchars($request['remarks']); ?></p>
        </div>

        <form method="POST">
            <textarea name="remarks" placeholder="Enter remarks (optional)"></textarea>
            <div class="action-buttons">
                <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php require 'footer.php'; ?>