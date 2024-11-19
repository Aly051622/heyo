<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = trim($input['message'] ?? '');

    // Validate the message
    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
        exit;
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in to send a message.']);
        exit;
    }

    $userId = $_SESSION['user_id']; // Logged-in user ID
    $username = $_SESSION['username'] ?? 'Guest'; // Use session username or fallback to 'Guest'
    $isSupport = 0; // Indicate this is a user message

    // Insert the message into the database
    try {
        $stmt = $con->prepare("INSERT INTO messages (user_id, username, message, isSupport) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $userId, $username, $message, $isSupport);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
        } else {
            throw new Exception("Failed to execute query: " . $stmt->error);
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
