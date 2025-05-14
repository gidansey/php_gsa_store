<?php
//session_start();
require 'db_connect.php'; // Ensure this file correctly establishes database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$search_query = mysqli_real_escape_string($conn, $search_query);
$category = mysqli_real_escape_string($conn, $category);

$sql = "SELECT * FROM store_items WHERE (item_name LIKE ? OR description LIKE ?)";
$params = ["%$search_query%", "%$search_query%"];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing statement: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="dashboard.php">Back to Dashboard</a>
    </header>
    <div class="container">
        <h2>Search Results</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Quantity</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['item_name']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td><?= htmlspecialchars($row['quantity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No items found matching your search.</p>
        <?php endif; ?>
    </div>
</body>
</html>
