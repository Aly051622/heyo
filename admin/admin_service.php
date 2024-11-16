<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

include_once 'includes/dbconnection.php';

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Example query: Select distinct senders from messages table
$sql = "SELECT DISTINCT sender FROM messages"; 
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error with query: " . mysqli_error($con));  // This will show the specific SQL error
}

while ($row = mysqli_fetch_assoc($result)) {
    $sender_email = $row['sender'];

    // Fetch user info from tblregusers based on the sender's email
    $user_sql = "SELECT FirstName, LastName, profile_pictures FROM tblregusers WHERE Email = '$sender_email'";
    $user_result = mysqli_query($con, $user_sql);
    
    if (!$user_result) {
        die("Error fetching user info: " . mysqli_error($con)); // Show if the query fails
    }

    $user_info = mysqli_fetch_assoc($user_result);
    echo "Sender: " . htmlspecialchars($user_info['FirstName']) . " " . htmlspecialchars($user_info['LastName']);
    echo " <img src='" . htmlspecialchars($user_info['profile_pictures']) . "' alt='Profile Picture'>";
}
?>
