<?php
    
    require 'header.php';
    require 'db_connect.php'; // Include database connection

    // Check if the user is logged in and has the required role
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'storekeeper')) {
        header("Location: login.php");
        exit();
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $item_name = trim($_POST['item_name']);
        $description = trim($_POST['description']);
        $unit = trim($_POST['unit']);
        $current_price = floatval($_POST['current_price']);
        $quantity = intval($_POST['quantity']);
        $supplier_id = intval($_POST['supplier_id']);
        
        if (!empty($item_name) && !empty($unit) && $current_price > 0 && $quantity >= 0) {
            $stmt = $conn->prepare("INSERT INTO Items (item_name, description, unit, current_price, quantity, supplier_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdis", $item_name, $description, $unit, $current_price, $quantity, $supplier_id);
            
            if ($stmt->execute()) {
                $message = "Item added successfully!";
            } else {
                $error = "Error adding item: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Invalid input. Please check your values.";
        }
    }

    // Fetch suppliers for dropdown
    $suppliers = $conn->query("SELECT supplier_id, supplier_name FROM Suppliers");
?>
    
    <title>Create Item - GSA Store Management System</title>

    <h2>Create New Item</h2>
    <?php if (isset($message)) echo "<p style='color: green;'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <label>Item Name:</label>
        <input type="text" name="item_name" required>
        
        <label>Description:</label>
        <textarea name="description"></textarea>
        
        <label>Unit:</label>
        <input type="text" name="unit" required>
        
        <label>Price:</label>
        <input type="number" name="current_price" step="0.01" required>
        
        <label>Quantity:</label>
        <input type="number" name="quantity" required>
        
        <label>Supplier:</label>
        <select name="supplier_id">
            <option value="">Select Supplier</option>
            <?php while ($row = $suppliers->fetch_assoc()) { ?>
                <option value="<?= $row['supplier_id'] ?>"><?= $row['supplier_name'] ?></option>
            <?php } ?>
        </select>
        
        <button type="submit">Create Item</button>
    </form>


<?php require 'footer.php'; ?>
