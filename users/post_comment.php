<?php
// Enable error reporting for debugging (log only, do not display in JSON response)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('../DBconnection/dbconnection.php');

// Set Content-Type to JSON
header('Content-Type: application/json');

// Suppress output buffering to avoid whitespace or errors in the response
ob_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']) ?: 'Anonymous';  // Default to 'Anonymous'
    $comment = mysqli_real_escape_string($con, $_POST['comment']);

    // Validate input
    if (empty($comment)) {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
        ob_end_clean(); // Clean buffer
        exit;
    }

    // Insert the comment into the database
    $query = "INSERT INTO comments (username, comment) VALUES ('$username', '$comment')";
    if (mysqli_query($con, $query)) {
        echo json_encode(['success' => true]);
    } else {
        error_log('Database Error: ' . mysqli_error($con)); // Log error for debugging
        echo json_encode(['success' => false, 'message' => 'Failed to save the comment.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Clean the output buffer and send the response
ob_end_clean();
exit;
?>
