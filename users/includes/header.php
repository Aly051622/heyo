<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

// Ensure user is logged in
if (!isset($_SESSION['vpmsuid'])) {
    echo '<p>Debug: User ID not found in session.</p>';
    exit;
}

$userId = $_SESSION['vpmsuid'];

// Fetch the user's profile picture
$query = "SELECT profile_pictures FROM tblregusers WHERE ID = '$userId'";
$result = mysqli_query($con, $query);

$profilePicturePath = '../admin/images/images.png'; // Default avatar
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profilePicture = $row['profile_pictures'] ?? '';
    $profilePicturePath = (!empty($profilePicture) && file_exists('../uploads/profile_uploads/' . $profilePicture)) 
        ? '../uploads/profile_uploads/' . htmlspecialchars($profilePicture, ENT_QUOTES, 'UTF-8') 
        : $profilePicturePath;
}

$uploadSuccess = false;

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
        $uploadsDir = '../uploads/profile_uploads/';
        $fileName = uniqid('profile_', true) . '.' . pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
        $targetFilePath = $uploadsDir . $fileName;

        // Create the uploads directory if it doesn't exist
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        // Move the uploaded file and update the database
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
            $updateQuery = "UPDATE tblregusers SET profile_pictures='$fileName' WHERE ID='$userId'";
            if (mysqli_query($con, $updateQuery)) {
                $uploadSuccess = true;
                $profilePicturePath = $targetFilePath; // Update the displayed picture path
            } else {
                error_log("Database update failed: " . mysqli_error($con));
            }
        } else {
            error_log("File upload failed for: " . $targetFilePath);
        }
    }
}
?>

<style>
    .navbar-header{
        position: fixed;
        width: 100vh;
        z-index: 1;
    }
    #printbtn:hover,
    #viewbtn:hover {
        background: orange;
    }

    .navbar-header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 13px;
        width: 100vw;
        border-bottom: groove;
    }

    .profile-container {
        position: relative;
        display: inline-block;
    }

    .user-avatar {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        object-fit: cover;
        text-shadow: 0px 4px 4px gray;
        border: groove 2px white;
        z-index: 5;
    }

    .user-avatar:hover {
        border: groove 1px orange;
    }

    .active-indicator {
        position: absolute;
        bottom: -3px;
        right: -3px;
        background-color: #28a745;
        color: white;
        border: 2px solid white;
        font-size: 11px;
        border-radius: 50%;
        width: 12px;
        height: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .user-area {
        display: flex;
        align-items: center;
        margin-top: -80px;
        margin-right: 10px;
        position:fixed;
    }
    .dropdown-toggle {
        margin-top: 50px;
    }

    .user-area img {
        margin-right: -2px;
        margin-top: 50px;
        z-index: 1;
    }

    .menuToggle {
        margin-top: 5px;
        margin-left: 12em;
    }
    #hh {
        margin-top: 30px;
    }


/* Responsive Styles with Media Queries */

/* 1600px and larger screens */
@media (min-width: 1600px) {
    .navbar-header {
        padding: 20px;
    }
    .user-avatar {
        height: 50px;
        width: 50px;
    }
    .user-area {
        margin-right: 20px;
    }
}

/* 1200px to 1599px screens */
@media (min-width: 1200px) and (max-width: 1599px) {
    .navbar-header {
        padding: 18px;
    }
    .user-avatar {
        height: 45px;
        width: 45px;
    }
    .user-area {
        margin-right: 15px;
    }
}

/* 992px to 1199px screens */
@media (min-width: 992px) and (max-width: 1199px) {
    .navbar-header {
        padding: 15px;
    }
    .user-avatar {
        height: 40px;
        width: 40px;
    }
    .menuToggle {
        margin-left: 10em;
    }
    .dropdown {
        margin-top: -70px;
        margin-right: 25px;
    }
}

/* 768px to 991px screens */
@media (min-width: 768px) and (max-width: 991px) {
    .navbar-header {
        padding: 12px;
    }
    .user-avatar {
        height: 35px;
        width: 35px;
    }
    .user-area {
        margin-right: 10px;
    }
    .menuToggle {
        margin-left: 8em;
    }
    .dropdown {
        margin-top: -60px;
        margin-right: 15px;
    }
}

/* 576px to 767px screens */
@media (min-width: 576px) and (max-width: 767px) {
    .navbar-header {
        padding: 10px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        margin-right: 5px;
    }
    .menuToggle {
        margin-left: 5em;
    }
    .dropdown {
        margin-top: -50px;
        margin-right: 10px;
    }
}

/* 480px to 575px screens */
@media (max-width: 575px) {
    .navbar-header {
        padding: 8px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
    }
    .dropdown {
        margin-top: -40px;
        margin-right: 5px;
    }
    .menuToggle {
        margin-left: 3em;
    }
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    /* Add relevant styles here */
</style>
<body>
    <div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle" style="width: 120px; height: auto; margin-top: -10px; margin-left: 20px; cursor: pointer; text-shadow: 0px 4px 4px gray"></a>
        <div class="user-area ">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="profile-container">
                    <img class="user-avatar" src="<?php echo htmlspecialchars($profilePicturePath, ENT_QUOTES, 'UTF-8') . '?v=' . time(); ?>" alt="User Avatar">
                    <span class="active-indicator"></span>
                </div>
            </a>
            <div class="user-menu dropdown-menu">
                <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa fa-upload"></i> Upload Picture</a>
                <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="profilePic" accept="image/*" required>
                        <button type="submit" name="upload" class="btn btn-primary mt-2">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <?php if ($uploadSuccess): ?>
    <div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-labelledby="uploadSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSuccessModalLabel"><i class="bi bi-check-circle-fill"></i> Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Profile picture uploaded successfully.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.reload();">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var successModal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
        successModal.show();
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
