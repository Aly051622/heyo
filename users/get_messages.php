<?php
// Include the database connection
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all messages where isSupport = 0 (user messages)
    $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE isSupport = 0 ORDER BY created_at ASC");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the statement: ' . $con->error]);
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Debug: log the result for troubleshooting
    error_log("Messages Retrieved: " . print_r($messages, true));

    if (empty($messages)) {
        echo json_encode(['success' => false, 'message' => 'No users have messaged the admin yet.']);
    } else {
        echo json_encode(['success' => true, 'messages' => $messages]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
