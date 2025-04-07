<?php
	include 'db_connect.php';

	$query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE is_read = 0";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);

	echo $row['unread_count'];
?>
