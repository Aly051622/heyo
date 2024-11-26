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

    // Ensure user is logged in
if (!isset($_SESSION['vpmsuid'])) {
    echo '<p>Debug: User ID not found in session.</p>';
    exit;
}

$userId = $_SESSION['vpmsuid'];

// Fetch the user's profile picture
$query = "SELECT profile_pictures FROM tblregusers WHERE ID = '$userId'";
$result = mysqli_query($con, $query);

$profilePicturePath = '../admin/images/images.png'; // Default avatar
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profilePicture = $row['profile_pictures'] ?? '';
    $profilePicturePath = (!empty($profilePicture) && file_exists('../uploads/profile_uploads/' . $profilePicture)) 
        ? '../uploads/profile_uploads/' . htmlspecialchars($profilePicture, ENT_QUOTES, 'UTF-8') 
        : $profilePicturePath;
}

$uploadSuccess = false;

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
        
    </style>
</head>
<body>
    <!-- Left Panel -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Left Panel -->


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
