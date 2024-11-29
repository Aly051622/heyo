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

.navbar-header{
        position: fixed;
        width: 100vw;
        z-index: 1;
        height: 70px;
    }
    .btn:hover{
                background-color: darkblue;
                border: solid blue;
            }
    #printbtn:hover,
    #viewbtn:hover, .btn:hover {
        background: orange;
    }

    .navbar-header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 5px;
        width: 100vw;
        border-bottom: groove;
    }

    .profile-container {
        position: relative;
        display: inline-block;
    }

    .user-avatar {
        height: 55px;
        width: 55px;
        margin-left: 50em;
        border-radius: 50%;
        object-fit: cover;
        text-shadow: 0px 4px 4px gray;
        border: groove 2px white;
        z-index: 5;
    }

    .user-avatar:hover {
        border: groove 1px orange;
    }

    .active-indicator {
        position: absolute;
        margin-top: 30px;
        right: -3px;
        background-color: #28a745;
        color: white;
        border: 2px solid white;
        font-size: 11px;
        border-radius: 50%;
        width: 12px;
        height: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .user-area {
        display: flex;
        align-items: center;
        margin-top: -60px;
        margin-right: 10px;
        position:fixed;
    }
    .dropdown-toggle {
        margin-top: 50px;
    }
    
    .user-avatar img {
        float: right;
        margin-top: 52px;
        z-index: 1;
    }

    .menuToggle {
        margin-top: 5px;
        margin-left: 12em;
    }
    #menuToggle{
        width: 120px; 
        height: auto;
        margin-top: -10px; 
        margin-left: 20px; 
        cursor: pointer; 
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 3px;
        border-radius: 7px;
    }
    #menuToggle:hover{
        box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 4px 0px inset;
    }
    #hh {
        margin-top: 30px;
    }

     
/* modal for logout */
.modal {
    display: none; 
    position: fixed;
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
.modal-contents {
    background: whitesmoke;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 300px;
    text-align: center;
    box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
}

.modal-contents button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    color: white;
    cursor: pointer;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      background: whitesmoke;
    border: 1px solid white;
}

.modal-contents button:first-of-type {
    background-color:#2691d9;
    color: white;
}

.modal-contents button:last-of-type {
    background-color: #2691d9;
    color: white;
}
.modal-contents button:first-of-type:hover,
.modal-contents button:last-of-type:hover
{
    background-color: darkblue;
    border: solid 1px blue;
}
.alert-message {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: red;
        color: white;
        font-weight: bold;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    /* 1600px and larger screens */
@media (min-width: 1600px) {
    .navbar-header {
        padding: 20px;
    }
    .user-avatar {
        height: 50px;
        width: 50px;
    }
    .user-area {
        margin-right: 20px;
    }
}

/* 1200px to 1599px screens */
@media (min-width: 1200px) and (max-width: 1599px) {
    .navbar-header {
        padding: 18px;
    }
    .user-avatar {
        height: 45px;
        width: 45px;
    }
    .user-area {
        margin-right: 15px;
    }
}

/* 992px to 1199px screens */
@media (max-width: 1200px) {
    .navbar-header {
        padding: 15px;
    }
    .user-avatar {
        height: 40px;
        width: 40px;
    }
    .menuToggle {
        margin-left: 10em;
    }
    .dropdown {
        margin-top: -70px;
        margin-right: 25px;
    }
}

/* 768px to 991px screens */
@media (max-width: 992px) {
    .navbar-header {
        padding: 12px;
    }
    .user-avatar {
        height: 35px;
        width: 35px;
    }
    .user-area {
        margin-right: 10px;
    }
    .menuToggle {
        margin-left: 8em;
        margin-top: 10px;
    }
    .dropdown {
        margin-top: -60px;
        margin-right: 15px;
    }
}

/* 576px to 767px screens */
@media (max-width: 767px) {
    .navbar-header {
        padding: 10px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        margin-right: 5px;
        margin-top: 30em;
    }
    .menuToggle {
        margin-left: 5em;
        margin-top: 10px;
    }
    .dropdown {
        margin-top: -50px;
        margin-right: 10px;
    }
}

/* 480px to 575px screens */
@media (max-width: 575px) {
    .navbar-header {
        padding: 5px;
        width: 100%;
        height: 50px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
        margin-left: 50em;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 40em;
    }
    .dropdown {
        margin-top: 50px;
        margin-right: 20px;
    }
    .menuToggle {
        margin-left: 3em;
        margin-top: 10px;
    }
}
@media (max-width: 480px) {
    .navbar-header {
        padding: 5px;
        width: 100%;
        height: 50px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 30em;
    }
    .dropdown {
        margin-top: 50px;
        margin-right: 100px;
    }
    #menuToggle {
        margin-left: 3em;
        margin-top: 10px;
    }
}
.left-panel{
        margin-top: 12px;
        border-top: groove 2px;
    }
    #sidebar {
    width: 200px;
    position: fixed;
    left: 0;
    height: 100bh;
    overflow: hidden;
    transition: width 0.3s ease;

    z-index: -1;
}

#sidebar.collapsed {
    width: 70px;
}

#toggleSidebar {
    color: white;
    border: none;
    left: 10px;
}


/*sidebarrrrr */

@media (max-width: 768px) {
    #sidebar {
        left: -250px;
    }

    #sidebar.collapsed {
        left: 0;
    }

    #toggleSidebar {
        display: block;
    }

    #right-panel {
        margin-left: 0;
    }
}

@media (max-width: 576px) {
    #sidebar {
        width: 200px;
        margin-top: -15px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
}

@media (max-width: 480px) {
    #sidebar {
        width: 200px;
        margin-top: -15px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
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

<div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle"></a>
        <div class="user-area dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="profile-container">
                    <img class="user-avatar" src="<?php echo htmlspecialchars($profilePicturePath, ENT_QUOTES, 'UTF-8') . '?v=' . time(); ?>" alt="User Avatar">
                    <span class="active-indicator"></span>
                </div>
            </a>
            <div class="user-menu dropdown-menu">
                <div class="hh">
                <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa fa-upload"></i> Upload Picture</a>
                <a class="nav-link" onclick="return handleLogout();"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="profilePic" accept="image/*" required>
                        <button type="submit" name="upload" class="btn btn-primary btn-sm">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


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
