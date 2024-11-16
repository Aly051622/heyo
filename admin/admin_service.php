<?php
include('includes/dbconnection.php'); // Include your database connection

// Query to get the distinct senders' first name, last name, and profile picture
$sql = "SELECT DISTINCT m.sender, u.FirstName, u.LastName, u.profile_pictures
        FROM messages m
        INNER JOIN tblregusers u ON m.sender = u.Email";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($con)); // Debugging line if query fails
}

while ($row = mysqli_fetch_assoc($result)) {
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    $profilePicture = $row['profile_pictures'];

    // Display the sender's name and profile picture
    echo "<div class='sender'>";
    echo "<img src='uploads/profile_uploads/" . $profilePicture . "' alt='Profile Picture' width='50' height='50'>";
    echo "<p>" . $firstName . " " . $lastName . "</p>";
    echo "</div>";
}
?>
