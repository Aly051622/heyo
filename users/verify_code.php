<?php
session_start();

if (isset($_POST['submit'])) {
    // Get the user input
    $userInputCode = $_POST['verification_code'];
    
    // Check if the code matches
    if (isset($_SESSION['verification_code']) && $_SESSION['verification_code'] == $userInputCode) {
        // Code matches, save the account (example: set user status to active)
        // Here you can add your logic to save the account, e.g., updating the database
        echo json_encode(['success' => true, 'message' => 'Account verified successfully.']);
        
        // Clear the session variables
        unset($_SESSION['verification_code']);
        unset($_SESSION['verification_email']);
        
    } else {
        // Code doesn't match
        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 1;
        } else {
            $_SESSION['attempts']++;
        }
        
        if ($_SESSION['attempts'] >= 5) {
            echo json_encode(['success' => false, 'message' => 'Maximum attempts reached. Please request a new code.']);
            // Optionally, clear the attempts or reset the process
            unset($_SESSION['attempts']);
        } else {
            // Resend the verification code
            // Call the send_verification_code.php script to resend the code
            include 'send_verification_code.php'; // Make sure to handle this correctly in production
            echo json_encode(['success' => false, 'message' => 'Incorrect code. A new code has been sent.']);
        }
    }
}
?>

<form method="POST" action="">
    <input type="text" name="verification_code" required placeholder="Enter verification code">
    <input type="submit" name="submit" value="Verify">
</form>

<script>

    // Disable right-click
  document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
  });
  
  // Disable F12 and other developer tools keys
  document.addEventListener('keydown', function(event) {
    if (event.keyCode == 123 || // F12
        (event.ctrlKey && event.shiftKey && event.keyCode == 73)) { // Ctrl + Shift + I (Inspect)
        event.preventDefault();
    }
  });

  </script>