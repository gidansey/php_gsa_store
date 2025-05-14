<?php
    require 'header.php';
    require 'db_connect.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch all notifications for the user
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .notification {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .notification.unread {
            background: #f9f9f9;
        }
        .notification .message {
            font-weight: bold;
        }
        .notification .timestamp {
            color: #777;
            font-size: 0.9em;
        }
        .notification a {
            text-decoration: none;
            color: #007bff;
        }
        .notification a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Notifications</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification <?= $row['is_read'] ? '' : 'unread'; ?>">
                    <div class="message">
                        <!-- Link notification to review_requisition.php -->
                        <a href="review_requisitions.php">
                            <?= htmlspecialchars($row['message']); ?>
                        </a>
                    </div>
                    <div class="timestamp"><?= htmlspecialchars($row['created_at']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notifications found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require 'footer.php'; ?>
