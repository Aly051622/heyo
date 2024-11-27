<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('../DBconnection/dbconnection.php');

// Set content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']) ?: 'Anonymous';  // Default to 'Anonymous'
    $comment = mysqli_real_escape_string($con, $_POST['comment']);

    // Validate comment input (name is optional)
    if (empty($comment)) {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
        exit;
    }

    // Insert the comment into the database
    $query = "INSERT INTO comments (username, comment) VALUES ('$username', '$comment')";
    if (mysqli_query($con, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
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