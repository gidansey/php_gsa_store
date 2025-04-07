<?php
    include 'db_connect.php'; // Database connection

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['size'] == 0) {
            die("<p style='color: red;'>File is empty!</p>");
        }

        $item_id = $_POST['item_id'];
        $file = $_FILES['csv_file']['tmp_name'];
        $batchSize = 500; // Process 500 records per batch
        $counter = 0;

        $handle = fopen($file, 'r');
        fgetcsv($handle); // Skip the first row (header)

        $query = "INSERT INTO store_records (item_id, date, id, receipt, unit_price, issue, balance, amount, supplier_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("<p style='color: red;'>Query Preparation Failed: " . $conn->error . "</p>");
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $id = $data[0];
            $date = $data[1];            
            $receipt = $data[2];
            $unit_price = $data[3];
            $issue = $data[4];
            $balance = $data[5];
            $amount = $data[6];
            $supplier_id = $data[7];

            $stmt->bind_param("isssdddsi", $item_id, $date, $id, $receipt, $unit_price, $issue, $balance, $amount, $supplier_id);
            $stmt->execute();

            $counter++;

            // Update current_price in batches
            if ($counter % $batchSize == 0) {
                updateCurrentPrice($conn, $item_id);
            }
        }
        fclose($handle);

        // Final price update after all records are inserted
        updateCurrentPrice($conn, $item_id);

        echo "<p style='color: green;'>File uploaded successfully and item prices updated!</p>";
    }

    // Function to update the current price of an item
    function updateCurrentPrice($conn, $item_id) {
        $updateQuery = "UPDATE items 
                        SET current_price = (SELECT unit_price FROM store_records 
                                             WHERE item_id = ? 
                                             ORDER BY date DESC, id DESC 
                                             LIMIT 1)
                        WHERE item_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt) {
            $updateStmt->bind_param("ii", $item_id, $item_id);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }
?>
