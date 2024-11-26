<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    
    
    // Handle image upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
            $uploadsDir = '../uploads/profile_uploads/';
            $fileName = uniqid('profile_', true) . '.' . pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
            $targetFilePath = $uploadsDir . $fileName;
    
            // Create the uploads directory if it doesn't exist
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
    
            // Move the uploaded file and update the database
            if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
                $updateQuery = "UPDATE tblregusers SET profile_pictures='$fileName' WHERE ID='$userId'";
                if (mysqli_query($con, $updateQuery)) {
                    $uploadSuccess = true;
                    $profilePicturePath = $targetFilePath; // Update the displayed picture path
                } else {
                    error_log("Database update failed: " . mysqli_error($con));
                }
            } else {
                error_log("File upload failed for: " . $targetFilePath);
            }
        }
    }
?>

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

.navbar-header{
        position: fixed;
        width: 100vh;
        z-index: 1;
    }
    #printbtn:hover,
    #viewbtn:hover {
        background: orange;
    }

    .navbar-header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 13px;
        width: 100vw;
        border-bottom: groove;
    }

    .profile-container {
        position: relative;
        display: inline-block;
    }

    .user-avatar {
        height: 40px;
        width: 40px;
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
        bottom: -3px;
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
    }
    .dropdown {
        margin-top: -85px;
        margin-right: 40px;
    }
 
    .dropdown-toggle {
        margin-top: 50px;
    }

    .user-area img {
        margin-right: -2px;
        margin-top: 10px;
    }

    .menuToggle {
        margin-top: 5px;
        margin-left: 12em;
    }
    #hh {
        margin-top: 30px;
    }
    </style>
</head>
<body>
<!-- Left Panel -->
<?php include_once('includes/sidebar.php'); ?>

<!--HEADER -->
<?php include_once('includes/header.php'); ?>

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
                                        <!--<th>Owner Name</th> -->
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
