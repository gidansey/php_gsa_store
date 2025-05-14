<?php
    $host = 'localhost';
    $user = 'root'; // Change this if using a different DB user
    $password = ''; // Set your MySQL password if applicable
    $database = 'gsa_store_management';

    // Create a database connection
    $conn = new mysqli($host, $user, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Ensure UTF-8 encoding
    $conn->set_charset("utf8");
?>
