<?php
// Start the session at the top of the file
session_start();

// Check if the 'email' session variable is set to verify if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}

// If the user is logged in, continue with the page content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Service</title>
</head>
<body>
    <h1>Welcome to the Admin Service Page</h1>
    <p>You are logged in as <?php echo $_SESSION['email']; ?></p>
    <!-- Your service page content goes here -->

    <!-- If you have a sidebar or other elements, include them here -->
</body>
</html>
