<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper'])) {
        header("Location: login.php");
        exit();
    }

    $message = "";

    // Fetch items from the database
    $items_query = "SELECT item_id, item_name, quantity, current_price FROM Items";
    $items_result = $conn->query($items_query);

    // Fetch users for requester and authorizer
    $users_query = "SELECT user_id, full_name FROM Users";
    $users_result = $conn->query($users_query);

    // Generate next store issue voucher number
    $last_voucher_query = $conn->query("SELECT store_issue_voucher FROM issued_stock ORDER BY store_issue_voucher DESC LIMIT 1");
    $last_voucher_row = $last_voucher_query->fetch_assoc();
    $last_voucher = $last_voucher_row['store_issue_voucher'] ?? 'SIV-000000';

    $last_number = intval(substr($last_voucher, 4));
    $new_number = $last_number + 1;
    $new_voucher = "SIV-" . str_pad($new_number, 6, '0', STR_PAD_LEFT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn->begin_transaction();
        try {
            $issued_items = $_POST['items'];
            $transferred_from = $_POST['transferred_from'] ?? '';
            $transferred_to = $_POST['transferred_to'] ?? '';
            $issued_by = $_SESSION['user_id'];
            $requester_id = $_POST['requester_id'];
            $authorizer_id = $_POST['authorizer_id'];
            $description = "Requester: $requester_id | Authorizer: $authorizer_id"; // Store in description field

            foreach ($issued_items as $item) {
                $item_id = $item['item_id'];
                $issued_quantity = $item['issued_quantity'];

                // Check stock availability
                $stock_query = $conn->prepare("SELECT quantity, current_price FROM Items WHERE item_id = ? FOR UPDATE");
                $stock_query->bind_param("i", $item_id);
                $stock_query->execute();
                $stock_result = $stock_query->get_result();
                $stock_row = $stock_result->fetch_assoc();
                $current_stock = $stock_row['quantity'];
                $unit_price = $stock_row['current_price'];

                if ($issued_quantity > $current_stock) {
                    throw new Exception("Insufficient stock for item ID $item_id!");
                }

                // Insert issued stock - using description field for requester/authorizer
                $issue_query = $conn->prepare("INSERT INTO issued_stock 
                    (item_id, issued_quantity, price, transferred_from, transferred_to, 
                    store_issue_voucher, issued_by, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $issue_query->bind_param("iidsssis", 
                    $item_id, $issued_quantity, $unit_price, 
                    $transferred_from, $transferred_to, 
                    $new_voucher, $issued_by, $description);
                $issue_query->execute();

                // Update stock
                $update_stock = $conn->prepare("UPDATE Items SET quantity = quantity - ? WHERE item_id = ?");
                $update_stock->bind_param("ii", $issued_quantity, $item_id);
                $update_stock->execute();
            }
            
            $conn->commit();
            $message = "Stock issued successfully! Voucher: $new_voucher";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Issue - GSA Store Management System</title>
    <script>
        // JavaScript functions remain the same
        function updatePrice(selectElement) {
            let row = selectElement.closest("tr");
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            let price = selectedOption.getAttribute("data-price");
            let stock = selectedOption.getAttribute("data-stock");
            row.querySelector(".price").value = price;
            row.querySelector(".stock-info").innerText = stock;
            calculateAmount(row);
        }
        
        function calculateAmount(row) {
            let qty = row.querySelector(".qty").value;
            let price = row.querySelector(".price").value;
            row.querySelector(".amount").value = (qty * price).toFixed(2);
            updateTotal();
        }
        
        function updateTotal() {
            let total = 0;
            document.querySelectorAll(".amount").forEach(el => total += parseFloat(el.value || 0));
            document.getElementById("total-amount").innerText = total.toFixed(2);
        }

        function addRow() {
            let table = document.querySelector('tbody');
            let rowCount = table.rows.length;
            let newRow = table.rows[0].cloneNode(true);
            
            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('.qty').value = '';
            newRow.querySelector('.price').value = '';
            newRow.querySelector('.amount').value = '';
            newRow.querySelector('.stock-info').innerText = '';
            
            newRow.querySelector('select').name = `items[${rowCount}][item_id]`;
            newRow.querySelector('.qty').name = `items[${rowCount}][issued_quantity]`;
            
            table.appendChild(newRow);
        }
    </script>
    <style>
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2, h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 30px auto;
        }

        form label {
            font-weight: 500;
            margin-top: 15px;
            display: block;
            color: #2c3e50;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        form input:focus, form select:focus, form textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        form button {
            width: 100%;
            padding: 14px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: #fff;
            font-weight: 600;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        @media (max-width: 768px) {
            form {
                width: 90%;
            }

            table {
                width: 100%;
                overflow-x: auto;
            }
        }

        .total-amount {
            text-align: right;
            margin-right: 20px;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px;
            cursor: pointer;
        }

        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        select::after {
            content: "";
            display: none;
        }

        select[name^="items"] {
            width: 100%;
        }

        input.qty, input.price, input.amount {
            width: 100px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h2>Store Issue Items</h2>
    <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Description</th>
                    <th>Available</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="item-row">
                    <td>
                        <select name="items[0][item_id]" required onchange="updatePrice(this)">
                            <option value="" disabled selected>Select an item</option>
                            <?php 
                            $items_result->data_seek(0); // Reset pointer
                            while ($item = $items_result->fetch_assoc()): ?>
                                <option value="<?= $item['item_id'] ?>" data-price="<?= $item['current_price'] ?>" data-stock="<?= $item['quantity'] ?>">
                                    <?= $item['item_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td class="stock-info"></td>
                    <td><input type="number" class="qty" name="items[0][issued_quantity]" required min="1" oninput="calculateAmount(this.closest('tr'))"></td>
                    <td><input type="text" class="price" readonly></td>
                    <td><input type="text" class="amount" readonly></td>
                    <td><button type="button" onclick="addRow()">+</button></td>
                </tr>
            </tbody>
        </table>
        <h3>Total: GH¢ <span id="total-amount">0.00</span></h3>

        <label>Requester:</label>
        <select name="requester_id" required>
            <option value="">Select Requester</option>
            <?php while ($user = $users_result->fetch_assoc()): ?>
                <option value="<?= $user['user_id'] ?>"><?= $user['full_name'] ?></option>
            <?php endwhile; ?>
        </select>
        
        <label>Authorizer:</label>
        <select name="authorizer_id" required>
            <option value="">Select Authorizer</option>
            <?php $users_result->data_seek(0); 
            while ($user = $users_result->fetch_assoc()): ?>
                <option value="<?= $user['user_id'] ?>"><?= $user['full_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Transferred From:</label>
        <input type="text" name="transferred_from" required>

        <label>Transferred To:</label>
        <input type="text" name="transferred_to" required>

        <label>Issuer:</label>
        <input type="text" value="<?= $_SESSION['full_name'] ?>" readonly>

        <button type="submit">Issue Stock</button>
    </form>
</body>
</html>
<?php require 'footer.php'; ?>