<?php
require 'header.php';
include 'db_connect.php'; // Ensure this file contains $conn for database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["item_id"]) || empty($_FILES["csv_file"]["tmp_name"])) {
        $message = "Please select an item and upload a CSV file!";
    } else {
        $item_id = intval($_POST["item_id"]);
        $file = $_FILES["csv_file"]["tmp_name"];

        if (($handle = fopen($file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            try {
                $conn->begin_transaction(); // Start transaction

                // Get last recorded balance before processing
                $stmt = $conn->prepare("SELECT balance FROM store_records WHERE item_id = ? ORDER BY date DESC, id DESC LIMIT 1");
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $stmt->bind_result($last_balance);
                $stmt->fetch();
                $stmt->close();

                $last_balance = $last_balance ?? 0;

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $date = trim($data[1]); // Assuming second column is the date (yyyy-mm-dd format)

                    // Validate date format
                    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
                        error_log("Invalid date format: {$date}");
                        continue;
                    }

                    $receipt = !empty(trim($data[2])) ? intval($data[2]) : 0;
                    $issue = !empty(trim($data[4])) ? intval($data[4]) : 0;
                    $unit_price = floatval($data[3]); // Assuming fourth column is unit price
                    $supplier_id = !empty(trim($data[7])) ? intval($data[7]) : NULL; // Assuming supplier_id is in column 8

                    // Calculate new balance
                    $new_balance = $last_balance + $receipt - $issue;
                    $amount = $receipt * $unit_price;

                    // Insert into store_records
                    $stmt = $conn->prepare("INSERT INTO store_records (item_id, date, receipt, issue, balance, unit_price, amount, supplier_id) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isiiiddi", $item_id, $date, $receipt, $issue, $new_balance, $unit_price, $amount, $supplier_id);
                    $stmt->execute();
                    $stmt->close();

                    // Update last balance for the next iteration
                    $last_balance = $new_balance;
                }

                fclose($handle);

                // Update current price in items table
                $stmt = $conn->prepare("UPDATE items SET current_price = ? WHERE item_id = ?");
                $stmt->bind_param("di", $unit_price, $item_id);
                $stmt->execute();
                $stmt->close();

                $conn->commit(); // Commit transaction
                $message = "CSV records uploaded successfully!";
            } catch (Exception $e) {
                $conn->rollback(); // Rollback on failure
                error_log("Database error: " . $e->getMessage());
                $message = "Error processing the file. Please try again.";
            }
        } else {
            $message = "Error opening CSV file!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Store Records</title>
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

<?php require 'footer.php'; ?>
