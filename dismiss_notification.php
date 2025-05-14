<?php
    session_start();
    include 'db_connect.php';

    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit();
    }

    $notification_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Mark the notification as dismissed
    $sql = "UPDATE notifications SET dismissed_by = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $notification_id);
    $stmt->execute();
    $stmt->close();
?>