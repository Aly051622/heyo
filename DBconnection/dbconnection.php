<?php
// Create the connection object using the object-oriented method
$con = new mysqli("localhost", "u132092183_parkingz", "@Parkingz!2024", "u132092183_parkingz");

// Check for connection errors
if ($con->connect_error) {
    die("Connection Failed: " . $con->connect_error); // Stop execution if connection fails
} 
// Optional Debugging:
// else {
//     echo "Database connected successfully.";
// }
?>
