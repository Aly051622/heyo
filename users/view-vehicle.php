
<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
  } else{


    $query = "SELECT v.*, u.FirstName, u.LastName
    FROM tblvehicle v
    JOIN tblregusers u ON u.MobileNumber = v.OwnerContactNumber
    WHERE v.vehid = '$vehicleId'"; // Adjust this based on how you're fetching the vehicle details

  ?>
  <!doctype html>
  <html lang="">
  <head>
      <title>CTU- Danao Parking System - View Vehicle Parking Details</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
      
  
      <style>
          html, body {
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
  
         
          /* Card and button styles */
          .card,
          .card-header {
              box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
          }
  
          #printbtn:hover,
          #viewbtn:hover, .download-icon:hover, .btn-sm:hover, .btn:hover {
              background-color: darkblue;
              border: solid blue;
              color: white;
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
              font-weight: bold;
              transition: background-color 0.3s ease;
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
      </style>
  </head>
  <body>
    
<?php include_once('includes/header.php');?>
    <?php include_once('includes/sidebar.php'); ?>
   

<div class="right-panel">
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-1">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h3>Owned Vehicles</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right" style="background: transparent">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="view-vehicle.php">View Vehicle Parking Details</a></li>
                                <li class="active">View Vehicle Parking Details</a></li>
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
                            <div class="table-responsive">
                                <?php
                                $ownerno = $_SESSION['vpmsumn'];
                                $ret = mysqli_query($con, "SELECT RegistrationNumber, Model, VehicleCompanyname, Color, ImagePath, QRCodePath, ID as vehid FROM tblvehicle WHERE OwnerContactNumber='$ownerno'");

                                while ($row = mysqli_fetch_array($ret)) {
                                    $imagePath = $row['ImagePath'];
                                    $qrCodePath = !empty($row['QRCodePath']) && strpos($row['QRCodePath'], 'qrcodes/') === false 
                                        ? '../admin/qrcodes/' . $row['QRCodePath'] 
                                        : '../admin/' . $row['QRCodePath'];
                                    $fullImagePath = __DIR__ . '/' . $imagePath;
                                ?>
                                    <div class="d-flex align-items-center  rounded p-0.2 mb-3">
                                        <div class="flex-shrink-0 mr-3">
                                            <?php if (!empty($imagePath) && file_exists($fullImagePath)) { ?>
                                                <img src="<?php echo $imagePath; ?>" alt="Vehicle Image" style="width: 170px; height: 100px;" />
                                            <?php } else { ?>
                                                <div class="image-placeholder">Image not found</div>
                                            <?php } ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>Plate Number:</strong> <?php echo $row['RegistrationNumber']; ?></p>
                                                    <p><strong>Make/Brand:</strong> <?php echo $row['VehicleCompanyname']; ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Model:</strong> <?php echo $row['Model']; ?></p>
                                                    <p><strong>Color:</strong> <?php echo $row['Color']; ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php if (!empty($row['QRCodePath']) && file_exists($row['QRCodePath'])) { ?>
                                                        <p style="margin: 0;"><strong>Download QR Code</strong></p>
                                                        <img src="<?php echo htmlspecialchars($row['QRCodePath']); ?>" alt="User's QR Code" style="width:100px;height:100px;" class="img-fluid" />
                                                        <a href="<?php echo htmlspecialchars($row['QRCodePath']); ?>" download="<?php echo basename($row['QRCodePath']); ?>" class="download-icon">
                                                            <i class="bi bi-download" aria-hidden="true"></i> <span class="sr-only">Download QR Code</span>
                                                        </a>
                                                    <?php } else { ?>
                                                        <p>QR Code image not found</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="view--detail.php?viewid=<?php echo $row['vehid']; ?>" class="btn btn-primary mr-2" id="viewbtn">🖹 View</a>
                                                <a href="print.php?vid=<?php echo $row['vehid']; ?>" target="_blank" class="btn btn-warning" id="printbtn">🖶 Print</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
                                                    
</div>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="../admin/assets/js/main.js"></script>
</body>
</html>

<?php } ?>