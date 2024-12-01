<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1); // Display errors

include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsuid'] == 0)) {
    header('location:logout.php');
} else {
    // Get the current user's contact number from the session
    $ownerno = $_SESSION['vpmsumn'];


    // Debug: Check the session variable value
    echo "<script>console.log('Session Contact Number: $ownerno');</script>";

    // Fetch data directly from tblqr_login and tblmanual_login
    $query = "
        SELECT 'QR' AS Source, tblqr_login.ID AS qrLoginID, tblqr_login.ParkingSlot, tblqr_login.Name, 
               tblqr_login.VehiclePlateNumber
        FROM tblqr_login
        WHERE tblqr_login.ContactNumber = '$ownerno'
        
        UNION
        
        SELECT 'Manual' AS Source, tblmanual_login.id AS LoginID, tblmanual_login.ParkingSlot, tblmanual_login.OwnerName, 
               tblmanual_login.RegistrationNumber AS VehiclePlateNumber
        FROM tblmanual_login
        WHERE tblmanual_login.OwnerContactNumber = '$ownerno'
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        // Log SQL error message if the query fails
        echo "<script>console.error('SQL Error: " . mysqli_error($con) . "');</script>";
        die("SQL query failed. Please check the logs.");
    }

    // Debug: Check if the query is returning any results
    $row_count = mysqli_num_rows($result);
    echo "<script>console.log('Number of rows returned: $row_count');</script>";

    if ($row_count == 0) {
        echo "<script>console.warn('No records found for contact number: $ownerno');</script>";
    }
    
    if (!isset($_SESSION['vpmsuid'])) {
        echo '<p>Debug: User ID not found in session.</p>';
        exit;
    }
    
?>

<!doctype html>
<html class="no-js" lang="">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>CTU- Danao Parking System - View Vehicle Parking Details</title>
    <link rel="apple-touch-icon" href="../images/aa.png">
      <link rel="shortcut icon" href="../images/aa.png">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QFW9zO27sR9L5N9l9X3C9yDhhFLDf8OiN+RFpF2mVXIOcn4Thhm/6z9y2mbsVsZ1" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HoAq9iHknG3+MBHpNPIc4ni+lK69CBddA/PJyt7jyvTFDhoJfWl7r29wh+I1kFTV" crossorigin="anonymous"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-PsH8R72gFWwHE1tvtfQN2fvHldDpWl9QUZT3G33asP1l61v0Ya34xUkTljAlbiY1" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js" integrity="sha384-GWqAb6aDZ5DJVPbqwnBp4ycg4CJyZ3sEkkquLDVfuFxIt6sXJ3dKEyk5L1hZ4Epl" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">


    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
body, * {
    font-family: 'Open Sans', sans-serif !important; /* Ensure Open Sans is prioritized */
    margin: 0; /* Reset margin for consistency */
    padding: 0; /* Reset padding for consistency */
    box-sizing: border-box; /* Avoid layout issues */
}
        /* Card and button styles */
        .card,
        .card-header {
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
        }

        #printbtn:hover,
        #viewbtn:hover, .download-icon:hover {
            background-color: darkblue;
            border: solid blue;
        }

        #printbtn, #viewbtn, .download-icon {
            border-radius: 9px;
            background-color: rgb(53, 97, 255);
            color: white;
            border: solid;
            cursor: pointer;
            font-weight: bold;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        .download-icon {
            margin-top: 5px;
            display: inline-block;
            padding: 6px 7px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .download-icon:hover {
            color: white;
        }
        .text-right{
            color: gray;
        }

        /* Table responsive adjustments for mobile */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Improve table styling for mobile */
        .table-responsive table {
            width: 100%;
            table-layout: auto;
            word-wrap: break-word;
        }

        .table-responsive th, .table-responsive td {
            white-space: nowrap;
            padding: 8px;
            text-align: left;
        }

        @media (max-width: 480px) {
            .table-responsive th, .table-responsive td {
                display: block;
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
            }
            .table-responsive tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #ddd;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }
            .breadcrumbs{
                display: none;
            }
        }

        @media (max-width: 300px) {
            .table-responsive th, .table-responsive td {
                display: block;
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
            }
            .table-responsive tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #ddd;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }
            .breadcrumbs{
                display: none;
            }
        }

        @media (max-width: 500px) {
            .table-responsive th, .table-responsive td {
                display: block;
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
            }
            .table-responsive tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #ddd;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }
            .breadcrumbs{
                display: none;
            }
        }
        
        .text-center {
    color: red;
    font-weight: bold;
}

    </style>
    </head>
    <body>


    <?php include_once('includes/header.php');?>
 <!-- Include sidebar -->
 <?php include_once('includes/sidebar.php'); ?>
    
 
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
                        <ol class="breadcrumb text-right" style="background: transparent;">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="view-vehicle.php">View Vehicle Parking Details</a></li>
                            <li class="active">View Vehicle Parking Details</li>
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
                        <strong class="card-title">View Vehicle Details</strong>
                    </div>
                    <div class="card-body">
                        <!-- Uncomment if needed -->
                        <!-- <a href="print_all.php" style="cursor:pointer" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print All</a> -->
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Parking Slot</th>
                                    <!-- <th>Owner Name</th> -->
                                    <th>Vehicle Plate Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($row_count > 0) {
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "
                                                <tr>
                                                    <td>$cnt</td>
                                                    <td>{$row['ParkingSlot']}</td>
                                                    <td>{$row['VehiclePlateNumber']}</td>
                                                    <td>
                                                        <a href='view--transac.php?viewid={$row['qrLoginID']}&source={$row['Source']}' class='btn btn-primary' id='viewbtn'>🖹 View</a>
                                                    </td>
                                                </tr>";
                                            $cnt++;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
                                </div>
    </div><!-- .animated -->
</div><!-- .content -->


    </div>

 <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="../admin/assets/js/main.js"></script>

</body>
</html>
<?php } ?>
