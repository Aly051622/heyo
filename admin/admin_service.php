<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/dbconnection.php');

// Start the session to access the logged-in user's data
session_start();

// Check if the user is logged in (e.g., checking if email is set in the session)
if (!isset($_SESSION['email'])) {
    die("You must be logged in to access this page.");
}

// Logged-in user's email (sender)
$senderEmail = $_SESSION['email'];

// Fetch distinct users who have sent or received messages
$sql = "
    SELECT DISTINCT 
        tblregusers.ID, 
        CONCAT(tblregusers.FirstName, ' ', tblregusers.LastName) AS FullName, 
        tblregusers.Email AS UserEmail
    FROM tblregusers
    JOIN messages ON messages.username = tblregusers.Email OR messages.receiver = tblregusers.Email
    ORDER BY tblregusers.FirstName ASC
";

// Execute the query
$result = $con->query($sql);

if (!$result) {
    die("Query failed: " . $con->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Service</title>
</head>
<body>
    <h1>Users Who Sent or Received Messages</h1>
    <ul style="list-style-type: none; padding: 0;">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Display user names vertically as clickable links -->
                <li>
                    <a 
                        href="conversation.php?user_id=<?php echo urlencode($row['ID']); ?>" 
                        style="text-decoration: none; color: #007BFF;"
                    >
                        <?php echo htmlspecialchars($row['FullName']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </ul>
    <?php $con->close(); ?>
</body>
</html>
