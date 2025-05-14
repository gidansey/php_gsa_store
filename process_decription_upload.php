<?php
session_start();
require 'db_connect.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $item_id = $_POST['item_id']; // Get selected item_id
    $file = $_FILES["csv_file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Skip header row

        $updated = 0;
        $not_found = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $issued_quantity = trim($data[0]);
            $date_issued = trim($data[1]);
            $description = trim($data[2]);

            // Check if matching record exists
            $stmt = $conn->prepare("SELECT issue_id FROM issued_stock WHERE item_id = ? AND issued_quantity = ? AND date_issued = ?");
            $stmt->bind_param("iis", $item_id, $issued_quantity, $date_issued);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $issue_id = $row['issue_id'];

                    // Update description
                    $update_stmt = $conn->prepare("UPDATE issued_stock SET description = ? WHERE issue_id = ?");
                    $update_stmt->bind_param("si", $description, $issue_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    $updated++;
                }
            } else {
                $not_found++;
            }
            $stmt->close();
        }

        fclose($handle);

        // Return response message
        if ($updated > 0) {
            echo "<p style='color: green;'>Successfully updated $updated record(s).</p>";
        }
        if ($not_found > 0) {
            echo "<p style='color: orange;'>$not_found record(s) not found.</p>";
        }
    } else {
        echo "<p style='color: red;'>Error opening file.</p>";
    }
} else {
    echo "<p style='color: red;'>Invalid request.</p>";
}

$conn->close();
?>
