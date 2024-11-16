<?php
// Start the session at the very beginning
session_start();

// Ensure the session is set
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    die("You must be logged in to access this page. Session variable 'email' is not set.");
}

// Include the database connection
include('includes/dbconnection.php');

// Get the logged-in user's email from the session
$loggedInEmail = $_SESSION['email'];

// Check if the user exists in the database
$sql = "SELECT * FROM tblregusers WHERE Email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $loggedInEmail);
$stmt->execute();
$result = $stmt->get_result();

// If no user is found with the email, the session is invalid
if ($result->num_rows === 0) {
    die("Session expired or invalid user. Please log in again.");
}

// User is valid, fetch user data
$user = $result->fetch_assoc();
$userID = $user['ID'];  // Store the user's ID for further use

// SQL query to fetch messages sent or received by the logged-in user
$sqlMessages = "
    SELECT DISTINCT 
        tblregusers.ID, 
        CONCAT(tblregusers.FirstName, ' ', tblregusers.LastName) AS FullName, 
        tblregusers.Email AS UserEmail
    FROM tblregusers
    JOIN messages 
        ON messages.username = tblregusers.Email 
        OR messages.receiver = tblregusers.Email
    ORDER BY tblregusers.FirstName ASC
";

// Execute the query to get users who sent messages
$resultMessages = $con->query($sqlMessages);

// If the query fails, show an error
if (!$resultMessages) {
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
        <?php if ($resultMessages->num_rows > 0): ?>
            <?php while ($row = $resultMessages->fetch_assoc()): ?>
                <li>
                    <a 
                        href="conversation.php?user_id=<?php echo urlencode($row['ID']); ?>" 
                        style="text-decoration: none; color: #007BFF;">
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
