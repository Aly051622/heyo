<?php
session_start();
include('DBconnection/dbconnection.php');

// Ensure the script only handles POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $message = trim($input['message'] ?? '');

    // Validate message input
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
        exit;
    }

    // Retrieve username and user_id from session
    $userId = $_SESSION['user_id'] ?? null;
    $username = $_SESSION['username'] ?? 'Guest'; // Fallback to 'Guest' if no session is set

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    // Set isSupport flag (0 for user messages)
    $isSupport = 0;

    try {
        // Prepare the SQL statement
        $stmt = $con->prepare("INSERT INTO messages (user_id, username, message, isSupport) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare the statement: " . $con->error);
        }

        // Bind parameters
        $stmt->bind_param("issi", $userId, $username, $message, $isSupport);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
        } else {
            throw new Exception("Failed to execute the statement: " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $con->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
