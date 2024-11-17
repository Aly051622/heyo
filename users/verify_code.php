<?php
// Include the database connection
include_once 'includes/dbconnection.php';

// Start the session to handle verification attempts
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user input for the verification code
    $entered_code = $_POST['verification_code'];
    $email = $_SESSION['email']; // Assuming email is stored in session

    // Fetch the correct verification code from the database for this user
    $sql = "SELECT * FROM tblregusers WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_code = $user['verification_code']; // Assuming verification_code is stored in the database

        // Check if the entered code matches the stored code
        if ($entered_code == $stored_code) {
            // Update the user's verification status to 'verified'
            $update_sql = "UPDATE tblregusers SET verification_status = 'verified', status = 'active' WHERE Email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("s", $email);
            $update_stmt->execute();

            echo "Verification successful! Your account is now active.";
            unset($_SESSION['email']); // Clear session email after successful verification
        } else {
            // Handle invalid verification code
            $failed_attempts = $user['FailedAttempts'];

            if ($failed_attempts >= 3) {
                echo "You have exceeded the maximum number of attempts.";
            } else {
                // Update failed attempts count
                $failed_attempts++;
                $update_attempts_sql = "UPDATE tblregusers SET FailedAttempts = ? WHERE Email = ?";
                $update_attempts_stmt = $conn->prepare($update_attempts_sql);
                $update_attempts_stmt->bind_param("is", $failed_attempts, $email);
                $update_attempts_stmt->execute();

                echo "Incorrect verification code. You have " . (3 - $failed_attempts) . " attempts left.";
            }
        }
    } else {
        echo "User not found!";
    }
}

// HTML Form for user to input verification code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
</head>
<body>
    <h1>Enter Your Verification Code</h1>
    <form method="POST">
        <label for="verification_code">Verification Code:</label>
        <input type="text" name="verification_code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
