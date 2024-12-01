<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include('../DBconnection/dbconnection.php');

// Check if the user ID is set in the session
if (!isset($_SESSION['vpmsuid'])) {
    echo '<p>Debug: User ID not found in session.</p>';
    exit; // Stop further execution
}

$userId = $_SESSION['vpmsuid'];

// Fetch the current profile picture from the database
$query = "SELECT profile_pictures FROM tblregusers WHERE ID = '$userId'";
$result = mysqli_query($con, $query);

if (!$result) {
    echo '<p>Debug: Query failed: ' . mysqli_error($con) . '</p>';
    $profilePicturePath = '../admin/images/images.png'; // Default avatar if query fails
} elseif (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profilePicture = $row['profile_pictures'];
    $profilePicturePath = '../uploads/profile_uploads/' . htmlspecialchars($profilePicture ?? '', ENT_QUOTES, 'UTF-8'); // Construct the path with null check

    // Debugging: Log profile picture path
    echo '<!-- Debug: Profile picture path: ' . $profilePicturePath . ' -->';
} else {
    $profilePicturePath = '../admin/images/images.png'; // Default avatar if no picture found
}

// Handle profile picture upload
if (isset($_POST['upload'])) {
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        // Ensure the uploads directory exists
        $uploadsDir = '../uploads/profile_uploads/'; // Your uploads directory
        $fileName = basename($_FILES['profilePic']['name']);
        $targetFilePath = $uploadsDir . $fileName;

        // Check if the uploads directory exists
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true); // Create the directory if it doesn't exist
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath)) {
            // Update the database with the new profile picture path
            $query = mysqli_query($con, "UPDATE tblregusers SET profile_pictures='$fileName' WHERE ID='$userId'");

            if ($query) {
                echo "<script>alert('Profile picture uploaded successfully.');</script>";
                // Update the profile picture path for display
                $profilePicturePath = $targetFilePath; // Update the path for display
            } else {
                echo "<script>alert('Database update failed.');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    } else {
        echo "<script>alert('File upload failed.');</script>";
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
    <div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle"></a>
        
                <div class="top-right">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            // Check if the profile picture exists and display it
                            if (!empty($profilePicture) && file_exists($profilePicturePath)) {
                                echo '<!-- Debug: Found profile picture at: ' . $profilePicturePath . ' -->';
                                echo '<img class="user-avatar rounded-circle" src="' . $profilePicturePath . '" alt="User Avatar">';
                            } else {
                                echo '<!-- Debug: No profile picture found or file does not exist. Attempted path: ' . $profilePicturePath . ' -->';
                                echo '<img class="user-avatar rounded-circle" src="../admin/images/images.png" alt="Default Avatar">';
                            }
                            ?>
                        </a>
                        <div class="user-menu dropdown-menu">
                                <!-- Dropdown for profile picture upload -->
                                <a class="nav-link" href="profile.php"><i class="fa bi-person-fill"></i> My Profile</a>
                                 <!-- <form action="upload-profile.php" method="POST" enctype="multipart/form-data" style="padding: 5px;">
                                    <label for="profilePic" class="nav-link">Upload Profile Picture:</label>
                                    <input type="file" name="profilePic" id="profilePic" accept="image/*" class="form-control nav-link">
                               <button type="submit" name="upload" class="btn btn-primary mt-2" class="nav-link">Upload</button>-->
                                    <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                                    <a class="nav-link" href="logout.php" onclick="return handleLogout();"><i class="fa fa-power-off"></i> Logout</a>

                                </form>   
                    </div>
                </div>
            <div id="logout-confirm-modal" class="modal">
                    <div class="modal-content">
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
                    window.location.href = "../welcome.php"; // Or any other logout URL
                }
            }
        </script>
