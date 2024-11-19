<?php
// Include the database connection
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Check if user_id is provided in the query string
        $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

        if ($userId) {
            // Query to fetch messages for a specific user
            $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
            $stmt->bind_param("i", $userId); // Bind the user_id parameter
        } else {
            // Query to fetch all messages if no user_id is provided
            $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages ORDER BY created_at ASC");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all messages as an array
        $messages = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
    } catch (Exception $e) {
        // Return an error message in case of an exception
        echo json_encode([
            'success' => false,
            'message' => 'Error retrieving messages: ' . $e->getMessage()
        ]);
    } finally {
        // Close the statement and connection
        if (isset($stmt)) {
            $stmt->close();
        }
        $con->close();
    }
} else {
    // Respond with an error for unsupported request methods
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
