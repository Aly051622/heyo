<?php
session_start();
include('../DBconnection/dbconnection.php');

if (!isset($_SESSION['vpmsuid']) && !isset($_SESSION['adminid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

try {
    $stmt = $con->prepare("SELECT username, message, isSupport FROM messages ORDER BY created_at ASC");
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
}
?>
