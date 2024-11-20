<?php
session_start();
include 'includes/dbconnection.php';

// Fetch unvalidated clients along with their images and expiration dates
$queryUnvalidated = "
    SELECT u.email, 
           u.expiration_date, 
           u.validity, 
           r.cr_image, 
           r.nv_image, 
           r.or_image, 
           r.profile_pictures
    FROM uploads u
    LEFT JOIN tblregusers r ON u.email = r.Email
    WHERE u.validity = 0 OR u.expiration_date < CURDATE()
";

$resultUnvalidated = mysqli_query($con, $queryUnvalidated);

if (mysqli_num_rows($resultUnvalidated) > 0) {
    echo "<h1>Unvalidated Clients</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Email</th>
            <th>Expiration Date</th>
            <th>CR Image</th>
            <th>NV Image</th>
            <th>OR Image</th>
            <th>Profile Picture</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($resultUnvalidated)) {
        echo "<tr>
                <td>{$row['email']}</td>
                <td>{$row['expiration_date']}</td>
                <td><img src='../uploads/{$row['cr_image']}' alt='CR Image' width='100'></td>
                <td><img src='../uploads/{$row['nv_image']}' alt='NV Image' width='100'></td>
                <td><img src='../uploads/{$row['or_image']}' alt='OR Image' width='100'></td>
                <td><img src='../uploads/{$row['profile_pictures']}' alt='Profile Picture' width='100'></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No unvalidated clients found.";
}

mysqli_close($con);
?>

<button onclick="window.history.back()">Back</button>
