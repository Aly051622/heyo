<?php
session_start();
include('../DBconnection/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    try {
        // Fetch messages for the logged-in user
        $stmt = $con->prepare("SELECT username, message, isSupport, created_at FROM messages WHERE user_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode(['success' => true, 'messages' => $messages]);
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
