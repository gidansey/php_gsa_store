<?php
    session_start();

    // Redirect to login if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GSA Store Management System/title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
    <p>Your role: <b><?= htmlspecialchars($role) ?></b></p>

    <a href="logout.php">Logout</a>
</body>
</html>
