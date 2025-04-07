<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'storekeeper'])) {
        die("Access Denied");
    }

    $message = "";

    $items = $conn->query("SELECT item_id, item_name, current_price FROM Items");
    $suppliers = $conn->query("SELECT supplier_id, supplier_name FROM Suppliers");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $supplier_id = $_POST['supplier_id'];
        $received_items = $_POST['items'];
        $received_by = $_SESSION['user_id'];

        foreach ($received_items as $item) {
            $item_id = $item['item_id'];
            $received_quantity = $item['received_quantity'];
            $cost = $item['cost'];
            $amount = $received_quantity * $cost;

            if ($received_quantity > 0 && $cost > 0) {
                $stmt = $conn->prepare("INSERT INTO Received_Stock 
                    (item_id, supplier_id, received_quantity, cost, amount, received_by) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiidii", $item_id, $supplier_id, $received_quantity, $cost, $amount, $received_by);
                
                if ($stmt->execute()) {
                    $update_item = $conn->prepare("UPDATE Items SET quantity = quantity + ?, current_price = ? WHERE item_id = ?");
                    $update_item->bind_param("idi", $received_quantity, $cost, $item_id);
                    $update_item->execute();

                    $message = "Stock received successfully.";
                } else {
                    $message = "Error receiving stock.";
                }
                $stmt->close();
            } else {
                $message = "Invalid input values.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Receipt - GSA Store Management System</title>
    <script>
        function calculateAmount(row) {
            let qty = parseFloat(row.querySelector(".qty").value) || 0;
            let price = parseFloat(row.querySelector(".price").value) || 0;
            let amount = qty * price;
            row.querySelector(".amount").value = amount.toFixed(2);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            let totalItems = 0;
            document.querySelectorAll(".amount").forEach(el => {
                total += parseFloat(el.value) || 0;
            });
            document.querySelectorAll(".qty").forEach(el => {
                if (parseFloat(el.value) > 0) totalItems++;
            });
            document.getElementById("total").innerText = total.toFixed(2);
            document.getElementById("total-items").innerText = totalItems;
        }

        function addRow() {
            let table = document.getElementById("items-table");
            let rowCount = table.rows.length;
            let newRow = document.querySelector(".item-row").cloneNode(true);
            
            newRow.querySelectorAll("input, select").forEach(el => el.value = "");
            newRow.querySelector(".row-number").innerText = rowCount + 1; // Update row number
            
            table.appendChild(newRow);
            updateNumbers();
        }

        function updateNumbers() {
            let rows = document.querySelectorAll("#items-table .item-row");
            rows.forEach((row, index) => {
                row.querySelector(".row-number").innerText = index + 1;
            });
        }

        function updatePrice(selectElement) {
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            let price = selectedOption.getAttribute("data-price");
            let row = selectElement.closest("tr");
            row.querySelector(".price").value = price;
            calculateAmount(row);
        }

        function applySupplierToAll() {
            let supplier = document.getElementById("supplier").value;
            document.querySelectorAll(".supplier-input").forEach(el => {
                el.value = supplier;
            });
        }
    </script>
    <style>
        .container {
            max-width: 100%;
            margin: 5px auto;
            padding: 0 5px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 30px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            background: #fff;
            border-radius: 5px;
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

        .total-amount {
            text-align: right;
            margin-right: 20px;
        }

        /* Hide the default dropdown arrow */
        select {
            appearance: none; /* Removes default arrow */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px;
            cursor: pointer;
        }

        /* Add a custom dropdown style */
        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Ensure dropdown still works */
        select::after {
            content: "";
            display: none; /* Hide the default arrow */
        }

    </style>
</head>
<body>
    <h2>Store Receipt</h2>
    <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th style="text-align: center;">Item</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: center;">Cost per Unit</th>
                    <th style="text-align: center;">Amount</th>
                </tr>
            </thead>
            <tbody id="items-table">
                <tr class="item-row">
                    <td class="row-number">1</td>
                    <td style="text-align: left;">
                        <select name="items[0][item_id]" required onchange="updatePrice(this)">
                            <option value="">Select Item</option>
                            <?php while ($item = $items->fetch_assoc()): ?>
                                <option value="<?= $item['item_id'] ?>" data-price="<?= $item['current_price'] ?>">
                                    <?= $item['item_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td style="text-align: right;">
                        <input type="number" class="qty" name="items[0][received_quantity]" required min="1" 
                               oninput="calculateAmount(this.closest('tr'))" style="text-align: right;">
                    </td>
                    <td style="text-align: right;">
                        <input type="number" class="price" name="items[0][cost]" step="0.01" required min="0" 
                               oninput="calculateAmount(this.closest('tr'))" style="text-align: right;">
                    </td>
                    <td style="text-align: right;">
                        <input type="text" class="amount" name="items[0][amount]" readonly style="text-align: right;">
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <div>
                <label><strong>Supplier:</strong></label>
                <select name="supplier_id" id="supplier" required onchange="applySupplierToAll()">
                    <option value="">Select Supplier</option>
                    <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                        <option value="<?= $supplier['supplier_id'] ?>"><?= $supplier['supplier_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="total-amount">
                <strong>Total Items: <span id="total-items">0</span></strong> |
                <strong>Total Amount: GH¢ <span id="total">0.00</span></strong>
            </div>
        </div>

        <br>

        <button type="button" onclick="addRow()">Add</button>
        <label></label>
        <button type="submit">Receive Stock</button>
    </form>

</body>
</html>
<?php require 'footer.php'; ?>
