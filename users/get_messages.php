<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please log in as an admin.']);
        exit;
    }

    // Check for a specific user ID
    $messages = [];
    if (isset($_GET['user_id'])) {
        $userId = (int)$_GET['user_id']; // Cast for security
        $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("i", $userId);
    } else {
        // Fetch all messages for admin
        $stmt = $con->prepare("SELECT user_id, username, message, isSupport, created_at FROM messages ORDER BY created_at ASC");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode(['success' => true, 'messages' => $messages]);

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
