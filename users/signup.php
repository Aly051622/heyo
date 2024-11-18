<?php
session_start();
include('includes/dbconnection.php');// Ensure this path is correct

if (isset($_POST['submit'])) {
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $contno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
   
    
    // Use the correct variable name
    $stmt = mysqli_prepare($con, "SELECT Email FROM tblregusers WHERE Email=? OR MobileNumber=?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $contno);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<script>alert("This email or Contact Number is already associated with another account")</script>';
    } else {
        // Initialize $query
        $query = mysqli_prepare($con, "INSERT INTO tblregusers(FirstName, LastName, MobileNumber, Email, Password) VALUES (?, ?, ?, ?, ?)");
        
        if ($query) { // Check if the statement was prepared successfully
            mysqli_stmt_bind_param(
                $query,
                "ssssssss",
                $fname,
                $lname,
                $contno,
                $email,
                $password,
               
            );

            if (mysqli_stmt_execute($query)) {
                // Send verification code after successful registration
                $_SESSION['verification_email'] = $email; // Store email in session
                echo '<script>
                    alert("A verification code has been sent to your email.");
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

   
   
    </head>
    <body>
   <div style="text-align:center;margin-top:40px;">
      <div class="bg-img">
         <div class="content">
         <a href="login.php" id="x">
         <i class="fa-solid fa fa-xmark"></i></a>
         <a style="text-decoration:none;">
            <header>CREATE ACCOUNT</header> </a>

            <div class="login-form">
    <form method="post" action="" id="registrationForm" onsubmit="return checkpass();">
        <!-- Page 1 -->
        <div id="page1">
            <div class="form-group field space">
                <span class="fa bi bi-person-vcard-fill" style="font-size: 20px"></span>
                <input type="text" name="firstname" placeholder="Your First Name..." required="true" class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-person-vcard" style="font-size: 20px"></span>
                <input type="text" name="lastname" placeholder="Your Last Name..." required="true" class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-telephone-fill" style="font-size: 20px"></span>
                <input type="text" name="mobilenumber" maxlength="11" pattern="[0-9]{10}" placeholder="Mobile Number" required="true" class="form-control">
            </div><br>
            <button type="button" onclick="nextPage('page2')" class="nextbtn" id="nextBtnPage1">
                Next <i class="bi bi-caret-right-square-fill"></i>
            </button>
        </div>

        <!-- Page 2 -->
        <div id="page2" style="display: none;">
            <div class="form-group field space">
                <span class="fa bi bi-person-fill" style="font-size: 20px"></span>
                <input type="email" name="email" placeholder="Email address" required="true" class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-lock-fill" style="font-size: 20px"></span>
                <input type="password" name="password" placeholder="Enter password" required="true" class="form-control">
            </div>
            <div class="form-group field space">
                <span class="fa bi bi-shield-lock-fill" style="font-size: 20px"></span>
                <input type="password" name="repeatpassword" placeholder="Enter repeat password" required="true" class="form-control">
            </div>
            <div class="checkbox">
                <label class="pull-right">
                    <a href="forgot-password.php" id="astyle">Forgot Password?</a>
                </label>
                <label class="pull-left">
                    <a href="login.php" id="astyle">Sign in</a>
                </label><br>
            </div>
            <div>
                <input type="submit" name="submit" class="field submitbtn btn-success btn-flat m-b-30 m-t-30" id="submitBtn" value="REGISTER">
            </div><br>
            <button type="button" onclick="prevPage('page1')" class="nextbtn">
                <i class="bi bi-caret-left-square-fill"></i> Previous
            </button>
        </div>
    </form>
</div>

<script>
    let currentPage = 1;

    function nextPage(nextPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const nextForm = document.getElementById(nextPageId);

        if (nextForm) {
            currentForm.style.display = 'none';
            nextForm.style.display = 'block';
            currentPage++;
        }
    }

    function prevPage(prevPageId) {
        const currentForm = document.getElementById(`page${currentPage}`);
        const prevForm = document.getElementById(prevPageId);

        if (prevForm) {
            currentForm.style.display = 'none';
            prevForm.style.display = 'block';
            currentPage--;
        }
    }
</script>


</body>
</html>