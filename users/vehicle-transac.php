<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');
include('../DBconnection/dbconnection.php');

// Check session and redirect if necessary
if (empty($_SESSION['vpmsuid'])) {
    header('location:logout.php');
    exit;
}

// Validate and retrieve owner number
if (empty($_SESSION['vpmsumn'])) {
    die("Error: Owner number not set in session.");
}

$ownerno = $_SESSION['vpmsumn'];

// Debugging: Validate database connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Parameterized query with consistent aliases
$stmt = $con->prepare("
    SELECT 'QR' AS Source, tblqr_login.ID AS LoginID, tblqr_login.ParkingSlot, tblvehicle.OwnerName, 
           tblqr_login.VehiclePlateNumber, tblqr_login.TIMEIN
    FROM tblqr_login
    INNER JOIN tblvehicle 
    ON tblqr_login.VehiclePlateNumber = tblvehicle.RegistrationNumber 
    AND tblqr_login.ContactNumber = tblvehicle.OwnerContactNumber
    WHERE tblqr_login.ContactNumber = ?
    
    UNION
    
    SELECT 'Manual' AS Source, tblmanual_login.id AS LoginID, tblmanual_login.ParkingSlot, tblvehicle.OwnerName, 
           tblmanual_login.RegistrationNumber AS VehiclePlateNumber, tblmanual_login.TimeIn
    FROM tblmanual_login
    INNER JOIN tblvehicle 
    ON tblmanual_login.RegistrationNumber = tblvehicle.RegistrationNumber 
    AND tblmanual_login.OwnerContactNumber = tblvehicle.OwnerContactNumber
    WHERE tblmanual_login.OwnerContactNumber = ?
");
if (!$stmt) {
    die("SQL statement preparation failed: " . $con->error);
}

$stmt->bind_param("ss", $ownerno, $ownerno);

if (!$stmt->execute()) {
    error_log("SQL Execution Error: " . $stmt->error, 3, "error_log.txt");
    die("SQL execution failed. Check error log for details.");
}

$result = $stmt->get_result();

// Debugging: Check if query ran successfully
if (!$result) {
    error_log("Result Fetch Error: " . $stmt->error, 3, "error_log.txt");
    die("Error fetching data. Debug message: " . $stmt->error);
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

        /* Breadcrumb styles */
        .breadcrumbs {
            width: 90%;
            background-color: #ffffff;
            padding: 7px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            margin-bottom: 10px;
            margin-top: 10px;
            margin-left: 5em;
        }

        .breadcrumbs .breadcrumb {
            background: none;
            margin: 0;
            padding: 0;
        }

        .breadcrumb a {
            color: gray;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: black;
        }

        .breadcrumb .active {
            color: #6c757d;
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
        }
        .clearfix{
            background: whitesmoke; 
        }
        .text-center {
    color: red;
    font-weight: bold;
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
                $cnt = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$cnt}</td>
                            <td>{$row['ParkingSlot']}</td>
                            <td>{$row['OwnerName']}</td>
                            <td>{$row['VehiclePlateNumber']}</td>
                            <td>
                                <a href='view--transac.php?viewid={$row['LoginID']}&source={$row['Source']}' class='btn btn-primary'>🖹 View</a>
                                <a href='print.php?vid={$row['LoginID']}&source={$row['Source']}' target='_blank' class='btn btn-warning'>🖶 Print</a>
                            </td>
                        </tr>";
                        $cnt++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No records found for this user.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="alert alert-info mt-3">
            <strong>Debug Info:</strong><br>
            Total Rows: <?php echo $result->num_rows; ?><br>
            Owner Number: <?php echo htmlspecialchars($ownerno); ?>
        </div>
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
<?php ?>