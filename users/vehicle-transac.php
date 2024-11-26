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
    
?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!doctype html>
<html class="no-js" lang="">
<head>
    <title>CTU- Danao Parking System - View Vehicle Parking Details</title>
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <style>
        #printbtn:hover,
        #viewbtn:hover {
            background: orange;
            color: black;
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); 
        }
        body {
            height: 100vh;
            background: whitesmoke;
            overflow: auto;
        }
        #printbtn {
            background: yellowgreen;
            color: white;
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
</head>
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


    <!-- Left Panel -->
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
                            <h1>Vehicle Logs</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
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
                                    if ($row_count > 0) {
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>
                                                    <td>$cnt</td>
                                                    <td>{$row['ParkingSlot']}</td>
                                                    <td>{$row['Name']}</td>
                                                    <td>{$row['VehiclePlateNumber']}</td>
                                                    <td>
                                                        <a href='view--transac.php?viewid={$row['qrLoginID']}&source={$row['Source']}' class='btn btn-primary' id='viewbtn'>🖹 View</a> 
                                                        <a href='print.php?vid={$row['qrLoginID']}&source={$row['Source']}' style='cursor:pointer' target='_blank' class='btn btn-warning' id='printbtn'>🖶 Print</a>
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
        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>

</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->

</body>
</html>
<?php } ?>
