<?php
session_start();
include('../DBconnection/dbconnection.php');

// Login Logic
if (isset($_POST['login'])) {
    $emailOrMobile = $_POST['emailcont'];
    $password = $_POST['password'];

    // Check if input is email or mobile number
    $condition = filter_var($emailOrMobile, FILTER_VALIDATE_EMAIL) 
                 ? "Email='$emailOrMobile'" 
                 : "MobileNumber='$emailOrMobile'";

    // Query user data
    $query = mysqli_query($con, "SELECT ID, FirstName, LastName, MobileNumber, Password, profile_pictures FROM tblregusers WHERE $condition");
    $row = mysqli_fetch_assoc($query);

    if ($row && password_verify($password, $row['Password'])) {
        $_SESSION['vpmsuid'] = $row['ID'];
        $_SESSION['vpmsumn'] = $row['MobileNumber'];
        header('location:dashboard.php');
        exit();
    } else {
        echo "<script>alert('Invalid login details.');</script>";
    }
}

// Profile Picture Upload Logic
if (isset($_POST['upload'])) {
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $uploadsDir = '../uploads/profile_uploads/';
        $fileName = time() . '_' . basename($_FILES['profilePic']['name']); // Generate unique file name
        $targetFilePath = $uploadsDir . $fileName;

        // Create uploads directory if it doesn't exist
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        // Move uploaded file and update database
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
            $userId = $_SESSION['vpmsuid'];
            $query = mysqli_query($con, "UPDATE tblregusers SET profile_pictures='$fileName' WHERE ID='$userId'");

            if ($query) {
                echo "<script>alert('Profile picture uploaded successfully.');</script>";
                header('location:dashboard.php'); // Refresh to update image
                exit();
            } else {
                echo "<script>alert('Failed to update the database.');</script>";
            }
        } else {
            echo "<script>alert('Error occurred while uploading your file.');</script>";
        }
    } else {
        echo "<script>alert('No valid file selected.');</script>";
    }
}

// Fetch Profile Picture for Display
$userId = $_SESSION['vpmsuid'];
$query = mysqli_query($con, "SELECT profile_pictures FROM tblregusers WHERE ID='$userId'");
$row = mysqli_fetch_assoc($query);

// Set profile picture path
$profilePicPath = !empty($row['profile_pictures']) 
    ? '../uploads/profile_uploads/' . $row['profile_pictures'] 
    : '../admin/images/images.png'; // Default placeholder
?>

<!-- HTML Section -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file -->
    <title>Dashboard</title>
    <style>
        #header {
            background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .nav-link:hover {
            border-radius: 4px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .user-avatar {
            height: 35px;
            width: 35px;
        }
    </style>
</head>
<body>
    <div id="right-panel" class="right-panel">
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
                    <a class="navbar-brand" href="dashboard.php">
                        <img src="images/clienthc.png" alt="Logo" style="width: 120px; height: auto;">
                    </a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="<?php echo $profilePicPath; ?>" alt="User Avatar">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                            <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>

                            <!-- Profile Picture Upload Form -->
                            <form action="dashboard.php" method="POST" enctype="multipart/form-data" style="padding: 10px;">
                                <label for="profilePic">Upload Profile Picture:</label>
                                <input type="file" name="profilePic" id="profilePic" accept="image/*" class="form-control">
                                <button type="submit" name="upload" class="btn btn-primary mt-2">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</body>
</html>
