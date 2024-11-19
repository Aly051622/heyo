<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is already logged in as an admin
if (isset($_SESSION['vpmsaid'])) {
    // Redirect to dashboard if already logged in
    header('location: dashboard.php');
    exit;
}

if (isset($_POST['login'])) {
    $adminuser = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    // Secure prepared statement
    $stmt = $con->prepare("SELECT ID, AdminName FROM tbladmin WHERE UserName = ? AND Password = ?");
    $stmt->bind_param("ss", $adminuser, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['vpmsaid'] = $admin['ID']; // Admin ID
        $_SESSION['admin_name'] = $admin['AdminName']; // Optional: Admin name
        header('location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $stmt->close();
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Admin Login | CTU DANAO Parking System</title>
        <link rel="apple-touch-icon" href="images/ctu.png">
        <link rel="shortcut icon" href="images/ctu.png">

        <!-- Include your CSS and other links here -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="bg-img">
            <div class="content">
                <header>A D M I N &nbsp; LOGIN</header>
                <form method="post">
                    <div class="field">
                        <span class="fa fa-user"></span>
                        <input class="form-control" type="text" placeholder="Username" required="true" name="username">
                    </div>
                    <div class="field space">
                        <span class="fa fa-lock"></span>
                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required="true">
                        <span class="show" id="show-password"><i class="fas fa-eye-slash"></i></span>
                    </div>
                    <div class="pass">
                        <a href="forgot-password.php">Forgot Password?</a>
                    </div>
                    <div class="field">
                        <input type="submit" name="login" value="LOGIN" id="loginbtn">
                    </div>
                    <div id="loading-spinner" class="fa fa-spinner fa-spin fa-3x"></div>
                    <a href="../welcome.php" class="btn btn-primary space" id="home">
                        <span class="glyphicon glyphicon-home"></span> Home
                    </a>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
        <script src="assets/js/main.js"></script>

        <script>
            const passField = document.querySelector('#password');
            const showBtn = document.querySelector('#show-password');

            showBtn.addEventListener('click', function() {
                if (passField.type === "password") {
                    passField.type = "text";
                    showBtn.innerHTML = '<i class="fas fa-eye"></i>';
                } else {
                    passField.type = "password";
                    showBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                }
            });
        </script>
    </body>
</html>
