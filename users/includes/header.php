<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

// Ensure user is logged in
if (!isset($_SESSION['vpmsuid'])) {
    die('<p>Access Denied: User not logged in.</p>');
}

$userId = $_SESSION['vpmsuid'];

// Fetch the user's profile picture securely using prepared statements
$query = $con->prepare("SELECT profile_pictures FROM tblregusers WHERE ID = ?");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();

$profilePicturePath = '../admin/images/images.png'; // Default avatar
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profilePicture = $row['profile_pictures'] ?? '';
    $profilePicturePath = (!empty($profilePicture) && file_exists('../uploads/profile_uploads/' . $profilePicture))
        ? '../uploads/profile_uploads/' . htmlspecialchars($profilePicture, ENT_QUOTES, 'UTF-8')
        : $profilePicturePath;
}

$uploadSuccess = false;

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = '../uploads/profile_uploads/';
        $fileType = mime_content_type($_FILES['profilePic']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            $fileName = uniqid('profile_', true) . '.' . pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
            $targetFilePath = $uploadsDir . $fileName;

            // Create the uploads directory if it doesn't exist
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            // Move the uploaded file and update the database
            if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
                $updateQuery = $con->prepare("UPDATE tblregusers SET profile_pictures = ? WHERE ID = ?");
                $updateQuery->bind_param("si", $fileName, $userId);

                if ($updateQuery->execute()) {
                    $uploadSuccess = true;
                    $profilePicturePath = $targetFilePath; // Update the displayed picture path
                } else {
                    error_log("Database update failed: " . $con->error);
                }
            } else {
                error_log("File upload failed for: " . $targetFilePath);
            }
        } else {
            echo '<p class="alert alert-danger">Invalid file type. Only JPG, PNG, and GIF are allowed.</p>';
        }
    } else {
        echo '<p class="alert alert-danger">Error uploading the file. Please try again.</p>';
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
body, * {
    font-family: 'Open Sans', sans-serif !important; /* Ensure Open Sans is prioritized */
    margin: 0; /* Reset margin for consistency */
    padding: 0; /* Reset padding for consistency */
    box-sizing: border-box; /* Avoid layout issues */
}
    .navbar-header{
        position: fixed;
        width: 100vw;
        z-index: 1;
        height: 70px;
    }
    .btn:hover{
                background-color: darkblue;
                border: solid blue;
            }
    #printbtn:hover,
    #viewbtn:hover, .btn:hover {
        background: orange;
    }

    .navbar-header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 5px;
        width: 100vw;
        border-bottom: groove;
    }

    .profile-container {
        position: relative;
        display: inline-block;
    }

    .user-avatar {
        height: 55px;
        width: 55px;
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
        margin-top: 30px;
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
        margin-top: -60px;
        margin-right: 10px;
        position:fixed;
    }
    .dropdown-toggle {
        margin-top: 50px;
    }
    
    .user-avatar img {
        float: right;
        margin-top: 52px;
        z-index: 1;
    }

    .menuToggle {
        margin-top: 5px;
        margin-left: 12em;
    }
    #menuToggle{
        width: 120px; 
        height: auto;
        margin-top: -10px; 
        margin-left: 20px; 
        cursor: pointer; 
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        padding: 3px;
        border-radius: 7px;
    }
    #menuToggle:hover{
        box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 4px 0px inset;
    }
    #hh {
        margin-top: 30px;
    }

     
/* modal for logout */
.modal {
    display: none; 
    position: fixed;
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
.modal-contents {
    background: whitesmoke;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 300px;
    text-align: center;
    box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
}

.modal-contents button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    color: white;
    cursor: pointer;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      background: whitesmoke;
    border: 1px solid white;
}

.modal-contents button:first-of-type {
    background-color:#2691d9;
    color: white;
}

.modal-contents button:last-of-type {
    background-color: #2691d9;
    color: white;
}
.modal-contents button:first-of-type:hover,
.modal-contents button:last-of-type:hover
{
    background-color: darkblue;
    border: solid 1px blue;
}
.alert-message {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: red;
        color: white;
        font-weight: bold;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }

/* Responsive na ni */

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
@media (max-width: 1200px) {
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
@media (max-width: 992px) {
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
        margin-top: 10px;
    }
    .dropdown {
        margin-top: -60px;
        margin-right: 15px;
    }
}

/* 576px to 767px screens */
@media (max-width: 767px) {
    .navbar-header {
        padding: 10px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        margin-right: 5px;
        margin-top: 30em;
    }
    .menuToggle {
        margin-left: 5em;
        margin-top: 10px;
    }
    .dropdown {
        margin-top: -50px;
        margin-right: 10px;
    }
}

/* 480px to 575px screens */
@media (max-width: 575px) {
    .navbar-header {
        padding: 5px;
        width: 100%;
        height: 70px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 40em;
    }
    .dropdown {
        margin-top: 50px;
        margin-right: 20px;
    }
    .menuToggle {
        margin-left: 3em;
        margin-top: 10px;
    }
}
@media (max-width: 480px) {
    .navbar-header {
        padding: 5px;
        width: 100%;
        height: 70px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 30em;
    }
    .dropdown {
        margin-top: 50px;
        margin-right: 100px;
    }
    #menuToggle {
        margin-left: 3em;
        margin-top: 10px;
    }
}
</style>

<body>
    <div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle"></a>
        <div class="user-area dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="profile-container">
                    <img class="user-avatar" src="<?php echo htmlspecialchars($profilePicturePath, ENT_QUOTES, 'UTF-8') . '?v=' . time(); ?>" alt="User Avatar">
                    <span class="active-indicator"></span>
                </div>
            </a>
            <div class="user-menu dropdown-menu">
                <div class="hh">
                <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                <!--
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa fa-upload"></i> Upload Picture</a>
-->
                <a class="nav-link" href="logout.php" onclick="return handleLogout();"><i class="fa bi bi-box-arrow-right-"></i> Logout</a>
                </div>
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
                    <button type="submit" name="upload" class="btn btn-primary btn-sm">Upload</button>
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
    
    <div id="logout-confirm-modal" class="modal">
                    <div class="modal-contents">
                        <p>Are you sure you want to log out?</p>
                        <button onclick="confirmLogout(true)" class="btn-danger">Yes</button>
                        <button onclick="confirmLogout(false)" class="btn-warning">No</button>
                    </div>
                </div>
                <div class="alert-message" id="logout-alert" style="display: none;">
                <i class="bi bi-shield-fill-check"></i> You have successfully logged out.
                </div>
            </div>
    <script>

        function handleLogout() {
                // Show the modal for confirmation
                document.getElementById("logout-confirm-modal").style.display = "block";
                return false; // Prevent the default action temporarily
            }

            function confirmLogout(isConfirmed) {
                // Hide the modal
                document.getElementById("logout-confirm-modal").style.display = "none";

                if (isConfirmed) {
                    // Show the logout alert
                    var alertMessage = document.getElementById("logout-alert");
                    alertMessage.style.display = "block";

                    // Redirect or proceed with logout actions if necessary
                    window.location.href = "login.php"; 
                }
            }
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
