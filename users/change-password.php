<?php
session_start();
include('../DBconnection/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['vpmsuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $userid = $_SESSION['vpmsuid'];
        $cpassword = $_POST['currentpassword'];
        $newpassword = $_POST['newpassword'];

        // Fetch user details based on ID
        $query1 = mysqli_query($con, "SELECT Password FROM tblregusers WHERE ID='$userid'");
        $row = mysqli_fetch_assoc($query1);

        // Check if the current password matches with the one stored in the database
        if ($row && password_verify($cpassword, $row['Password'])) {
            // If matches, update the password
            $newpassword_hashed = password_hash($newpassword, PASSWORD_DEFAULT);
            $ret = mysqli_query($con, "UPDATE tblregusers SET Password='$newpassword_hashed' WHERE ID='$userid'");

            if ($ret) {
                echo '<script>alert("Your password successfully changed.")</script>';
            } else {
                echo '<script>alert("Something went wrong. Please try again later.")</script>';
            }
        } else {
            echo '<script>alert("Your current password is wrong.")</script>';
        }

} 

  
  ?>
<!doctype html>
<html class="no-js" lang="">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CTU- Danao Parking System - Change Password</title>
   
    <link rel="apple-touch-icon" href="../images/aa.png">
    <link rel="shortcut icon" href="../images/aa.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
<script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
} 

</script>
<style>
    html, body{
        height: 100vh;
        overflow: auto;
        background: whitesmoke;
    }
    .btn-sm{
        padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: solid white;
            cursor: pointer;
            border-radius: 9px;
            margin-left: 10px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .btn-sm:hover{
                background-color: darkblue;
                border: solid blue;
            }
            .input-group-append .btn {
    border: none;
    background: transparent;
}
    </style>
</head>
<body>
   <?php include_once('includes/sidebar.php');?>
    <!-- Right Panel -->

   <?php include_once('includes/header.php');?>
<div class="right-panel mb-5">

        <div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <!-- START: Left Section -->
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <div class="page-header float-md-left text-center text-md-left">
                    <div class="page-title">
                        <h3>Change Password</h3>
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
                                    <li><a href="change-password.php">Change Password</a></li>
                                    <li class="active">Change Password</li>
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



                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Change </strong> Password
                            </div>
                            <div class="card-body card-block">
                                <form action="" method="post" class="form-horizontal" name="changepassword" onsubmit="return checkpass();">
                                   
                                   
                                <div class="row form-group">
                    <div class="col col-md-3"><label for="currentpassword" class="form-control-label">Current Password</label></div>
                    <div class="col-12 col-md-9">
                        <div class="input-group">
                            <input type="password" name="currentpassword" id="currentpassword" class="form-control" required="true">
                            <div class="input-group-append">
                                <button type="button" class="btn toggle-password" data-target="#currentpassword" style="background: none; border: none; font-weight: bold;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col col-md-3"><label for="newpassword" class="form-control-label">New Password</label></div>
                    <div class="col-12 col-md-9">
                        <div class="input-group">
                            <input type="password" name="newpassword" id="newpassword" class="form-control" required="true"
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                                title="Password must be at least 8 characters long, and include at least one lowercase letter, one uppercase letter, one number, and one special character.">
                            <div class="input-group-append">
                                <button type="button" class="btn toggle-password" data-target="#newpassword" style="background: none; border: none; font-weight: bold;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col col-md-3"><label for="confirmpassword" class="form-control-label">Confirm Password</label></div>
                    <div class="col-12 col-md-9">
                        <div class="input-group">
                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required="true">
                            <div class="input-group-append">
                                <button type="button" class="btn toggle-password" data-target="#confirmpassword" style="background: none; border: none; font-weight: bold;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                                   
                                  
                                    
                                    
                                   <p style="text-align: center;"> <button type="submit" class="btn btn-primary btn-sm" name="submit" >⚙ Change</button></p>
                                </form>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="col-lg-6">
                        
                  
                </div>

           

            </div>


        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>
</div>
</div><!-- /#right-panel -->

<!-- Right Panel -->
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.querySelector(button.getAttribute('data-target'));
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="../admin/assets/js/main.js"></script>


</body>
</html>
<?php }  ?>