<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Manila');
include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsuid'] == 0)) {
    header('location:logout.php');
} else {
    // Sanitize input
    $cid = mysqli_real_escape_string($con, $_GET['viewid']);
    $source = mysqli_real_escape_string($con, $_GET['source']); // Get the source table identifier

    // Construct query based on source
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
}
else {
        echo "Invalid source!";
        exit();
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        error_log("SQL Error in VIEW--TRANSAC.PHP: " . mysqli_error($con), 3, "error_log.txt");
    }
?>

<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>Client View Vehicle Detail | CTU DANAO Parking System</title>
   

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

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>
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
            margin-left: -0.3em;
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

        #header{
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .nav-link:hover{
        background-image: transparent;
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
       
    }
    #hh{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        font: 20px;
        font-weight: bold;
           }
        .user-avatar{
            height: 35px;
            width: 27px;
        }
    </style>
<body>
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="dashboard.php">
                        <i class="menu-icon fa fa-laptop"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="view-vehicle.php">
                    <i class="menu-icon fa fa-car-side"></i> Owned Vehicle/s
                    </a>
                </li>
                <li>
                    <a href="vehicle-transac.php">
                        <i class="menu-icon fa fa-address-book"></i>Vehicle Logs
                    </a>
                </li>
                <li>
                    <a href="add-vehicle.php">
                        <i class="menu-icon fa fa-address-book"></i>Register Vehicle
                    </a>
                </li>
                <li>
                    <a href="service.php">
                        <i class="menu-icon fa fa-headset"></i>Chat Concern
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<!--HEADER -->
<div id="right-panel" class="right-panel">
<header id="header" class="header">
            <div class="top-left">
            <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
                    <a class="navbar-brand" href="dashboard.php"><img src="images/clientlogo.png" alt="Logo" style=" width: 120px; height: auto;"></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">
                        
                        <div class="form-inline">
                           
                        </div>

                     
                    </div>

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="../admin/images/images.png" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu" id="hh">
                            <a class="nav-link" href="profile.php"><i class="fa fa-user" > My Profile
                            </i></a>

                            <a class="nav-link" href="change-password.php"><i class="fa fa-cog"> Change Password
                            </i></a>

                            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"> Logout
                            </i></a>
                        </div>
                    </div>

                </div>
            </div>
        </header>


        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Dashboard</h1>
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
                            <strong class="card-title">View Vehicle details</strong>
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
                            <?php } else { ?>
                                <p>No data found.</p>
                            <?php } ?>

                    </div>
                </div>
                

  


            </div>



        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<div class="clearfix"></div>

<?php include_once('includes/footer.php');?>

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
<?php }  ?>