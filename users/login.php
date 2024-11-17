<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/db.php'); // Include your DB connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer autoloader (change the path if necessary)
require 'vendor/autoload.php';

// Retrieve user input (username and password)
$email = $_POST['email'];
$password = $_POST['password'];

// Query to find user by email
$query = "SELECT * FROM tblregusers WHERE Email = '$email'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    // User not found
    echo "<script>alert('User not found.');</script>";
    exit;
}

// Check if the account is locked
if ($row['AccountLocked'] == 1) {
    echo "<script>alert('Your account is temporarily locked due to multiple failed attempts. Please check your email for further instructions.');</script>";
    exit;
}

// Validate password
if (password_verify($password, $row['Password'])) {
    // Successful login, reset FailedAttempts
    mysqli_query($con, "UPDATE tblregusers SET FailedAttempts = 0 WHERE Email = '$email'");
    echo "<script>alert('Login successful');</script>";
    // Redirect to dashboard or home page
    header("Location: dashboard.php");
    exit;
} else {
    // Failed login attempt
    $failedAttempts = $row['FailedAttempts'] + 1;
    $lastFailedAttempt = time();
    
    // Update FailedAttempts and LastFailedAttempts in the database
    mysqli_query($con, "UPDATE tblregusers SET FailedAttempts = $failedAttempts, LastFailedAttempts = $lastFailedAttempt WHERE Email = '$email'");
    
    if ($failedAttempts >= 3) {
        // Lock the account after 3 failed attempts
        mysqli_query($con, "UPDATE tblregusers SET AccountLocked = 1 WHERE Email = '$email'");
        
        // Send lock notification via email
        sendLockNotification($row['Email']);
        
        echo "<script>alert('Your account has been locked due to 3 failed attempts. Please check your email for further instructions.');</script>";
    } else {
        // Inform the user about remaining attempts
        echo "<script>alert('Incorrect password. You have " . (3 - $failedAttempts) . " attempt(s) left.');</script>";
    }
    exit;
}

// Function to send account lock notification email
function sendLockNotification($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use your SMTP server here
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Replace with your email
        $mail->Password = 'your-email-password'; // Replace with your app password or email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Parking System');
        $mail->addAddress($email); // Add recipient's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Locked - Multiple Failed Login Attempts';
        $mail->Body    = 'Your account has been temporarily locked due to 3 failed login attempts. Please contact support to unlock your account.';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>






<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Client Login | CTU DANAO Parking System</title>
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
      <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
      <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Poppins:400,500&display=swap');
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      user-select: none;
    }
    .bg-img{
      background: url('images/ctuser.png');
      height: 100vh;
      background-size: cover;
      background-position: center;
    }
    .bg-img:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100vh;
      width: 100%;
      background: rgba(0,0,0,0.7);
    }
    .content {
      border-radius: 20px;
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 999;
      text-align: center;
      padding: 60px 32px;
      width: 370px;
      transform: translate(-50%, -50%);
      background-color:#ff9933;
      box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

    .content header{
      color: white;
      font-size: 33px;
      font-weight: 600;
      margin: 0 0 35px 0;
      font-family: 'Montserrat',sans-serif;
    }
    .field{
      position: relative;
      height: 40px;
      width: 100%;
      display: flex;
      background: rgba(255,255,255,0.94);
      border-radius: 7px;
    }
    .field span{
      color: black;
      width: 40px;
      line-height: 45px;
    }
    .field input{
      height: 100%;
      width: 100%;
      background: transparent;
      border: none;
      outline: none;
      color: black;
      font-size: 14px;
      font-family: 'Poppins',sans-serif;
    }
    .space{
      margin-top: 16px;
    }
    .show{
      position: absolute;
      right: 13px;
      font-size: 13px;
      font-weight: 700;
      color: black;
      display: none;
      cursor: pointer;
      font-family: 'Montserrat',sans-serif;
    }
    .pass-key:valid ~ .show{
      display: block;
    }
    .pass{
      text-align: left;
      margin: 10px 0;
    }
    .pass a{
      color: white;
      text-decoration: none;
      font-family: 'Poppins',sans-serif;
    }
    .pass:hover a{
      text-decoration: underline;
    }
    .field input[type="submit"]{
      border-radius: 9px;
      background-color: rgb(53, 97, 255);        
      color: white;
      border: solid ;
        cursor:pointer;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .field input[type="submit"]:hover{
      background-color: darkblue;
      border: solid blue;

    }
    .login{
      margin: 20px 0;
      font-family: 'Poppins',sans-serif;
    }
    .links{
      display: flex;
      cursor: pointer;
      color: white;
      margin: 0 0 20px 0;
    }
    .facebook,.instagram{
      width: 100%;
      height: 45px;
      line-height: 45px;
      margin-left: 10px;
    }
    .facebook{
      margin-left: 0;
      background: #4267B2;
      border: 1px solid #3e61a8;
    }
    .instagram{
      background: #E1306C;
      border: 1px solid #df2060;
    }
    .facebook:hover{
      background: #3e61a8;
    }
    .instagram:hover{
      background: #df2060;
    }
    .links i{
      font-size: 17px;
    }
    i span{
      margin-left: 8px;
      font-weight: 500;
      letter-spacing: 1px;
      font-size: 16px;
      font-family: 'Poppins',sans-serif;
    }
    .signup{
      font-size: 15px;
      color: white;
      font-family: 'Poppins',sans-serif;
    }
    .signup a{
      color: #3498db;
      text-decoration: none;
    }
    .signup a:hover{
      text-decoration: underline;
    }

    input[type="text"]:hover, input[type="password"]:hover {
                background-color: whitesmoke;
                border: 2px solid lightblue;
            }

    #home{
      margin: 2vw 0 0 15vw; /* Adjusted margin for responsiveness */
        background-color: rgb(53, 97, 255);
        border-radius: 10px;
        cursor: pointer;
        border: solid;
        font-weight:bold;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
      }
    #home:hover{
      background-color: darkblue;
        border: solid blue;
        border-radius: 10px;
    }

    /*bag o ni nga loading*/
    #loading-spinner {
      margin-top:7px;
        display: none;
        color: white;
      }

      /*Responsiveness for all*/
      @media (max-width: 768px) {
    .content {
        width: 80%; /* Adjust width for smaller screens */
        padding: 40px 24px; /* Reduce padding */
        border-radius: 15px; /* Adjust border-radius */
    }
}

@media (max-width: 480px) {
    .content {
        width: 85%; /* Further reduce width for very small screens */
        padding: 30px 20px; /* Further reduce padding */
        border-radius: 20px; /* Adjust border-radius for a smaller look */
        height: 450px;
    }
    .bg-img:after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      height: 100vh;
      width: 100%;
      background: rgba(0,0,0,0.7);
    }
    #home{
      margin-left: 12.5em;
    }
}
        </style>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('form');
        const loadingSpinner = document.getElementById('loading-spinner');

        loginForm.addEventListener('submit', function() {
          // Show the loading spinner when the form is submitted
          loadingSpinner.style.display = 'inline-block';
        });
      });
</script>
   
    </head>
   <body>
      <div class="bg-img">
         <div class="content">
         <a style="text-decoration:none;">   
         <header>C L I E N T  &nbsp; LOGIN</header> </a>
    <form method="post">
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="text" name="emailcont" required="true" placeholder="Registered Email or Contact Number" required="true" class="form-control">
               </div>
               <div class="field space">
              <span class="fa fa-lock"></span>
              <input type="password" id="password" class="form-control" name="password" placeholder="Password" required="true">
              <span class="show" id="show-password"><i class="fas fa-eye-slash"></i></span>
            </div>
               <div class="pass">
               <a href="forgot-password.php">Forgot Password?</a>
               </div>
               <div class="field" style="color: white;">
                  <input type="submit"name="login" value="LOGIN" id="loginbtn"  style="color: white;">
               </div>
               <div class="signup space">
               Don't have account?
               <a href="signup.php" style="color: white; font-weight: 500;">Signup Now!</a>
            </div>
            <div id="loading-spinner" class="fa fa-spinner fa-spin fa-3x"></div>

               <a href="../welcome.php" class="btn btn-primary" id="home">
                <span class="glyphicon glyphicon-home"></span> Home</a>
    </form>
  
         </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
  const passField = document.querySelector('#password');
      const showBtn = document.querySelector('#show-password');

      showBtn.addEventListener('click', function() {
        if (passField.type === "password") {
          passField.type = "text";
          showBtn.innerHTML = '<i class="fas fa-eye"></i>'; 
          showBtn.style.color = "red"; 
        } else {
          passField.type = "password";
          showBtn.innerHTML = '<i class="fas fa-eye-slash"></i>'; 
          showBtn.style.color = "black"; // 
        }
      });
</script>

   </body>
</html>
