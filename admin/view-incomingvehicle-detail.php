<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $cid = $_GET['viewid'];
        $status = $_POST['status'];

        // Update the status of the vehicle
        $query = mysqli_query($con, "UPDATE tblvehicle SET Status='$status' WHERE ID='$cid'");
        if ($query) {
            echo "<script>alert('Status has been updated');</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <title>VPMS - View Vehicle Detail</title>
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include_once('includes/sidebar.php');?>
    <?php include_once('includes/header.php');?>


    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">View Incoming Vehicle</strong>
                            <!--put in here the profile picture please with the same contact number and then the profile_pictures -->
                        </div>
                        <div class="card-body">
                            <table border="1" class="table table-bordered mg-b-0">
                                <tr>
                                    <th>Parking Number</th>
                                    <td>915203976</td>
                                </tr>
                                <tr>
                                    <th>Vehicle Category</th>
                                    <td>Two Wheeler Vehicle</td>
                                </tr>
                                <tr>
                                    <th>Vehicle Company Name</th>
                                    <td>stert</td>
                                </tr>
                                <tr>
                                    <th>Registration Number</th>
                                    <td>4564564545646464</td>
                                </tr>
                                <tr>
                                    <th>Owner Name</th>
                                    <td>sd</td>
                                </tr>
                                <tr>
                                    <th>Owner Contact Number</th>
                                    <td>3453645645</td>
                                </tr>
                                <tr>
                                    <th>In Time</th>
                                    <td>2024-10-20 14:45:07</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>Vehicle In</td>
                                </tr>
                            </table>

                            <!-- Status Update Form -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal mt-4">
                                <table class="table mb-0">
                                    <tr>
                                        <th>Status :</th>
                                        <td>
                                            <select name="status" class="form-control" required="true">
                                                <option value="Out">Outgoing Vehicle</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;">
                                            <button type="submit" class="btn btn-primary btn-sm" name="submit">Update</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php include_once('includes/footer.php');?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
