<?php
	session_start();
	include 'db_connect.php';

	if (!isset($_SESSION['user_id'])) {
	    echo "error";
	    exit();
	}

	$user_id = $_SESSION['user_id'];

	// Update notifications as read
	$sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $user_id);

	if ($stmt->execute()) {
	    echo "success";
	} else {
	    echo "error";
	}
?>