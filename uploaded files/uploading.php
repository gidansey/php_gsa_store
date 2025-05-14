<?php
include 'db_connect.php'; // Ensure this file contains $conn for database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["item_id"]) && isset($_FILES["csv_file"])) {
        $item_id = intval($_POST["item_id"]);
        $file = $_FILES["csv_file"]["tmp_name"];

        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $date = $data[0]; // Assuming first column is the date
                $receipt = ($data[1] !== "") ? intval($data[1]) : NULL;
                $issue = ($data[2] !== "") ? intval($data[2]) : NULL;
                $unit_price = floatval($data[3]); // Assuming fourth column is unit price

                // Get current quantity to calculate balance
                $stmt = $conn->prepare("SELECT balance FROM store_records WHERE item_id = ? ORDER BY date DESC, id DESC LIMIT 1");
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $stmt->bind_result($last_balance);
                $stmt->fetch();
                $stmt->close();

                $last_balance = $last_balance ?? 0;
                $new_balance = $last_balance + ($receipt ?? 0) - ($issue ?? 0);
                $amount = ($receipt ?? 0) * $unit_price;

                // Insert into store_records
                $stmt = $conn->prepare("INSERT INTO store_records (item_id, date, receipt, issue, balance, unit_price, amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isiiidd", $item_id, $date, $receipt, $issue, $new_balance, $unit_price, $amount);
                $stmt->execute();
                $stmt->close();
            }
            fclose($handle);

            // Update current_price in items table
            $stmt = $conn->prepare("UPDATE items SET current_price = ? WHERE item_id = ?");
            $stmt->bind_param("di", $unit_price, $item_id);
            $stmt->execute();
            $stmt->close();

            $message = "CSV records uploaded successfully!";
        } else {
            $message = "Error opening CSV file!";
        }
    } else {
        $message = "Please select an item and upload a CSV file!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Store Records</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 50%; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9; }
        select, input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; border-radius: 4px; }
        button:hover { background: #218838; }
        .message { text-align: center; font-size: 18px; margin-top: 10px; color: green; }
    </style>
</head>
<body>

<h2 style="text-align: center;">Upload Store Records via CSV</h2>

<?php if (!empty($message)): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<form action="upload_store_records.php" method="post" enctype="multipart/form-data">
    <label>Select Item:</label>
    <select name="item_id" required>
        <option value="">-- Select Item --</option>
        <?php
        $result = $conn->query("SELECT item_id, item_name FROM items");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['item_id']}'>{$row['item_name']}</option>";
        }
        ?>
    </select>

    <label>Upload CSV File:</label>
    <input type="file" name="csv_file" accept=".csv" required>

    <button type="submit">Upload Records</button>
</form>

</body>
</html>
