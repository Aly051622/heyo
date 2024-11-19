<?php
// Start the session
session_start();

// Include database connection
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    exit('Unauthorized access.');
}

// Get the logged-in user's email
$logged_in_email = $_SESSION['email'];

// Query to fetch distinct users who messaged the logged-in user (admin)
$sql = "
    SELECT DISTINCT m.username AS sender_email, 
           u.FirstName, 
           u.LastName, 
           u.profile_pictures
    FROM messages m
    JOIN tblregusers u ON m.username = u.Email
    WHERE m.isSupport = 0 AND m.username != '$logged_in_email'
    ORDER BY m.created_at DESC
";

// Execute the query
$result = mysqli_query($con, $sql);

// Check for query errors
if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}

// Display results if there are messages
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sender_email = $row['sender_email'];
        $first_name = $row['FirstName'];
        $last_name = $row['LastName'];
        $profile_picture = $row['profile_pictures'];

        // Sanitize data for display
        $safe_first_name = htmlspecialchars($first_name);
        $safe_last_name = htmlspecialchars($last_name);
        $safe_email = htmlspecialchars($sender_email);
        $profile_picture_path = $profile_picture
            ? 'uploads/profile_uploads/' . htmlspecialchars($profile_picture)
            : 'uploads/default-profile-picture.jpg';

        // Display user information
        echo '<div style="margin-bottom: 20px;">';
        echo '<img src="' . $profile_picture_path . '" alt="Profile Picture" width="50" height="50" style="border-radius: 50%;"> ';
        echo '<strong>' . $safe_first_name . ' ' . $safe_last_name . '</strong>';
        echo '<br>';
        echo '<a href="conversation.php?sender=' . urlencode($safe_email) . '">View Conversation</a>';
        echo '</div>';
    }
} else {
    // Display a message if no messages are available
    echo '<p>No users have messaged you yet.</p>';
}

// Close the database connection
mysqli_close($con);
?>
