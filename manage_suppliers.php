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
        <!-- Supplier List -->
        <h3>Suppliers</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Supplier Name</th>
                <th>Contact Info</th>
            </tr>
            <?php 
                $number = 1; // Start numbering from 1
                while ($supplier = $suppliers->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $number++ ?></td>
                <td><?= $supplier['supplier_name'] ?></td>
                <td><?= $supplier['contact_info'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

<?php require 'footer.php'; ?>