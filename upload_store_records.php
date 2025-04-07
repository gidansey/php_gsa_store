<?php
require 'header.php';
include 'db_connect.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Store Record</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Upload Store Record CSV</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        <label for="item_id">Select Item:</label>
        <select name="item_id" id="item_id" required>
            <?php
            $result = $conn->query("SELECT item_id, item_name FROM items");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['item_id'] . "'>" . $row['item_name'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="csv_file">Upload CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required><br><br>
        
        <button type="submit">Upload</button>
    </form>

    <div id="uploadStatus"></div>

    <script>
        $(document).ready(function () {
            $("#uploadForm").on("submit", function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                $("#uploadStatus").html("<p style='color: blue;'>Uploading...</p>");

                $.ajax({
                    url: "process_upload.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("#uploadStatus").html(response);
                    },
                    error: function () {
                        $("#uploadStatus").html("<p style='color: red;'>Error uploading file.</p>");
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php require 'footer.php'; ?>
