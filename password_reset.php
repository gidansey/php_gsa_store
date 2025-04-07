<?php
    session_start();
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'gsa_store_management';

    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $new_password = trim($_POST['new_password']);

        // Check if the username exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user['user_id']);

            if ($update_stmt->execute()) {
                $success = "Password updated successfully! You can now <a href='index.php'>login</a>.";
            } else {
                $error = "Error updating password. Please try again.";
            }
        } else {
            $error = "Username not found.";
        }

        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Reset Password - GSA Store Management System</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .reset-container {
            width: 100%;
            max-width: 350px; /* Restricts width */
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }


        .reset-container img {
            width: 100px;
            margin-bottom: 15px;
        }

        .reset-container h2 {
            font-weight: 600;
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }

        input {
            width: calc(100%); /* Ensures input fits within container */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Prevents overflow */
        }


        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            font-size: 14px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .login-link {
            margin-top: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <img src="gsa_logo.svg" alt="GSA Logo">
        <h2>Reset Your Password</h2>
        
        <?php 
            if (isset($error)) echo "<p class='message error'>$error</p>"; 
            if (isset($success)) echo "<p class='message success'>$success</p>"; 
        ?>
        
        <form method="POST">
            <input type="text" name="username" required placeholder="Enter Username">
            <input type="password" name="new_password" required placeholder="Enter New Password">
            <button type="submit">Reset Password</button>
        </form>

        <a class="login-link" href="index.php">Back to Login</a>
    </div>
</body>
</html>
