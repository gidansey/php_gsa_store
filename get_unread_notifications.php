<?php
    session_start();
    include 'db_connect.php';

    if (!isset($_SESSION['user_id'])) {
        echo 0;
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch unread notifications count
    $sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0 AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(["unreadCount" => $unreadCount]);

?>