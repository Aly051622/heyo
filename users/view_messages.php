<?php
session_start();
include('includes/dbconnection.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "Please log in as an admin.";
    exit;
}

// Check if user ID is provided
if (!isset($_GET['user_id'])) {
    echo "No user selected.";
    exit;
}

$userId = (int)$_GET['user_id'];

// Fetch messages for the selected user
$stmt = $con->prepare("SELECT username, message, isSupport, created_at 
                       FROM messages 
                       WHERE user_id = ? 
                       ORDER BY created_at ASC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h3>Conversation</h3>";
    while ($row = $result->fetch_assoc()) {
        $sender = $row['isSupport'] ? 'Admin' : htmlspecialchars($row['username']);
        echo "<div>";
        echo "<strong>$sender:</strong> ";
        echo "<p>" . htmlspecialchars($row['message']) . "</p>";
        echo "<small>Sent on: " . $row['created_at'] . "</small>";
        echo "</div><hr>";
    }
} else {
    echo "No messages found for this user.";
}

$stmt->close();
$con->close();
?>
