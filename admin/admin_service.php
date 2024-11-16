<?php
// Include database connection
include('includes/dbconnection.php');

// Verify that the user is logged in (optional based on your logic)
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] != 'tester1@gmail.com') {
    header("Location: admin_service.php");  // Redirect to login page if not logged in
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

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($con));  // In case of SQL error
}

// Fetch results and display them
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Service - Messages</title>
    <!-- Include any additional CSS here -->
</head>
<body>
    <h1>Users Who Sent Messages to Admin</h1>

    <?php
    // Display users
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sender_email = $row['sender'];
            $first_name = $row['FirstName'];
            $last_name = $row['LastName'];
            $profile_picture = $row['profile_pictures'];

            // Display each user with their profile picture and name
            echo '<div>';
            echo '<h2>' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</h2>';
            if ($profile_picture) {
                echo '<img src="path/to/uploads/profile_pictures/' . htmlspecialchars($profile_picture) . '" alt="Profile Picture" width="100" height="100">';
            } else {
                echo '<img src="path/to/default-profile-picture.jpg" alt="Profile Picture" width="100" height="100">';
            }
            echo '<a href="conversation.php?sender=' . urlencode($sender_email) . '">View Conversation</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No users have messaged the admin yet.</p>';
    }

    // Close the database connection
    mysqli_close($con);
    ?>

</body>
</html>
