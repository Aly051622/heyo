<?php
session_start();
include('includes/dbconnection.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "Please log in as an admin.";
    exit;
}

$adminId = $_SESSION['admin_id'];

try {
    // Fetch distinct users who have messaged the admin
    $query = "SELECT DISTINCT user_id, username FROM messages WHERE isSupport = 0 ORDER BY created_at DESC";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        echo "<h3>Users Who Messaged</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<strong>User:</strong> " . htmlspecialchars($row['username']) . " ";
            echo "<a href='view_messages.php?user_id=" . $row['user_id'] . "'>View Conversation</a>";
            echo "</div><hr>";
        }
    } else {
        echo "No users have messaged the admin yet.";
    }
} catch (Exception $e) {
    echo "Error fetching messages: " . $e->getMessage();
} finally {
    $con->close();
}
?>
