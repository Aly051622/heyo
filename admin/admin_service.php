<?php
// Start the session
session_start();

// Include database connection
include('includes/dbconnection.php');

// Verify that the user is logged in (optional based on your logic)
if (!isset($_SESSION['email']) || $_SESSION['email'] != 'tester1@gmail.com') {
    // Redirect to the login page or admin dashboard if the user is not logged in
    header("Location: login.php");  // Adjust to your login page if needed
    exit();
}

// Query to fetch distinct senders who messaged the admin (tester1@gmail.com)
$sql = "
    SELECT DISTINCT m.sender, u.FirstName, u.LastName, u.profile_pictures
    FROM messages m
    JOIN tblregusers u ON m.sender = u.Email
    WHERE m.receiver = 'tester1@gmail.com'
    ORDER BY m.created_at DESC
";

// Execute the query
$result = mysqli_query($con, $sql);

// Check for query errors
if (!$result) {
    die("Query Failed: " . mysqli_error($con));  // In case of SQL error
}

// If query executed successfully, fetch and display results
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sender_email = $row['sender'];
        $first_name = $row['FirstName'];
        $last_name = $row['LastName'];
        $profile_picture = $row['profile_pictures'];

        // Display user information with the option to view the conversation
        echo '<div>';
        echo '<h2>' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</h2>';

        // Check if the user has a profile picture, if not, use a default one
        if ($profile_picture) {
            echo '<img src="path/to/uploads/profile_pictures/' . htmlspecialchars($profile_picture) . '" alt="Profile Picture" width="100" height="100">';
        } else {
            echo '<img src="path/to/default-profile-picture.jpg" alt="Default Profile Picture" width="100" height="100">';
        }

        // Link to view the conversation with the sender
        echo '<a href="conversation.php?sender=' . urlencode($sender_email) . '">View Conversation</a>';
        echo '</div>';
    }
} else {
    // If no messages, display a message
    echo '<p>No users have messaged the admin yet.</p>';
}

// Close the database connection
mysqli_close($con);
?>
