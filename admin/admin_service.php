<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/dbconnection.php');

if (!isset($con)) {
    die("Database connection not established.");
}

// Fetch user names from the database
$sql = "SELECT CONCAT(FirstName, ' ', LastName) AS FullName, ID FROM tblregusers";
$result = $con->query($sql);

if (!$result) {
    die("Query failed: " . $con->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Service</title>
</head>
<body>
    <h1>Users List</h1>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Display user names as clickable links -->
                <a 
                    href="conversation.php?user_id=<?php echo urlencode($row['ID']); ?>" 
                    style="text-decoration: none; padding: 10px; background-color: #f0f0f0; border-radius: 5px; color: #333;"
                >
                    <?php echo htmlspecialchars($row['FullName']); ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
    <?php $con->close(); ?>
</body>
</html>
