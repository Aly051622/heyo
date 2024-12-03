<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Manila');
include('../DBconnection/dbconnection.php');

// Make sure user is logged in
if (strlen($_SESSION['vpmsuid'] == 0)) {
    header('location:logout.php');
} else {
    // Sanitize input
    $cid = mysqli_real_escape_string($con, $_GET['viewid']);
    $source = mysqli_real_escape_string($con, $_GET['source']); // Get the source table identifier

    // Construct query based on source
    if ($source == 'QR') {
        $query = "
        SELECT 
            tblqr_login.ParkingSlot, 
            tblvehicle.VehicleCategory, 
            tblvehicle.VehicleCompanyname, 
            tblvehicle.Model, 
            tblvehicle.Color, 
            tblvehicle.RegistrationNumber, 
            tblvehicle.OwnerName, 
            tblvehicle.OwnerContactNumber, 
            DATE_FORMAT(tblqr_login.TIMEIN, '%h:%i %p %m-%d-%Y') AS FormattedInTimeFromLogin, 
            DATE_FORMAT(tblqr_logout.TIMEOUT, '%h:%i %p %m-%d-%Y') AS FormattedOutTime 
        FROM 
            tblqr_login 
        INNER JOIN 
            tblvehicle ON tblqr_login.VehiclePlateNumber = tblvehicle.RegistrationNumber 
        LEFT JOIN 
            tblqr_logout ON tblqr_login.VehiclePlateNumber = tblqr_logout.VehiclePlateNumber 
            AND tblqr_login.ParkingSlot = tblqr_logout.ParkingSlot
        WHERE 
            tblqr_login.ID = '$cid'";
    } elseif ($source == 'Manual') {
        $query = "
        SELECT 
            tblmanual_login.ParkingSlot, 
            tblvehicle.VehicleCategory, 
            tblvehicle.VehicleCompanyname, 
            tblvehicle.Model, 
            tblvehicle.Color, 
            tblvehicle.RegistrationNumber, 
            tblvehicle.OwnerName, 
            tblvehicle.OwnerContactNumber, 
            DATE_FORMAT(tblmanual_login.TimeIn, '%h:%i %p %m-%d-%Y') AS FormattedInTimeFromLogin, 
            DATE_FORMAT(tblmanual_logout.TimeOut, '%h:%i %p %m-%d-%Y') AS FormattedOutTime 
        FROM 
            tblmanual_login 
        INNER JOIN 
            tblvehicle ON tblmanual_login.RegistrationNumber = tblvehicle.RegistrationNumber 
        LEFT JOIN 
            tblmanual_logout ON tblmanual_login.RegistrationNumber = tblmanual_logout.RegistrationNumber 
            AND tblmanual_login.ParkingSlot = tblmanual_logout.ParkingSlot
        WHERE 
            tblmanual_login.ID = '$cid'";
    } else {
        echo "Invalid source!";
        exit();
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        error_log("SQL Error in VIEW--TRANSAC.PHP: " . mysqli_error($con), 3, "error_log.txt");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Print Vehicle Details | CTU Danao Parking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Include your custom styles -->
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <strong>Vehicle Details</strong>
            </div>
            <div class="card-body">
                <?php if ($row = mysqli_fetch_array($result)) { ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>Parking Slot</th>
                            <td><?php echo $row['ParkingSlot']; ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle Category</th>
                            <td><?php echo $row['VehicleCategory']; ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle Company Name</th>
                            <td><?php echo $row['VehicleCompanyname']; ?></td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td><?php echo $row['Model']; ?></td>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <td><?php echo $row['Color']; ?></td>
                        </tr>
                        <tr>
                            <th>Registration Number</th>
                            <td><?php echo $row['RegistrationNumber']; ?></td>
                        </tr>
                        <tr>
                            <th>Owner Name</th>
                            <td><?php echo $row['OwnerName']; ?></td>
                        </tr>
                        <tr>
                            <th>Owner Contact Number</th>
                            <td><?php echo $row['OwnerContactNumber']; ?></td>
                        </tr>
                        <tr>
                            <th>In Time</th>
                            <td><?php echo $row['FormattedInTimeFromLogin']; ?></td>
                        </tr>
                        <tr>
                            <th>Out Time</th>
                            <td><?php echo $row['FormattedOutTime']; ?></td>
                        </tr>
                    </table>

                    <!-- Add Print Button -->
                    <button class="btn btn-primary" id="printbtn" onclick="window.print()">Print</button>
                <?php } else { ?>
                    <p>No data found.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
