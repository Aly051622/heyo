<?php
// Correct the path to dbconnection.php based on its actual location
include('../DBconnection/dbconnection.php');

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']); // Optional email
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Insert feedback into the database
    $query = "INSERT INTO feedbacks (name, email, message) VALUES ('$name', '$email', '$message')";

    if (mysqli_query($con, $query)) {
        echo "Your feedback has been submitted successfully.";
    } else {
        echo "There was an error submitting your feedback. Please try again.";
    }
}
?>
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
