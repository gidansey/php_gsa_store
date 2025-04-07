<?php
    session_start();
    require 'db_connect.php';

    // Set response header to JSON
    header('Content-Type: application/json');

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        exit();
    }

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']);
        exit();
    }

    // Validate request ID and status
    if (!isset($_POST['request_id']) || !isset($_POST['status'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters.']);
        exit();
    }

    $request_id = intval($_POST['request_id']);
    $new_status = trim($_POST['status']);

    // Validate status value
    if (!in_array($new_status, ['Approved', 'Dismissed'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status value.']);
        exit();
    }

    // Fetch the requested item details
    $query = "SELECT item_id, requested_quantity FROM requisition_requests WHERE request_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Requisition not found.']);
        exit();
    }

    $row = $result->fetch_assoc();
    $item_id = $row['item_id'];
    $requested_quantity = $row['requested_quantity'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        if ($new_status === "Approved") {
            // Check the available stock
            $query = "SELECT quantity FROM items WHERE item_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $item = $result->fetch_assoc();

            if ($item['quantity'] < $requested_quantity) {
                throw new Exception("Not enough stock available.");
            }

            // Reduce the stock quantity
            $update_stock = "UPDATE items SET quantity = quantity - ? WHERE item_id = ?";
            $stmt = $conn->prepare($update_stock);
            $stmt->bind_param("ii", $requested_quantity, $item_id);
            $stmt->execute();
        }

        // Update the requisition status
        $query = "UPDATE requisition_requests SET status = ? WHERE request_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_status, $request_id);

        if (!$stmt->execute()) {
            throw new Exception("Error updating requisition status.");
        }

        // Commit the transaction
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Requisition updated successfully.']);
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } finally {
        // Close the database connection
        $stmt->close();
        $conn->close();
    }
?>