<?php
// Include the database connection
include_once 'includes/dbconnection.php';

// Start the session to handle user authentication and failed attempts
session_start();

// Check if the form is submitted for login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists and verify the password
    $sql = "SELECT * FROM tblregusers WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['Password'])) {
            // Check if the account is verified
            if ($user['verification_status'] == 'verified') {
                // Successful login
                $_SESSION['email'] = $email; // Store email in session
                header('Location: dashboard.php'); // Redirect to dashboard
                exit;
            } else {
                // If email is not verified, ask for verification code
                $_SESSION['email'] = $email; // Store email in session for verification
                header('Location: verify_code.php'); // Redirect to verify code page
                exit;
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
}

// Check if the form is submitted for verification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    // Get the entered verification code
    $entered_code = $_POST['verification_code'];
    $email = $_SESSION['email'];

    // Fetch the correct verification code from the database
    $sql = "SELECT * FROM tblregusers WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_code = $user['verification_code']; // Verification code stored in the database

        // Check if the entered code matches the stored code
        if ($entered_code == $stored_code) {
            // Update the user's verification status to 'verified'
            $update_sql = "UPDATE tblregusers SET verification_status = 'verified', status = 'active' WHERE Email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("s", $email);
            $update_stmt->execute();

            echo "Verification successful! Your account is now active.";
            unset($_SESSION['email']); // Clear session email after successful verification
            header('Location: dashboard.php'); // Redirect to dashboard after verification
            exit;
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <!-- Login Form -->
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Login</button>
    </form>

    <!-- Verification Form (Displayed if the user is not verified) -->
    <?php if (isset($_SESSION['email'])): ?>
        <h1>Enter Your Verification Code</h1>
        <form method="POST">
            <label for="verification_code">Verification Code:</label>
            <input type="text" name="verification_code" required><br>
            <button type="submit" name="verify">Verify</button>
        </form>
    <?php endif; ?>
</body>
</html>
