<?php
include_once 'includes/dbconnection.php'; // Make sure the connection is included

$sql = "SELECT DISTINCT sender FROM messages"; // Get distinct senders
$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Error with query: " . mysqli_error($con);
    exit(); // Stop execution if query fails
}

while ($row = mysqli_fetch_assoc($result)) {
    $sender_email = $row['sender']; // Get the sender email

    // Fetch sender info from tblregusers
    $user_sql = "SELECT FirstName, LastName, profile_pictures FROM tblregusers WHERE Email = '$sender_email'";
    $user_result = mysqli_query($con, $user_sql);
    
    if ($user_result) {
        $user_info = mysqli_fetch_assoc($user_result);
        echo "Sender: " . $user_info['FirstName'] . " " . $user_info['LastName'];
        echo " <img src='" . $user_info['profile_pictures'] . "' alt='Profile Picture'>";
    } else {
        echo "Error fetching user info: " . mysqli_error($con);
    }
}
?>
