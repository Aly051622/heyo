<?php
// Include the database connection
include('includes/dbconnection.php');

// Fetch user names from the database
$sql = "SELECT first_name, username FROM tblregusers";  // Adjust the column names if necessary
$result = $conn->query($sql);

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
                    <th>Username</th>
                    <th>Full Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <?php
    // Close the database connection
    $conn->close();
    ?>

</body>
</html>
