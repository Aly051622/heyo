<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
session_start();
include('../DBconnection/dbconnection.php');

// Check if user is logged in
if (empty($_SESSION['vpmsuid'])) {
    header('location:logout.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_SESSION['vpmsuid'];
    $fname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lname = mysqli_real_escape_string($con, $_POST['lastname']);
    $registration_status = mysqli_real_escape_string($con, $_POST['registration_status']);

    $or_image = $cr_image = $nv_image = '';
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die('Failed to create directory.');
        }
    }

    // Handle file uploads
    if ($registration_status === 'registered') {
        if (isset($_FILES['OR_image']) && $_FILES['OR_image']['error'] === UPLOAD_ERR_OK) {
            $or_image = basename($_FILES['OR_image']['name']);
            move_uploaded_file($_FILES['OR_image']['tmp_name'], $uploadDir . $or_image);
        }
        if (isset($_FILES['CR_image']) && $_FILES['CR_image']['error'] === UPLOAD_ERR_OK) {
            $cr_image = basename($_FILES['CR_image']['name']);
            move_uploaded_file($_FILES['CR_image']['tmp_name'], $uploadDir . $cr_image);
        }
    } elseif ($registration_status === 'for_registration') {
        if (isset($_FILES['NV_image']) && $_FILES['NV_image']['error'] === UPLOAD_ERR_OK) {
            $nv_image = basename($_FILES['NV_image']['name']);
            move_uploaded_file($_FILES['NV_image']['tmp_name'], $uploadDir . $nv_image);
        }
    }

    $updateQuery = "
        UPDATE tblregusers SET 
        FirstName = '$fname',
        LastName = '$lname',
        registration_status = '$registration_status',
        or_image = IF('$or_image' != '', '$or_image', or_image),
        cr_image = IF('$cr_image' != '', '$cr_image', cr_image),
        nv_image = IF('$nv_image' != '', '$nv_image', nv_image)
        WHERE ID = '$uid'
    ";

    if (mysqli_query($con, $updateQuery)) {
        echo '<script>alert("Profile updated successfully.")</script>';
        echo '<script>window.location.href="profile.php"</script>';
        exit;
    } else {
        echo '<script>alert("Error: ' . mysqli_error($con) . '")</script>';
    }
}

$uid = $_SESSION['vpmsuid'];
$ret = mysqli_query($con, "SELECT * FROM tblregusers WHERE ID='$uid'");
$row = mysqli_fetch_array($ret);
$orImage = !empty($row['or_image']) ? 'uploads/' . htmlspecialchars($row['or_image']) : '';
$crImage = !empty($row['cr_image']) ? 'uploads/' . htmlspecialchars($row['cr_image']) : '';
$nvImage = !empty($row['nv_image']) ? 'uploads/' . htmlspecialchars($row['nv_image']) : '';
$registrationStatus = htmlspecialchars($row['registration_status']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="apple-touch-icon" href="../images/aa.png">
      <link rel="shortcut icon" href="../images/aa.png">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    

</head>
    <style>

#notification {
    position: fixed;
    top: 10px;
    right: 10px;
    background-color: #f8d7da; 
    color: #721c24; 
    border: 1px solid #f5c6cb; 
    border-radius: 5px;
    padding: 15px; 
    display: none;
    z-index: 9999;
    width: 300px;
    max-width: 90%;
    font-size: 16px; 
}

#notification span {
    display: inline-block;
    vertical-align: middle;
}

#notification button {
    float: right;
    border: none;
    background: none;
    color: #721c24; /* Dark red color for the close button */
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
}
html,body{
        background-color: whitesmoke;
        height: 100vh;
        overflow: auto;
    }
    .card, .card-header{
        box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
            top: 50%;
                 }
                 .close, .btn{
                border: solid lightgray;
                border-radius: 10px;
                padding: 10px;
                background-color: rgb(53, 97, 255);
                color: white;
                cursor: pointer;
                font-family: 'Monsterrat', sans-serif;
                font-weight: bolder;
        }

         .close:hover  .btn:hover{
                background-color: darkblue;
                border: solid blue;
            }
         .btn{
            cursor: pointer;
         }
           /* Container for images */
    .imgp { 
        background-color: transparent; 
        border-radius: 8px;
        width: 935px;
    }
    .imgp input[type="file"]{
        margin-left: 1em;
        margin-top:-20px;
        cursor: pointer;
        text-align: left;
    }
    .img-fluid{
        height: 300px;
        width: auto;
        justify-content: center;
        align-items:center;
    }

    /* Style for clickable images */
    .clickable-image {
        width: 100%; 
        height: auto; 
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px; 
        cursor: pointer; 
        transition: transform 0.2s ease-in-out; 
    }

    .clickable-image:hover {
        transform: scale(1.1); 
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); 
    }
    .modal-backdrop {
    z-index: 1040; 
    pointer-events: none;
    background-color: transparent;
    }

    .modal-body {
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: auto; 
}

.modal-body img {
    max-width: none; 
    max-height: none; 
}

.modal-dialog {
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100vh;
    margin: 0 auto;
    overflow: hidden;
}

.modal-content {
    background: transparent; 
    border: none;
    box-shadow: none; 
}

.modal.fade .modal-dialog {
    transform: none; 
}

.table-responsive{
    overflow: hidden;
}
    .reg{
        margin-left: 18px;
        width: 500px;
    }
    @media (max-width: 560px){
        .regs{
            max-width: 250px;
        }
        .fil{
            margin-top: 20px;
            margin-left: 10px;
            max-width: 250px;
        }
    }
    @media (max-width: 480px){
        .regs{
            max-width: 250px;
        }
        .fil{
            margin-top: 20px;
            margin-left: 10px;
            max-width: 250px;
        }
    }
    @media (max-width: 300px){
        .regs{
            max-width: 250px;
        }
        .fil{
            margin-top: 20px;
            margin-left: 10px;
            max-width: 250px;
        }
    }
    @media (max-width: 500px){
        .regs{
            max-width: 250px;
        }
        .fil{
            margin-top: 20px;
            margin-left: 10px;
            max-width: 250px;
        }
    }

    /*for all the braeadcrumbs for users*/
@media (max-width: 1024px) {
    .breadcrumbs {
        width: 95%;
        margin-left: 3em;
    }
}

@media (max-width: 954px) {
    .breadcrumbs {
        width: 90%;
        margin-left: 2em;
    }

}

@media (max-width: 780px) {
    .breadcrumbs {
        width: 85%;
        margin-left: 1.5em;
    }

}

@media (max-width: 500px) {
    .breadcrumbs {
        width: 60%;
        margin-left: 2em;
        padding: 5px;
    }

}

@media (max-width: 480px) {
    
    .breadcrumbs {
        width: 60%;
        margin-left: 2em;
        padding: 5px;
    }
 
}


@media (max-width: 300px) {
    .breadcrumbs {
        width: 60%;
        margin-left: 2em;
        padding: 5px;
    }

}
    
</style>
<body>
    <!-- Include sidebar -->
    <?php include_once('includes/sidebar.php'); ?>
    
   <?php include_once('includes/header.php');?>

   <!-- Notification system -->
  
    <div id="notification"><!-- START: Notification -->
        <span id="notification-message"><?php if (isset($_GET['notification'])) echo htmlspecialchars($_GET['notification']); ?></span>
        <button id="notification-close">&times;</button>
    </div><!-- END: Notification -->
    <div class="right-panel"><!-- START: Right Panel -->
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                    <div class="page-title">
                        <h3>My Profile</h3>
                    </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right" style="background: transparent;">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li class="profile.php">Profile</li>
                                <li class="active">User's Information</li>                              
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content"><!-- START: Content -->
        <div class="animated fadeIn"><!-- START: Animated Fade In -->

            <div class="col-lg-12"><!-- START: Column -->
                <div class="card table-responsive"><!-- START: Card -->
                    <div class="card-header">
                        <strong>User </strong> Profile
                    </div>
                    <div class="card-body card-block"><!-- START: Card Body -->
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"><!-- START: Form -->

                            <?php
                                $uid = $_SESSION['vpmsuid'];
                                $ret = mysqli_query($con, "SELECT * FROM tblregusers WHERE ID='$uid'");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                            ?>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class="form-control-label">First Name</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="firstname" required="true" class="form-control" readonly="true" value="<?php echo $row['FirstName']; ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="email-input" class="form-control-label">Last Name</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="lastname" required="true" class="form-control" readonly="true" value="<?php echo $row['LastName']; ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="password-input" class="form-control-label">Contact Number</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="mobilenumber" maxlength="10" pattern="[0-9]{10}" readonly="true" class="form-control" value="<?php echo $row['MobileNumber']; ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="disabled-input" class="form-control-label">Email Address</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="email" name="email" required="true" class="form-control" readonly="true" value="<?php echo $row['Email']; ?>">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="disabled-input" class="form-control-label">Registration</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" name="regdate" readonly="true" class="form-control" value="<?php echo $row['RegDate']; ?>">
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="container"><!-- START: Container -->
                                <div class="row">
                                    <?php if ($orImage): ?>
                                        <div class="col-md-4 mb-4">
                                            <h4>OR File</h4>
                                            <img src="<?php echo $orImage; ?>" alt="OR File" class="img-fluid clickable-image" data-toggle="modal" data-target="#imageModal" data-title="OR File">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($crImage): ?>
                                        <div class="col-md-4 mb-4">
                                            <h4>CR File</h4>
                                            <img src="<?php echo $crImage; ?>" alt="CR File" class="img-fluid clickable-image" data-toggle="modal" data-target="#imageModal" data-title="CR File">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($nvImage): ?>
                                        <div class="col-md-4 mb-4">
                                            <h4>MV File</h4>
                                            <img src="<?php echo $nvImage; ?>" alt="NV File" class="img-fluid clickable-image" data-toggle="modal" data-target="#imageModal" data-title="NV File">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div><!-- END: Container -->

                            <div class="reg"><!-- START: Registration -->
                                <p>Registration Status: <?php echo $registrationStatus; ?></p>

                                <!-- START: Persistent Notification System -->
                                <div id="notification" style="display: none;">
                                    <span id="notification-message">Please upload your files.</span>
                                    <button id="notification-close"><i class="bi bi-x-circle-fill"></i></button>
                                </div><!-- END: Persistent Notification System -->

                                <form id="upload-form" action="your_upload_handler.php" method="POST" enctype="multipart/form-data"><!-- START: Upload Form -->
                                    <div class="row form-group">
                                        <div class="col-md-3">
                                            <label for="registration-status" class="form-control-label">Registration Status</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select id="registration-status" name="registration_status" class="form-control regs" required>
                                                <option value="" disabled selected>Select Registration Status</option>
                                                <option value="for_registration" <?php echo $registrationStatus === 'for_registration' ? 'selected' : ''; ?>>For Registration</option>
                                                <option value="registered" <?php echo $registrationStatus === 'registered' ? 'selected' : ''; ?>>Registered</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="imgp"><!-- START: File Uploads -->
                                        <div class="row form-group" id="for-registration-files" style="display: none;">
                                            <div class="col-md-3">
                                                <label for="nv-file" class="form-control-label">Upload MV File</label>
                                            </div><br>
                                            <div class="col-md-9">
                                                <input type="file" id="nv-file" name="NV_image" accept=".jpeg, .jpg" class="form-control fil">
                                            </div>
                                        </div>

                                        <div class="row form-group" id="registered-files" style="display: none;">
                                            <div class="col-md-3">
                                                <label for="or-file" class="form-control-label">Upload OR File</label>
                                            </div>
                                            <div class="col-md-9"><br>
                                                <input type="file" id="or-file" name="OR_image" accept=".jpeg, .jpg" class="form-control fil">
                                            </div><br>
                                            <div class="col-md-3">
                                                <label for="cr-file" class="form-control-label">Upload CR File</label>
                                            </div>
                                            <div class="col-md-9"><br>
                                                <input type="file" id="cr-file" name="CR_image" accept=".jpeg, .jpg" class="form-control fil">
                                            </div>
                                        </div>
                                    </div><!-- END: File Uploads -->

                                    <div class="row form-group">
                                        <div class="col text-center">
                                            <button type="submit" id="submit-button" class="btn btn-primary btn-sm">
                                                <i class="bi bi-images"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </form><!-- END: Upload Form -->
                            </div><!-- END: Registration -->
                        </form><!-- END: Form -->
                    </div><!-- END: Card Body -->
                </div><!-- END: Card -->
            </div><!-- END: Column -->
        </div><!-- END: Animated Fade In -->
    </div><!-- END: Content -->
     <!-- START: Bootstrap Modal -->
     <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
                                    <div class="modal-content">
                                        <div class="modal-header" style="padding: 2px;">
                                            <h5 class="modal-title" id="imageModalTitle">Image Preview</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none;">
                                                <span aria-hidden="true"><i class="bi bi-x-circle-fill"></i></span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img id="modalImage" src="" alt="Preview Image" style="display: block; margin: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div><!-- END: Bootstrap Modal -->
</div><!-- END: Right Panel -->



                        <script>
                        $(document).on('click', '.clickable-image', function () {
                            const src = $(this).attr('src'); 
                            const title = $(this).attr('alt'); 
                            const img = new Image(); 
                            img.src = src; 

                            img.onload = function () {
                                const naturalWidth = img.naturalWidth;
                                const naturalHeight = img.naturalHeight;

                                $('#modalImage').attr('src', src).css({
                                    display: 'block',
                                    width: naturalWidth > window.innerWidth ? '90%' : `${naturalWidth}px`, 
                                    height: naturalHeight > window.innerHeight ? '90%' : `${naturalHeight}px`, 
                                    maxWidth: '100%',
                                    maxHeight: '100%',
                                    objectFit: 'contain',
                                });

                                // Update modal title dynamically if needed
                                if (title) {
                                    $('#imageModalTitle').text(title);
                                }

                                // Open the modal
                                $('#imageModal').modal('show');
                            };
                        });
                        </script>


                        <!-- Include Bootstrap JS and jQuery -->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                        <!-- JavaScript for Modal Image Handling -->
                        <script>
                        $(document).on('click', '.clickable-image', function () {
                            const src = $(this).attr('src');
                            const img = new Image();
                            img.src = src;

                            img.onload = function () {
                                const modalImage = $('#modalImage');
                                const modalBody = $('.modal-body');

                                // Set the modal image source
                                modalImage.attr('src', src);

                                if (img.width > img.height) {
                                    modalImage.css({
                                        'max-width': '100%',
                                        'max-height': '100vh',
                                        'width': 'auto',
                                        'height': 'auto'
                                    });
                                } else {
                                    modalImage.css({
                                        'max-width': '60%',
                                        'max-height': '80vh',
                                        'width': 'auto',
                                        'height': 'auto'
                                    });
                                }

                                $('#imageModalTitle').text($(this).data('title'));
                            };
                        });

                        </script>
                        
                        <script>
                                // Show/hide upload fields based on selected registration status
                                document.getElementById('registration-status').addEventListener('change', function() {
                                    const registeredFiles = document.getElementById('registered-files');
                                    const forRegistrationFiles = document.getElementById('for-registration-files');

                                    if (this.value === 'registered') {
                                        registeredFiles.style.display = 'block';
                                        forRegistrationFiles.style.display = 'none';
                                    } else if (this.value === 'for_registration') {
                                        registeredFiles.style.display = 'none';
                                        forRegistrationFiles.style.display = 'block';
                                    } else {
                                        registeredFiles.style.display = 'none';
                                        forRegistrationFiles.style.display = 'none';
                                    }
                                });

                                // Close notification on click
                                document.getElementById('notification-close').addEventListener('click', function() {
                                    document.getElementById('notification').style.display = 'none';
                                });
                            </script>

                        <script>
                            function startReminderNotification() {
                                var notification = document.getElementById('notification');
                                var notificationMessage = document.getElementById('notification-message');
                                notification.style.display = 'block';

                                // Customize the message or keep it from PHP
                                notificationMessage.textContent = notificationMessage.textContent || "Please upload your files.";

                                // Toggle visibility every second
                                setInterval(function () {
                                    notification.style.display = notification.style.display === 'none' ? 'block' : 'none';
                                }, 1000); // Toggle visibility every second
                            }

                            function hideNotification() {
                                var notification = document.getElementById('notification');
                                notification.style.display = 'none';
                            }

                            document.addEventListener('DOMContentLoaded', function () {
                                var orImageUploaded = <?php echo json_encode(!empty($orImage)); ?>;
                                var crImageUploaded = <?php echo json_encode(!empty($crImage)); ?>;
                                var nvImageUploaded = <?php echo json_encode(!empty($nvImage)); ?>;

                                // Show notification only if no files are uploaded
                                if (!orImageUploaded && !crImageUploaded && !nvImageUploaded) {
                                    startReminderNotification();
                                }

                                // File input elements
                                var nvFileInput = document.getElementById('nv-file');
                                var orFileInput = document.getElementById('or-file');
                                var crFileInput = document.getElementById('cr-file');
                                var form = document.getElementById('upload-form');
                                var registrationStatus = document.getElementById('registration-status');

                                function anyFileUploaded() {
                                    // Check if any file has been uploaded, including PHP pre-upload checks
                                    return (
                                        nvFileInput.files.length > 0 ||
                                        orFileInput.files.length > 0 &&
                                        crFileInput.files.length > 0 &&
                                        orImageUploaded &&
                                        crImageUploaded ||
                                        nvImageUploaded
                                    );
                                }

                                function checkNotification() {
                                    if (anyFileUploaded()) {
                                        hideNotification(); // Hide notification if any file is uploaded
                                        return true;
                                    } else {
                                        startReminderNotification(); // Show notification if no files are uploaded
                                        return false;
                                    }
                                }

                                // Add event listeners to file inputs to check for uploads
                                nvFileInput.addEventListener('change', checkNotification);
                                orFileInput.addEventListener('change', checkNotification);
                                crFileInput.addEventListener('change', checkNotification);

                                // Handle registration status change
                                registrationStatus.addEventListener('change', function () {
                                    checkNotification();
                                });

                                const form = document.querySelector('#myForm');

                        function checkNotification() {
                            const fileInput = document.querySelector('#fileInput');
                            if (!fileInput.files.length) {
                                alert("Please upload a file before submitting!");
                                return false;
                            }
                            return true;
                        }

                        form.addEventListener('submit', function (event) {
                            if (!checkNotification()) {
                                event.preventDefault();
                            }
                        });


                                // Close button event listener for the notification
                                document.getElementById('notification-close').addEventListener('click', function () {
                                    hideNotification();
                                });

                                // Initial check and setup on page load
                                checkNotification();
                            });
                        </script>

                <!--<p style="text-align: center;">  <button type="submit" class="btn btn-sm" name="submit">⏏ Update</button>-->

    <div class="clearfix"></div>

<!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="../admin/assets/js/main.js"></script>

</body>
</html>
<?php  ?>