<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1); // Display errors for debugging
include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsuid'] ?? '') == 0) {
    header('location:logout.php');
} else {
    $ownerno = $_SESSION['vpmsumn'] ?? '';

    if (!$ownerno) {
        echo "<script>console.error('Error: Session variable \"vpmsumn\" is empty or not set.');</script>";
        die();
    }

    // Debug: Log owner number
    echo "<script>console.log('Session Owner Number: " . htmlspecialchars($ownerno) . "');</script>";

    // SQL query
    $query = "
       SELECT 'QR' AS Source, tblqr_login.ID AS qrLoginID, tblqr_login.ParkingSlot, tblvehicle.OwnerName, 
       tblqr_login.VehiclePlateNumber
FROM tblqr_login
INNER JOIN tblvehicle 
ON tblqr_login.VehiclePlateNumber = tblvehicle.RegistrationNumber 
AND tblqr_login.ContactNumber = tblvehicle.OwnerContactNumber
WHERE tblqr_login.ContactNumber = '88888888888' ";


    // Debug: Log query
    echo "<script>console.log('SQL Query: " . htmlspecialchars($query) . "');</script>";

    $result = mysqli_query($con, $query);

    if (!$result) {
        // Log SQL error to the browser console
        $error_message = mysqli_real_escape_string($con, mysqli_error($con));
        echo "<script>console.error('SQL Error: $error_message');</script>";
        die("SQL Query failed. Check the console for details.");
    }

    // Check if query returns any rows
    if (mysqli_num_rows($result) === 0) {
        echo "<script>console.warn('No records found for owner: $ownerno');</script>";
    }

?>


<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>CTU- Danao Parking System - View Vehicle Parking Details</title>
    
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
   
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
      html, body {
            font-family: 'Poppins', sans-serif;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: auto;
            
            background: whitesmoke;
        }

        body {
            background: whitesmoke;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }


    </style>
</head>
<body>
    <!-- Left Panel -->

  <?php include_once('includes/sidebar.php');?>

    <!-- Left Panel -->

    <!-- Right Panel -->

     <?php include_once('includes/header.php');?>

     <div class="right-panel">
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h3>Vehicle Logs</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="dashboard.php">Dashboard</a></li>
                                    <li><a href="view-vehicle.php">View Vehicle</a></li>
                                    <li class="active">View Vehicle details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                   
         

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">View Vehicle Parking Details</strong>
                        </div>
                        <div class="card-body">
                        <a href="print_all.php" style="cursor:pointer" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print All</a>
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Parking Slot</th>
                                        <th>Owner Name</th>
                                        <th>Vehicle Plate Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                            echo "<div class='breadcrumbs'>";
                                            echo "<h4>Owner Number: " . htmlspecialchars($ownerno) . "</h4>";
                                            echo "</div>";
                                            $cnt = 1;
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo "<script>console.log('Row Data: " . json_encode($row) . "');</script>"; ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row['ParkingSlot']; ?></td>
                                                        <td><?php echo $row['OwnerName']; ?></td>
                                                        <td><?php echo $row['VehiclePlateNumber']; ?></td>
                                                        <td>
                                                            <a href="view--transac.php?viewid=<?php echo $row['qrLoginID']; ?>&source=<?php echo $row['Source']; ?>" class="btn btn-primary" id="viewbtn">🖹 View</a>
                                                            <a href="print.php?vid=<?php echo $row['qrLoginID']; ?>&source=<?php echo $row['Source']; ?>" style="cursor:pointer" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $cnt++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                                            }
                                        ?>
                                    </tbody>
        </table>

                    </div>
                </div>
            </div>

   

        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<div class="clearfix"></div>

</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="../admin/assets/js/main.js"></script>


</body>
</html>
<?php } ?>