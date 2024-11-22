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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<style>
#header {
    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}
.nav-link:hover {
    background-image: transparent;
    border-radius: 4px;
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}
#hh {
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    font: 20px;
    font-weight: bold;
}
.profile-container {
    position: relative;
    display: inline-block;
}
.user-avatar {
    height: 40px;
    width: 60px;
    border-radius: 50%;
    object-fit: cover;
}
.user-avatar:hover{
    border: groove 1px orange;
}
.active-indicator {
    position: absolute;
    bottom: 3px;
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
.user-area img {
    margin-right: 5px;
}
</style>
<div id="right-panel" class="right-panel">
<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
            <a class="navbar-brand" href="dashboard.php"><img src="images/clientlogo.png" alt="Logo" style="width: 100px; height: auto;"></a>
            <a class="navbar-brand hidden" href="./"><img src="images/logo3.png" alt="Logo"></a>
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
    </div>
    <div class="top-right">
        <div class="header-menu">
            <div class="header-left">
                <div class="form-inline"></div>
            </div>
            <div class="user-area dropdown float-right">
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
                    <a class="nav-link" href="profile.php"><i class="fa fa-user"> My Profile</i></a>
                    <a class="nav-link" href="change-password.php"><i class="fa fa-cog"> Change Password</i></a>
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="profilePic" accept="image/*" required>
                        <button type="submit" name="upload" class="btn btn-primary mt-2">Upload</button>
                    </form>
                    <a class="nav-link" href="logout.php"><i class="fa fa-power-off"> Logout</i></a>
                </div>
            </div>
        </div>
    </div>
</header>
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
