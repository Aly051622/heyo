<?php
// Start the session at the top of the file
session_start();

// Check if the 'email' session variable is set to verify if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}

// Include database connection
include_once 'includes/dbconnection.php';

// Get all messages sent to the admin
$query = "SELECT DISTINCT sender FROM messages WHERE receiver = 'admin'"; // Adjust 'admin' if needed
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error retrieving messages: " . mysqli_error($conn));
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
    <h1>Messages for Admin</h1>
    
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $sender = $row['sender'];
        
        // Get sender's profile picture and name from tblregusers using their email
        $userQuery = "SELECT profile_pictures, FirstName, LastName FROM tblregusers WHERE Email = '$sender'";
        $userResult = mysqli_query($conn, $userQuery);
        
        if ($userResult && mysqli_num_rows($userResult) > 0) {
            $user = mysqli_fetch_assoc($userResult);
            
            $profilePicture = $user['profile_pictures'];
            $fullName = $user['FirstName'] . ' ' . $user['LastName'];
            
            echo "<div class='user-message'>";
            echo "<img src='../uploads/profile_uploads/$profilePicture' alt='$fullName' width='50' height='50'>";
            echo "<a href='conversation.php?user=$sender'>$fullName</a>";
            echo "</div>";
        }
    }
    ?>
</body>
</html>
