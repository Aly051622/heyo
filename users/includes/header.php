<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

if (!isset($_SESSION['vpmsuid'])) {
    echo '<p>Debug: User ID not found in session.</p>';
    exit;
}

$userId = $_SESSION['vpmsuid'];

$query = "SELECT profile_pictures FROM tblregusers WHERE ID = '$userId'";
$result = mysqli_query($con, $query);

if (!$result) {
    $profilePicturePath = '../admin/images/images.png';
} else {
    $row = mysqli_fetch_assoc($result);
    $profilePicture = $row['profile_pictures'] ?? '';
    $profilePicturePath = (!empty($profilePicture) && file_exists('../uploads/profile_uploads/' . $profilePicture)) 
        ? '../uploads/profile_uploads/' . htmlspecialchars($profilePicture, ENT_QUOTES, 'UTF-8') 
        : '../admin/images/images.png';
}

$uploadSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $uploadsDir = '../uploads/profile_uploads/';
        $fileName = uniqid('profile_', true) . '.' . pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
        $targetFilePath = $uploadsDir . $fileName;

        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
            $updateQuery = "UPDATE tblregusers SET profile_pictures='$fileName' WHERE ID='$userId'";
            if (mysqli_query($con, $updateQuery)) {
                $uploadSuccess = true;
                $profilePicturePath = $targetFilePath;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
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
        height: 40px;
        width: 40px;
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
    }
    .dropdown {
        margin-top: -85px;
        margin-right: 40px;
    }
 
    .dropdown-toggle {
        margin-top: 50px;
    }

    .user-area img {
        margin-right: -2px;
        margin-top: 10px;
    }

    .menuToggle {
        margin-top: 5px;
        margin-left: 12em;
    }
    #hh {
        margin-top: 30px;
    }
</style>
<body>
    <div class="navbar-header">
        <a id="menuToggle" class="menutoggle" style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>
        <a href="dashboard.php"><img src="images/clientlogo.png" alt="Logo" style="width: 120px; height: auto; margin-top: -30px; margin-left: 20px;"></a>
        <div class="user-area dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="profile-container">
                    <?php
                    if (!empty($profilePicture) && file_exists($profilePicturePath)) {
                        echo '<img class="user-avatar" src="' . htmlspecialchars($profilePicturePath, ENT_QUOTES, 'UTF-8') . '" alt="User Avatar">';
                    } else {
                        echo '<img class="user-avatar" src="../admin/images/images.png" alt="Default Avatar">';
                    }
                    ?>
                    <span class="active-indicator"></span>
                </div>
            </a>
            <div class="user-menu dropdown-menu" id="hh">
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

    <?php if ($uploadSuccess): ?>
    <div class="modal fade" id="uploadSuccessModal" tabindex="-1" aria-labelledby="uploadSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSuccessModalLabel"><i class="bi bi-check-circle-fill"></i> Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalButton"></button>
                </div>
                <div class="modal-body">
                    Profile picture uploaded successfully.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="okButton">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var successModal = new bootstrap.Modal(document.getElementById('uploadSuccessModal'));
        successModal.show();

        document.getElementById('closeModalButton').addEventListener('click', function() {
            window.location.href = 'dashboard.php';
        });

        document.getElementById('okButton').addEventListener('click', function() {
            window.location.href = 'dashboard.php';
        });
    });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
