<?php
// Include the database connection
include('includes/dbconnection.php');

// Start the session to check admin login
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "Please log in as an admin.";
    exit;
}

// Fetch all user messages (isSupport = 0)
$query = "SELECT user_id, username, message, created_at FROM messages WHERE isSupport = 0 ORDER BY created_at ASC";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
    // Display the messages
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<strong>" . htmlspecialchars($row['username']) . ":</strong> ";
        echo "<p>" . htmlspecialchars($row['message']) . "</p>";
        echo "<small>Sent on: " . $row['created_at'] . "</small>";
        echo "</div><hr>";
    }
} else {
    echo "No users have messaged the admin yet.";
}

$con->close();
?>
