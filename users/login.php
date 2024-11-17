<?php
session_start();
include('../includes/dbconnection.php'); // Ensure the path is correct to connect to the database
require '../vendor/autoload.php';  // Corrected path to autoload.php for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['login'])) {
    $emailOrMobile = $_POST['emailcont'];
    $password = $_POST['password'];

    // Check if the input is an email or mobile number
    if (filter_var($emailOrMobile, FILTER_VALIDATE_EMAIL)) {
        $condition = "Email='$emailOrMobile'";
    } else {
        $condition = "MobileNumber='$emailOrMobile'";
    }

    // Fetch user details based on email or mobile number
    $query = mysqli_query($con, "SELECT ID, Email, MobileNumber, Password, FailedAttempts, LastFailedAttempt FROM tblregusers WHERE $condition");
    $row = mysqli_fetch_assoc($query);

    // If user exists
    if ($row) {
        // Check if the account is locked due to failed attempts
        $failedAttempts = $row['FailedAttempts'];
        $lastFailedAttempt = strtotime($row['LastFailedAttempt']);
        $currentTime = time();
        $lockDuration = 300; // Lock duration in seconds (e.g., 5 minutes)

        if ($failedAttempts >= 3 && ($currentTime - $lastFailedAttempt) < $lockDuration) {
            // Account is locked, show message and do not proceed
            echo "<script>alert('Your account is locked due to multiple failed login attempts. Please try again later.');</script>";
        } else {
            // Check if the password is correct
            if (password_verify($password, $row['Password'])) {
                // Reset failed attempts after successful login
                mysqli_query($con, "UPDATE tblregusers SET FailedAttempts = 0 WHERE ID = {$row['ID']}");
                $_SESSION['vpmsuid'] = $row['ID'];
                $_SESSION['vpmsumn'] = $row['MobileNumber'];
                header('location:dashboard.php');
            } else {
                // Increment failed attempts
                $newFailedAttempts = $failedAttempts + 1;
                $currentTimeFormatted = date('Y-m-d H:i:s', $currentTime);
                mysqli_query($con, "UPDATE tblregusers SET FailedAttempts = $newFailedAttempts, LastFailedAttempt = '$currentTimeFormatted' WHERE ID = {$row['ID']}");

                // If 3 attempts failed, lock the account
                if ($newFailedAttempts >= 3) {
                    // Lock the account and send an email notification
                    sendLockNotificationEmail($row['Email']);
                    echo "<script>alert('Your account is now locked. Please try again after 5 minutes.');</script>";
                } else {
                    // Display remaining attempts
                    $remainingAttempts = 3 - $newFailedAttempts;
                    echo "<script>alert('Invalid credentials. You have $remainingAttempts attempts left.');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('User does not exist.');</script>";
    }
}

// Function to send account lock notification email
function sendLockNotificationEmail($email) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'developershalcyon@gmail.com';  // Use your email here
        $mail->Password = 'uhdv sagp oljc smwm';  // Use your app password here
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('developershalcyon@gmail.com', 'CTU Parking System');
        $mail->addAddress($email);  // Send to the user's email
        $mail->Subject = 'Account Locked Due to Failed Login Attempts';
        $mail->Body = 'Your account has been temporarily locked due to multiple failed login attempts. Please try again after a few minutes.';

        // Send the email
        if ($mail->send()) {
            // Email sent successfully, nothing more to do here
            error_log("Account lock notification sent to $email");
        } else {
            error_log('Failed to send lock notification email: ' . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $e->getMessage());
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
    .pass-key:invalid ~ .show{
      display: none;
    }
    .forget-pass{
      margin-top: 15px;
      font-size: 15px;
      color: black;
    }
    .forget-pass a{
      color: black;
      font-weight: 600;
    }
    .login-btn{
      margin-top: 25px;
      width: 100%;
      padding: 15px;
      border-radius: 7px;
      background: #ff6600;
      color: white;
      border: none;
      font-size: 16px;
      font-weight: 600;
    }
    .login-btn:hover{
      background: #f44336;
      cursor: pointer;
    }
    .login-btn:focus{
      outline: none;
    }
    .media-links{
      margin-top: 30px;
    }
    .media-links li{
      display: inline-block;
      margin-right: 20px;
      font-size: 23px;
      color: white;
    }
    .media-links li a{
      color: white;
      font-size: 27px;
    }
    .media-links li a:hover{
      color: #f44336;
    }
    .footer-text{
      margin-top: 70px;
      font-size: 14px;
      color: #ffffff;
      line-height: 23px;
    }
    .footer-text a{
      color: #ff6600;
      font-weight: 600;
    }

    @media (max-width: 420px){
      .content{
        width: 290px;
        padding: 40px 30px;
      }
      .content header{
        font-size: 26px;
      }
    }
  </style>
</head>
<body>
   <div class="bg-img">
      <div class="content">
         <header>Login</header>
         <form method="post">
            <div class="field">
               <span><i class="fa fa-envelope"></i></span>
               <input type="text" name="emailcont" placeholder="Email or Mobile Number" required>
            </div>
            <div class="field space">
               <span><i class="fa fa-lock"></i></span>
               <input type="password" class="pass-key" name="password" placeholder="Password" required>
            </div>
            <div class="space">
               <button class="login-btn" type="submit" name="login">Login</button>
            </div>
         </form>
      </div>
   </div>
</body>
</html>
