<?php
session_start();
include('../DBconnection/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = trim($input['message'] ?? '');

    if (empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty.']);
        exit;
    }

    $userId = $_SESSION['vpmsuid'] ?? null;
    $username = $_SESSION['username'] ?? 'Guest';

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $isSupport = 0; // User message
    try {
        $stmt = $con->prepare("INSERT INTO messages (user_id, username, message, isSupport, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("issi", $userId, $username, $message, $isSupport);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
