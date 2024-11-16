<?php
// Include the database connection
include 'includes/dbconnection.php';

// Admin email for reference (if you need to filter messages by admin)
$admin_email = 'tester1@gmail.com';

// Query to get distinct senders, along with their profile pictures
$sql = "
    SELECT DISTINCT m.sender, u.FirstName, u.LastName, u.profile_pictures
    FROM messages m
    JOIN tblregusers u ON m.sender = u.Email
    WHERE m.receiver = '$admin_email' OR m.sender = '$admin_email'
    ORDER BY m.created_at DESC
";

// Execute the query
$result = mysqli_query($con, $sql);

// Check if the query was successful and if there are results
if ($result && mysqli_num_rows($result) > 0) {
    echo "<h2>Messages from Users</h2>";

    // Display the list of senders and their profile picture
    while ($row = mysqli_fetch_assoc($result)) {
        $sender_email = $row['sender'];
        $sender_name = $row['FirstName'] . " " . $row['LastName'];
        $profile_picture = $row['profile_pictures'] ? "uploads/profile_uploads/" . $row['profile_pictures'] : 'default-profile.jpg';

        // Display each sender's information
        echo "<div class='sender'>";
        echo "<img src='$profile_picture' alt='Profile Picture' width='50' height='50'>";
        echo "<a href='conversation.php?sender=$sender_email'>$sender_name</a>";
        echo "</div>";
    }
} else {
    echo "No messages found.";
}

?>
