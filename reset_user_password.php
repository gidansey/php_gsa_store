<?php
require 'header.php';
require_once "db_connect.php"; // Ensure you have a database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $new_password = $_POST["new_password"];

    // Validate inputs
    if (empty($user_id) || empty($new_password)) {
        $error_message = "All fields are required!";
    } else {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in database
        $query = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $success_message = "Password reset successfully!";
        } else {
            $error_message = "Error resetting password!";
        }
        $stmt->close();
    }
}

// Fetch all users for selection
$query = "SELECT user_id, username, full_name FROM users ORDER BY full_name ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset User Password</title>
    <!-- <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; }
        label { font-weight: bold; display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; }
        button { background: #007bff; color: #fff; padding: 10px; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style> -->
</head>
<body>

<div class="container">
    <h2>Reset User Password</h2>

    <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

    <form method="POST" action="">
        <label for="user_id">Select User:</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Choose a User --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['user_id']; ?>"><?= htmlspecialchars($row['full_name']) . " (" . htmlspecialchars($row['username']) . ")"; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>

        <button type="submit">Reset Password</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>

<?php require 'footer.php'; ?>
