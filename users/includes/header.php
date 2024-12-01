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
    #header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                    rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                    rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .nav-link{
        width: 100px;
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
    .user-avatar {
        height: 35px;
        width: 35px; /* Maintain aspect ratio */
        border-radius:
         50%; /* Circular avatar */
    }
      /* logout message */
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
.modal-content {
    background: whitesmoke;
    margin: 15% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 300px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-content button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    color: black;
    cursor: pointer;
      color: white;
      font-size: 18px;
      letter-spacing: 1px;
      font-weight: 600;
      font-family: 'Montserrat',sans-serif;
      background: whitesmoke;
    border: 1px solid white;
}

.modal-content button:first-of-type {
    background-color:#2691d9;
    color: white;
}

.modal-content button:last-of-type {
    background-color: #2691d9;
    color: white;
}
.modal-content button:first-of-type:hover,
.modal-content button:last-of-type:hover
{
    background-color: darkblue;
    border: solid 1px blue;
}
</style>

    <header id="header" class="header">
    <div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle"></a>
        </div>
        <div class="top-right">
            <div class="header-menu">
                <div class="header-left"></div>
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
                        <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                        <form action="upload-profile.php" method="POST" enctype="multipart/form-data" style="padding: 5px;">
                            <label for="profilePic" class="nav-link">Upload Profile Picture:</label>
                            <input type="file" name="profilePic" id="profilePic" accept="image/*" class="form-control nav-link">
                            <!--<button type="submit" name="upload" class="btn btn-primary mt-2" class="nav-link">Upload</button>-->
                            <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                            <a class="nav-link" href="logout.php" onclick="return handleLogout();"><i class="fa fa-power-off"></i> Logout</a>

                        </form>
                      
                    </div>
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
    </header>