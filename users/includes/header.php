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
        margin-top: -60px;
        margin-right: 50px;
        position:fixed;
    }
    .dropdown{
        margin-top: -60px;
    }
    .dropdown-toggle {
        margin-top: 50px;
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
<header>
    <div class="navbar-header">
       <!-- <a  style="color: white; z-index: 1;"><i class="fa fa-bars"></i></a>-->
        <a ><img src="images/clientlogo.png"  id="menuToggle"></a>
        
        <div class="top-right">
                        <div class="header-menu">
                                <div class="header-left">  
                                    <div class="form-inline"></div>
                                </div>

                            <div class="user-area dropdown float-right">
                                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="user-avatar rounded-circle" src="../admin/images/images.png" alt="User Avatar" style="margin-top: -10px;">
                                </a>

                                <div class="user-menu dropdown-menu" id="hh">
                                    <a class="nav-link" href="profile.php"><i class="fa bi-person-fill" > My Profile
                                    </i></a>

                                     <!-- <form action="upload-profile.php" method="POST" enctype="multipart/form-data" style="padding: 5px;">
                                    <label for="profilePic" class="nav-link">Upload Profile Picture:</label>
                                    <input type="file" name="profilePic" id="profilePic" accept="image/*" class="form-control nav-link">
                               <button type="submit" name="upload" class="btn btn-primary mt-2" class="nav-link">Upload</button>-->

                                    <a class="nav-link" href="change-password.php"><i class="fa bi-gear-fill"> Change Password
                                    </i></a>

                                    <a class="nav-link" href="logout.php" onclick="return handleLogout();"><i class="fa bi-box-arrow-right"> Logout
                                    </i></a>
                                </div>
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
        </div>
</header>