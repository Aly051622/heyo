<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/dbconnection.php');

if (!isset($con)) {
    die("Database connection not established.");
}

// Query to fetch user information
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
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
    <?php $con->close(); ?>
</body>
</html>
