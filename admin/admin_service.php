<?php
// Include the database connection
include 'includes/dbconnection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Admin email (hardcoded as you specified)
    $sender = "tester1@gmail.com";  // Admin email
    
    // Sanitize and get the receiver's email and message from the form submission
    $receiver = mysqli_real_escape_string($con, $_POST['receiver']);  // Receiver's email
    $message = mysqli_real_escape_string($con, $_POST['message']);  // Message content
    $status = "unread";  // Default status is unread for new messages

    // Insert the message into the messages table
    $sql = "INSERT INTO messages (sender, receiver, message, status)
            VALUES ('$sender', '$receiver', '$message', '$status')";

    // Execute the query and check for success
    if (mysqli_query($con, $sql)) {
        echo "Message sent successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
