
<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Manila');
include('../DBconnection/dbconnection.php');
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
  } else{



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
    <link rel="stylesheet" href="css/responsive/.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<style>
       .clearfix{ 
        background-color: #f9fcff;
        background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
         }
         body{
    background: whitesmoke;
    height: 100vh;
}
         .card, .card-header{
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
                 }
         .btn:hover{
            background: orange;
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
          .clearfix{
              background: whitesmoke; 
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
          }
    </style>
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
            <!-- START: Left Section -->
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <div class="page-header float-md-left text-center text-md-left">
                    <div class="page-title">
                    <h3>View Vehicle Details</h3>
                    </div>
                </div>
            </div>
            <!-- END: Left Section -->

            <!-- START: Right Section -->
            <div class="col-12 col-md-8">
                <div class="page-header float-md-right text-center text-md-right">
                    <div class="page-title">
                        <ol class="breadcrumb d-flex justify-content-center justify-content-md-end text-right" style="background: transparent;">
                        <li><a href="dashboard.php">Dashboard</a></li>
                                    <li><a href="view-vehicle.php">View Vehicle</a></li>
                                    <li class="active">View Vehicle details</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- END: Right Section -->
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
                  
              <?php
 $cid=$_GET['viewid'];
 $ret = mysqli_query($con, "SELECT *, 
 DATE_FORMAT(InTime, '%h:%i %p %m-%d-%Y') AS FormattedInTime, 
 DATE_FORMAT(OutTime, '%h:%i %p %m-%d-%Y') AS FormattedOutTime 
FROM tblvehicle WHERE ID='$cid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>                       
                    <div class="table-responsive">
                    <table border="1" class="table table-bordered table-striped  mg-b-0">
                              
<tr>
                                <th>Vehicle Category</th>
                                   <td><?php  echo $row['VehicleCategory'];?></td>
                                   </tr>
                                   <tr>
                                <th>Vehicle Company Name</th>
                                   <td><?php  echo $packprice= $row['VehicleCompanyname'];?></td>
                                   </tr>
                                   <tr>
                                <th>Model</th>
                                   <td><?php  echo $packprice= $row['Model'];?></td>
                                   </tr>
                                   <tr>
                                <th>Color</th>
                                   <td><?php  echo $packprice= $row['Color'];?></td>
                                   </tr>
                                <tr>
                                <th>Registration Number</th>
                                   <td><?php  echo $row['RegistrationNumber'];?></td>
                                   </tr>
                                   <tr>
                                    <th>Owner Name</th>
                                      <td><?php  echo $row['OwnerName'];?></td>
                                  </tr>
                                      <tr>  
                                       <th>Owner Contact Number</th>
                                        <td><?php  echo $row['OwnerContactNumber'];?></td>
                                    </tr>
                               <th>Registration Date</th>
                                <td><?php  echo $row['FormattedInTime'];?></td>
                           <!-- <tr>
    <th>Status</th>
    <td> 
        <?php  
if($row['Status']=="")
{
  echo "Vehicle In";
}
if($row['Status']=="Out")
{
  echo "Vehicle out";
}

     ;?></td>
  </tr>-->
   
<tr>


</table>
</div>
<?php } ?>

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
<?php }  ?>