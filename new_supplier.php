<?php
    require 'header.php';
    require 'db_connect.php'; // Include database connection

    // Check if user is logged in and has the right role
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'storekeeper'])) {
        header("Location: index.php");
        exit();
    }

    $message = "";

    // Handle Add Supplier
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supplier'])) {
        $supplier_name = trim($_POST['supplier_name']);
        $contact_info = trim($_POST['contact_info']);

        // Insert into Suppliers table
        $stmt = $conn->prepare("INSERT INTO Suppliers (supplier_name, contact_info) VALUES (?, ?)");
        $stmt->bind_param("ss", $supplier_name, $contact_info);

        if ($stmt->execute()) {
            $message = "Supplier added successfully!";
        } else {
            $message = "Error adding supplier.";
        }
    }

    // Handle Update Supplier
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_supplier'])) {
        $supplier_id = $_POST['supplier_id'];
        $supplier_name = trim($_POST['supplier_name']);
        $contact_info = trim($_POST['contact_info']);

        $stmt = $conn->prepare("UPDATE Suppliers SET supplier_name = ?, contact_info = ? WHERE supplier_id = ?");
        $stmt->bind_param("ssi", $supplier_name, $contact_info, $supplier_id);

        if ($stmt->execute()) {
            $message = "Supplier updated successfully!";
        } else {
            $message = "Error updating supplier.";
        }
    }

    // Handle Delete Supplier
    if (isset($_GET['delete'])) {
        $supplier_id = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM Suppliers WHERE supplier_id = ?");
        $stmt->bind_param("i", $supplier_id);

        if ($stmt->execute()) {
            $message = "Supplier deleted successfully!";
        } else {
            $message = "Error deleting supplier.";
        }
    }

    // Fetch suppliers
    $suppliers = $conn->query("SELECT * FROM Suppliers");
?>

<head>
    <title>Manage Suppliers - GSA Store Management System</title>
</head>

    <div class="container">
        <h2>Add New Supplier</h2>

        <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>

        <!-- Add Supplier Form -->
        <form method="POST">
            <label>Supplier Name:</label>
            <input type="text" name="supplier_name" required>
            <label>Contact Info:</label>
            <input type="text" name="contact_info">
            <button type="submit" name="add_supplier">Add Supplier</button>
        </form>

<?php require 'footer.php'; ?>