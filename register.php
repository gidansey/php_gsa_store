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
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $role = $_POST['role']; // 'admin', 'storekeeper', or 'viewer'

        $stmt = $conn->prepare("INSERT INTO Users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location='index.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GSA Store Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }

        .register-container img {
            width: 100px;
            margin-bottom: 15px;
        }

        .register-container h2 {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .register-container input, .register-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        .register-container button:hover {
            background-color: #0056b3;
        }

        .register-link {
            margin-top: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="gsa_logo.svg" alt="GSA Logo">
        <h2>GSA Store Management System Registration</h2>
        <form method="POST">
            <input type="text" name="username" required placeholder="Enter Username">
            <input type="password" name="password" required placeholder="Enter Password">
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="storekeeper">Storekeeper</option>
                <option value="viewer">Viewer</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <a href="index.php" class="register-btn">Already registered? Login</a>
    </div>
</body>
</html>
