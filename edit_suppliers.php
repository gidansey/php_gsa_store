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
    <style>
        .container {
            max-width: 60%; 
            margin: 20px auto; 
            padding: 10px; 
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
        }

        .filter-form, .table-container { 
            width: 100%; 
            text-align: center; 
        }

        .filter-form input, .filter-form button { 
            margin: 5px; 
            padding: 8px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td { 
            padding: 10px; 
            border: 1px solid #ddd; 
            text-align: center; 
        }

        th { 
            background: #2c3e50; 
            color: white; 
        }

        tr:hover { 
            background: #f5f5f5; 
        }

        .table-container { 
            overflow-x: auto; 
        }

        .pagination { 
            text-align: center; 
            margin-top: 10px; 
        }

        .pagination button { 
            padding: 5px 10px; 
            margin: 2px; 
            cursor: pointer; 
        }

        @media (max-width: 768px) { 
            table { font-size: 14px; } 
        }

        .actions a, .actions button {
            margin: 5px;
            text-decoration: none;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn {
            background-color: red;
            color: white;
        }

        .edit-btn {
            background-color: blue;
            color: white;
        }
    </style>
</head>

<div class="container">
    <!-- Supplier List -->
    <h3>Suppliers</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Supplier Name</th>
            <th>Contact Info</th>
            <th>Actions</th>
        </tr>
        <?php 
        $number = 1; // Start numbering from 1
        while ($supplier = $suppliers->fetch_assoc()): 
        ?>
            <tr>
                <td><?= $number++ ?></td>
                <td style="text-align: left;"><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                <td><?= htmlspecialchars($supplier['contact_info']) ?></td>
                <td class="actions">
                    <a href="?delete=<?= $supplier['supplier_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    <button class="edit-btn" onclick="editSupplier(<?= $supplier['supplier_id'] ?>, '<?= htmlspecialchars($supplier['supplier_name']) ?>', '<?= htmlspecialchars($supplier['contact_info']) ?>')">Edit</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Edit Supplier Form (hidden by default) -->
    <div id="editForm" style="display:none;">
        <h3>Edit Supplier</h3>
        <form method="POST">
            <input type="hidden" name="supplier_id" id="editSupplierId">
            <label>Supplier Name:</label>
            <input type="text" name="supplier_name" id="editSupplierName" required>
            <label>Contact Info:</label>
            <input type="text" name="contact_info" id="editContactInfo">
            <button type="submit" name="update_supplier">Update Supplier</button>
        </form>
    </div>
</div>

<script>
    function editSupplier(id, name, contact) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('editSupplierId').value = id;
        document.getElementById('editSupplierName').value = name;
        document.getElementById('editContactInfo').value = contact;
    }
</script>

<?php require 'footer.php'; ?>
