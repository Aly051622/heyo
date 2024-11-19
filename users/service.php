<?php
session_start(); // Start the session at the beginning of the file

// Check if the user is logged in
if (!isset($_SESSION['vpmsuid'])) {
    echo "<script>alert('User not logged in.'); window.location='login.php';</script>";
    exit(); // Stop further script execution if not logged in
}

// Database connection (update this with your actual database credentials)
include("includes/db.php");

// Fetch messages if the user is logged in
$user_id = $_SESSION['vpmsuid']; // Retrieve logged-in user ID from session

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message from the form
    $message = $_POST['message'] ?? '';

    // Validate message input
    if (!empty($message)) {
        // Insert the message into the database
        $sql = "INSERT INTO messages (user_id, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $message); // Bind the user ID and message
        $stmt->execute();
        $stmt->close();

        // Redirect or show a success message
        echo "<script>alert('Message sent successfully.'); window.location='customer_service.php';</script>";
    } else {
        echo "<script>alert('Please enter a message.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service</title>
</head>
<body>
    <h1>Customer Service</h1>
    
    <!-- Display logged-in user's name or ID (optional) -->
    <p>Welcome, User ID: <?php echo $_SESSION['vpmsuid']; ?></p>

    <!-- Message Form -->
    <form action="customer_service.php" method="POST">
        <textarea name="message" placeholder="Type your message here..." required></textarea><br>
        <button type="submit">Send</button>
    </form>

    <h2>Messages</h2>
    <?php
    // Fetch and display messages (optional)
    $sql = "SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['created_at']) . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
    }
    $stmt->close();
    ?>
</body>
</html>
