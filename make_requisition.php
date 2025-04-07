<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'viewer') {
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $message = "";

    // Fetch available items
    $items_query = "SELECT item_id, item_name FROM items";
    $items_result = $conn->query($items_query);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST['items']) || empty($_POST['items'])) {
            $message = "Error: No items selected for requisition.";
        } else {
            $requested_items = $_POST['items'];
            $success = false;
            $conn->begin_transaction();

            try {
                foreach ($requested_items as $item) {
                    $item_id = intval($item['item_id']);
                    $requested_quantity = intval($item['requested_quantity']);
                    $description = trim($item['description']);

                    // Insert into requisition_requests
                    $insert_request = $conn->prepare("INSERT INTO requisition_requests (item_id, requested_quantity, requested_by, remarks) VALUES (?, ?, ?, ?)");
                    $insert_request->bind_param("iiis", $item_id, $requested_quantity, $user_id, $description);
                    $insert_request->execute();
                }

                // Fetch admin/storekeeper user IDs
                $admin_storekeeper_query = "SELECT user_id FROM users WHERE role IN ('admin', 'storekeeper')";
                $admin_storekeeper_result = $conn->query($admin_storekeeper_query);

                // Insert notifications for all admins/storekeepers
                $notification_message = "New stock requisition request by $username.";
                while ($row = $admin_storekeeper_result->fetch_assoc()) {
                    $admin_user_id = $row['user_id'];
                    $insert_notification = $conn->prepare("INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())");
                    $insert_notification->bind_param("is", $admin_user_id, $notification_message);
                    $insert_notification->execute();
                }

                $conn->commit();
                $message = "Requisition request submitted successfully!";
            } catch (Exception $e) {
                $conn->rollback();
                $message = "Error: " . $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Requisition - GSA Store Management System</title>
    <script>
        function addRow() {
            let table = document.getElementById("items-table");
            let rowIndex = table.rows.length;
            let row = document.querySelector(".item-row").cloneNode(true);

            row.querySelectorAll("input, select, textarea").forEach(el => {
                let name = el.getAttribute("name");
                if (name) {
                    el.setAttribute("name", name.replace(/\d+/, rowIndex)); // Update index for new row
                    el.value = ""; // Clear previous value
                }
            });

            table.appendChild(row);
        }
    </script>
    <style>
        .container { 
            max-width: 1000px; 
            margin: 30px auto; 
            padding: 20px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }
        form { 
            max-width: 1200px;
            background: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
        }

        table { 
            width: 100%; 
            border-collapse: collapse;
            margin: 20px 0; 
        }

        th, td { 
            padding: 10px; 
            text-align: center; 
            border-bottom: 1px solid #ddd; 
        }

        button { 
            width: 100%; 
            padding: 10px; 
            background: #3498db; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }

        button:hover { 
            background: #2980b9; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Make a Requisition</h2>
        <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Purpose of Request</th>
                    </tr>
                </thead>
                <tbody id="items-table">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][item_id]" required>
                                <option value="" disabled selected>Select an item</option>
                                <?php while ($item = $items_result->fetch_assoc()): ?>
                                    <option value="<?= $item['item_id'] ?>">
                                        <?= $item['item_name'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td><input type="number" name="items[0][requested_quantity]" required min="1"></td>
                        <td><textarea name="items[0][description]" rows="2" required></textarea></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addRow()">Add Another Item</button>
            <label></label>
            <button type="submit">Submit Requisition</button>
        </form>
    </div>
</body>
</html>
<?php require 'footer.php'; ?>