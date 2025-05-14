<?php
	session_start();
	include 'db_connect.php';

	$user_role = $_SESSION['role'];
	$user_id = $_SESSION['user_id']; // Ensure session has user ID

	// Fetch notifications for both Admin and Storekeeper
	if ($user_role === 'admin' || $user_role === 'storekeeper') {
	    $query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10";
	} else {
	    $query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
	}

	$stmt = $conn->prepare($query);

	if ($user_role !== 'admin' && $user_role !== 'storekeeper') {
	    $stmt->bind_param("i", $user_id);
	}

	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
	        echo "<li>" . htmlspecialchars($row['message']) . " <small>" . $row['created_at'] . "</small></li>";
	    }
	} else {
	    echo "<li>No new notifications</li>";
	}

	$stmt->close();
	$conn->close();
?>
