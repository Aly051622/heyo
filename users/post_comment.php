<?php
// Enable error reporting for development (comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('../DBconnection/dbconnection.php');

// Set Content-Type to JSON
header('Content-Type: application/json');

// Start output buffering to suppress unwanted output
ob_start();

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = mysqli_real_escape_string($con, $_POST['username']) ?: 'Anonymous';  // Default to 'Anonymous'
        $comment = mysqli_real_escape_string($con, $_POST['comment']);

        // Validate input
        if (empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
            ob_end_clean(); // Clean the buffer and exit
            exit;
        }

        // Insert the comment into the database
        $query = "INSERT INTO comments (username, comment) VALUES ('$username', '$comment')";
        if (mysqli_query($con, $query)) {
            echo json_encode(['success' => true]);
        } else {
            // Log error and send JSON response
            error_log('Database Error: ' . mysqli_error($con));
            echo json_encode(['success' => false, 'message' => 'Failed to save the comment.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }
} catch (Exception $e) {
    // Catch unexpected errors and return JSON
    error_log('Exception: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
}

// Clean the output buffer and flush JSON response
ob_end_clean();
exit;
?>
