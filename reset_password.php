<?php

    // Redirect to login if user is not logged in
    // if (!isset($_SESSION['user_id'])) {
    //     header("Location: index.php");
    //     exit();
    // }

    require 'header.php';

    require 'db_connect.php'; // Database connection

    $success = $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } else {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            
            if (!password_verify($current_password, $hashed_password)) {
                $error = "Current password is incorrect.";
            } else {
                $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);
                if ($update_stmt->execute()) {
                    $success = "Password updated successfully.";
                } else {
                    $error = "Error updating password. Please try again.";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 400px; margin: auto; }
        .form-container { background: #f4f4f4; padding: 20px; border-radius: 5px; }
        input[type="password"], input[type="submit"] { width: 100%; padding: 10px; margin-top: 10px; }
        .error { color: red; }
        .success { color: green; }
    </style> -->
</head>
<body>
    <h2>Reset Password</h2>
    <div class="form-container">
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <form method="post">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
<?php require 'footer.php'; ?>