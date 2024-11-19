<?php
session_start();
include('includes/dbconnection.php'); // Ensure this path is correct

if (isset($_POST['submit'])) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $contno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType = $_POST['userType'];
    $place = $_POST['place'];
    $LicenseNumber = $_POST['LicenseNumber'];
    
    // Use the correct variable name
    $stmt = mysqli_prepare($con, "SELECT Email FROM tblregusers WHERE Email=? OR MobileNumber=?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $contno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<script>alert("This email or Contact Number is already associated with another account")</script>';
    } else {
        // Initialize $query
        $query = mysqli_prepare($con, "INSERT INTO tblregusers(FirstName, LastName, MobileNumber, Email, Password, user_type, place, LicenseNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($query) { // Check if the statement was prepared successfully
            mysqli_stmt_bind_param(
                $query,
                "ssssssss",
                $fname,
                $lname,
                $contno,
                $email,
                $password,
                $userType,
                $place,
                $LicenseNumber
            );

            if (mysqli_stmt_execute($query)) {
                // Send verification code after successful registration
                $_SESSION['verification_email'] = $email; // Store email in session
                echo '<script>
                    alert("You have successfully registered. A verification code has been sent to your email.");
                    window.location.href = "send_verification_code.php"; // Redirect to send verification code
                </script>';
            } else {
                echo '<script>alert("Something Went Wrong. Please try again")</script>';
            }

            mysqli_stmt_close($query); // Close the prepared statement
        } else {
            echo '<script>alert("Failed to prepare the SQL statement")</script>';
        }
    }

    mysqli_stmt_close($stmt); // Close the first prepared statement
    mysqli_close($con);
}
?>



<style>
   .success-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4CAF50;
        color: white;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        z-index: 9999;
    }

    .success-message {
        margin: 0;
        font-size: 16px;
        font-weight: bold;
    }
  </style>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Client Signup | CTU DANAO Parking System</title>
      
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
  overflow: hidden;
  user-select: none;
  color: white;
}
.bg-img{
  background: url('images/ctuser.png');
  height: 100vh;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
.bg-img:after{
  position: absolute;
  content: '';
  top: 0;
  left: 0;
  height: 100%;
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
  padding: 10px 32px;
  height: 430px;
  width: 450px;
  opacity: 0.7;
  transform: translate(-50%, -50%);
  background: rgba(255, 255, 255, 0.04);
  box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
}

.content:hover {
    opacity: 1;
      background-image: linear-gradient(316deg, #f94327 0%, #ff7d14 74%);
      box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
      rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
      rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
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
  border-radius: 10px;
}
.field span{
  color: black;
  width: 30px;
  line-height: 30px;
}
.field input{
  margin-left: 2px;
  height: 100%;
  width: 100%;
  background: transparent;
  border: none;
  outline: none;
  color: black;
  font-size: 16px;
  font-family: 'Poppins',sans-serif;
}
.space{
  margin-top: 5px;
}
.show{
  position: absolute;
  right: 13px;
  font-size: 15px;
  font-weight: 700;
  color: #222;
  display: none;
  cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
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
.submitbtn{
  padding: 10px;
  background: orange;
  border: 1px solid #2691d9;
  color: black;
  font-size: 16px;
  letter-spacing: 1px;
  font-weight: 600;
  cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
  font-family: 'Montserrat',sans-serif;
  border-radius: 10px;
}

.pull-left{
  color: white;
  margin: 20px 0;
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

header{
    border-bottom: 2px groove red; /* You can adjust the color and thickness */       
}

input[type="text"]:hover, input[type="email"]:hover, input[type="password"]:hover {
            background-color: #f7e791; /* Change the background color on hover */
            border: 2px solid #ffbe58; /* Add a border on hover */
        }

#client:hover{
  background-color: #f7e791; /* Change the background color on hover */
            border: 2px solid #ffbe58; 
}

#client{
  height: 40px;
  border: none;
  background: transparent;
  font-family: 'Poppins',sans-serif;
  font-size: 16px;
}
 #home{
    margin: 2vw 0 0 21vw; /* Adjusted margin for responsiveness */
    background-color: red;
    border-radius: 10px;
    font-weight: bolder;
}
#home span:hover{
    color:black;
}
#home:hover{
  background-color: #1b8b00;
    background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
    color: black;
    border-radius: 10px;
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, 
    rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, 
    rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
}
.nextbtn{
    border-radius: 10px;
    padding: 10px;
    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        color: white;
        cursor: url('https://img.icons8.com/ios-glyphs/28/drag-left.png') 14 14, auto;
        font-family: 'Montserrat',sans-serif;
    font-weight: bolder;
}

.nextbtn:hover{
    background-color: #1b8b00;
    background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
    color: black;
    border-radius: 10px;
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, 
    rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, 
    rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
}

#submitbtn:hover {
    background-color: #1b8b00;
    background-image: linear-gradient(314deg, #1b8b00 0%, #a2d240 74%);
    color: white;
    box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, 
    rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, 
    rgba(255, 255, 255, 0.08) 0px 1px 0px inset;
}
.file-upload {
            display: none;
        }

        #userType option{
            color: black;
        }
        #registrationStatus option{
            color: black;
        }

        .form-group label{
            font-family: 'Montserrat',sans-serif;
        }
    </style>
   
   
    </head>
   <body>
   <div style="text-align:center;margin-top:40px;">
      <div class="bg-img">
         <div class="content">
         <a href="login.php" style="text-decoration:none;">
            <header>CREATE ACCOUNT</header> </a>

                <div class="login-form">
                  
                    <form method="post" action="" id="registrationForm" onsubmit="return checkpass();">
                       <!-- Page 1 -->
<div id="page1">
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="firstname" placeholder="Your First Name..." required="true" class="form-control">
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="lastname" placeholder="Your Last Name..." required="true" class="form-control">
    </div>
    <div class="form-group">
        <label>Mobile Number</label>
        <input type="text" name="mobilenumber" maxlength="10" pattern="[0-9]{10}" placeholder="Mobile Number" required="true" class="form-control">
    </div>
    <button type="button" onclick="nextPage('page2')" class="nextbtn" id="nextBtnPage1">Next</button>
</div>

<!-- Page 2 -->
<div id="page2" style="display: none;">
    <div class="form-group">
        <label>User Type</label>
        <select name="userType" id="userType" class="form-control" required="true" onchange="updatePlace()">
            <option value="" disabled selected>Select user type</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="visitor">Visitor</option>
            <option value="staff">Staff</option>
        </select>
    </div>
    <div class="form-group">
        <label>Place</label>
        <input type="text" name="place" id="place" placeholder="Place" readonly class="form-control">
    </div>

    <div class="form-group">
        <label>License Number</label>
        <input type="text" name="LicenseNumber" maxlength="10" pattern="[0-9]*" placeholder="License Number" required class="form-control">
    </div>

   
    <button type="button" onclick="prevPage('page1')" class="nextbtn">Previous</button>
    <button type="button" onclick="nextPage('page3')" class="nextbtn" id="nextBtnPage2">Next</button>
</div>

    

<!-- Page 3 -->
<div id="page3" style="display: none;">
    <div class="form-group">
        <label>Email address</label>
        <input type="email" name="email" placeholder="Email address" required="true" class="form-control">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required="true" class="form-control">
    </div>

    <div class="form-group">
        <label>Repeat Password</label>
        <input type="password" name="repeatpassword" placeholder="Enter repeat password" required="true" class="form-control">
    </div>

    <div class="checkbox">
        <label class="pull-right">
            <a href="forgot-password.php" id="astyle">Forgot Password?</a>
        </label>
        <label class="pull-left">
            <a href="login.php"id="astyle">Sign in</a>
        </label>
    </div>
    <button type="button" onclick="prevPage('page2')" class="nextbtn">Previous</button>
    <button type="submit" name="submit" class="submitbtn btn-success btn-flat m-b-30 m-t-30" id="submitBtn">REGISTER</button>
                     </div>
                    </form>
                    <a href="../welcome.php" class="btn btn-primary" id="home">
                        <span class="glyphicon glyphicon-home"></span> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>rc="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="../admin/assets/js/main.js"></script>
    <script>
    let currentPage = 1;

    function updatePlace() {
        var userTypeSelect = document.getElementById('userType');
        var placeInput = document.getElementById('place');

        switch (userTypeSelect.value) {
            case 'faculty':
            case 'staff':
                placeInput.value = "Beside Kadasig Gym";
                break;
            case 'student':
                placeInput.value = "Beside the CME Building";
                break;
            case 'visitor':
                placeInput.value = "Front";
                break;
            default:
                placeInput.value = "";
                break;
        }
    }

   
    function nextPage(nextPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const nextForm = document.getElementById(nextPageId);

        if (currentPage === 1) {
            // Validation logic for Page 1 remains unchanged
        } else if (currentPage === 2) {
            // Validation logic for the newly created Page 2
        }

        currentForm.style.display = 'none';
        nextForm.style.display = 'block';
        currentPage++;
    }

    function prevPage(prevPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const prevForm = document.getElementById(prevPageId);

        currentForm.style.display = 'none';
        prevForm.style.display = 'block';
        currentPage--;
    }
</script>
</body>
</html>