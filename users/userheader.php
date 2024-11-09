<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include('includes/dbconnection.php');

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

<style>
    #header {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                    rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                    rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
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
        border-radius: 50%; /* Circular avatar */
    }
</style>

<div id="right-panel" class="right-panel">
    <header id="header" class="header">
        <div class="top-left">
            <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
                <a class="navbar-brand" href="dashboard.php"><img src="images/clienthc.png" alt="Logo" style="width: 120px; height: auto;"></a>
            </div>
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
                        <a class="nav-link" href="profile.php"><i class="fa fa-user"></i> My Profile</a>
                        <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i> Change Password</a>
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>

                        <!-- Dropdown for profile picture upload -->
                        <form action="upload-profile.php" method="POST" enctype="multipart/form-data" style="padding: 10px;">
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
