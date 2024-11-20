<?php
session_start();
include 'includes/dbconnection.php';

// Fetch all users' details
$queryAllUsers = "
    SELECT ID, 
           FirstName, 
           LastName, 
           Email, 
           MobileNumber, 
           LicenseNumber, 
           or_image, 
           cr_image, 
           nv_image, 
           profile_pictures, 
           RegDate, 
           status, 
           validity 
    FROM tblregusers
";

$resultAllUsers = mysqli_query($con, $queryAllUsers);

if (mysqli_num_rows($resultAllUsers) > 0) {
    echo "<h1>All Users</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Mobile Number</th>
            <th>License Number</th>
            <th>OR Image</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>Profile Picture</th>
            <th>Registration Date</th>
            <th>Status</th>
            <th>Validity</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($resultAllUsers)) {
        echo "<tr>
                <td>{$row['ID']}</td>
                <td>{$row['FirstName']}</td>
                <td>{$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['MobileNumber']}</td>
                <td>{$row['LicenseNumber']}</td>
                <td><img src='../uploads/{$row['or_image']}' alt='OR Image' width='100'></td>
                <td><img src='../uploads/{$row['cr_image']}' alt='CR Image' width='100'></td>
                <td><img src='../uploads/{$row['nv_image']}' alt='NV Image' width='100'></td>
                <td><img src='../uploads/{$row['profile_pictures']}' alt='Profile Picture' width='100'></td>
                <td>{$row['RegDate']}</td>
                <td>{$row['status']}</td>
                <td>{$row['validity']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No users found.";
}

mysqli_close($con);
?>

<button onclick="window.history.back()">Back</button>
