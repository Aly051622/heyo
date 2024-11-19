<?php
// Include the database connection
include('includes/dbconnection.php');

// Start the session to check if the admin is logged in
session_start();

// Check if admin is logged in (you can use $_SESSION variables or authentication method here)
if (!isset($_SESSION['admin_id'])) {
    echo "Please log in as an admin.";
    exit;
}

$adminId = $_SESSION['admin_id'];  // Admin ID from session
$adminUsername = $_SESSION['admin_username'];  // Admin username from session

// Fetch all user messages (isSupport = 0 for user messages)
$query = "SELECT messages.username, messages.message, messages.created_at 
          FROM messages 
          WHERE messages.isSupport = 0 
          ORDER BY messages.created_at ASC";

$result = $con->query($query);

if ($result->num_rows > 0) {
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
