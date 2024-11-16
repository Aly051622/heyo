<?php
// Include database connection
include_once 'includes/dbconnection.php';

// Get all distinct senders who have messaged the admin
$query = "SELECT DISTINCT sender FROM messages WHERE receiver = 'admin'"; 
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
    // Loop through each sender and fetch their details
    while ($row = mysqli_fetch_assoc($result)) {
        $senderEmail = $row['sender'];
        
        // Get sender's profile picture and full name from tblregusers
        $userQuery = "SELECT profile_pictures, FirstName, LastName FROM tblregusers WHERE Email = '$senderEmail'";
        $userResult = mysqli_query($conn, $userQuery);
        
        if ($userResult && mysqli_num_rows($userResult) > 0) {
            $user = mysqli_fetch_assoc($userResult);
            
            $profilePicture = $user['profile_pictures'];
            $fullName = $user['FirstName'] . ' ' . $user['LastName'];
            
            // Display the user's name with their profile picture, and link to the conversation
            echo "<div class='user-message'>";
            echo "<img src='../uploads/profile_uploads/$profilePicture' alt='$fullName' width='50' height='50'>";
            echo "<a href='conversation.php?user=$senderEmail'>$fullName</a>";
            echo "</div>";
        }
    }
    ?>
</body>
</html>
