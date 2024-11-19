<?php
// Include the database connection
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if a specific user_id is provided (optional)
    $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

    // Prepare query
    if ($userId) {
        $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("i", $userId);
    } else {
        $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE isSupport = 0 ORDER BY created_at ASC");
    }

    // Execute and fetch results
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

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
