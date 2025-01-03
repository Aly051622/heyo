<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsuid'] == 0)) {
    header('location:logout.php');
} else {
    $uid = $_SESSION['vpmsuid'];

    // Fetch user information and validity status from tblregusers
    $userQuery = mysqli_query($con, "SELECT * FROM tblregusers WHERE ID='$uid'");

    if (!$userQuery) {
        die("Error in query: " . mysqli_error($con)); // Error handling for debugging
    }

    $userData = mysqli_fetch_array($userQuery);

    if (!$userData) {
        die("Error fetching user data: " . mysqli_error($con)); // Additional error handling
    }

    // Get the current date
    $currentDate = date('Y-m-d');

    // Determine the validity status
    $regValidityStatus = $userData['validity']; // Validity from tblregusers
    $licenseStatusMessage = "";

    // Check validity and set the appropriate notification message
    if ($regValidityStatus == 0) {
        // Validity is 0 (license expired)
        $licenseStatusMessage = "Your driver's license is expired. Please renew it.";
    } elseif ($regValidityStatus == -2) {
        // Validity is -2 (unvalidated account)
        $licenseStatusMessage = "Your account is unvalidated. Please complete your registration.";
    } elseif ($regValidityStatus == 1) {
        // Validity is 1 (validated account) - No notification
        $licenseStatusMessage = ""; // Explicitly set to empty
    }

    // Sanitize user data for secure output
    $firstName = isset($userData['FirstName']) ? htmlspecialchars($userData['FirstName'], ENT_QUOTES, 'UTF-8') : 'User';
    $lastName = isset($userData['LastName']) ? htmlspecialchars($userData['LastName'], ENT_QUOTES, 'UTF-8') : '';

    ?>


<!doctype html>

 <html class="no-js" lang="">
<head>
    
    <title>Client Dashboard | CTU DANAO Parking System</title>
   
    <link rel="apple-touch-icon" href="../images/aa.png">
      <link rel="shortcut icon" href="../images/aa.png">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
        body, * {
            font-family: 'Open Sans', sans-serif !important; /* Ensure Open Sans is prioritized */
            margin: 0; /* Reset margin for consistency */
            padding: 0; /* Reset padding for consistency */
            box-sizing: border-box; /* Avoid layout issues */
        }
        html, body{
            background: whitesmoke;
            height: 100vh;
            overflow: auto;
            z-index: -1;
        }
        /* Carousel container and styling */
        .carousel-container {
            width: 75%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel img {
            width: 100%;
            height: 100%;
        }

        /* Progress bar styling */
        .progress-bar {
            width: 100%;
            height: 5px;
            background-color: orange;
            margin-top: 7px;
            position: relative;
        }

        /* Slide number indicator */
        .slide-number {
            position: absolute;
            top: -25px;
            right: 0;
            background-color: blue;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            opacity: 0.8;
        }

        .scrollable-images {
            margin-top: 20px;
            overflow-y: auto;
            align-items: center;
        }

        .scrollable-images img {
            border-radius: 20px;
            width: 99%;
            height: 500px;
            margin: 5px;
            border: 1px solid #ddd;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .section-divider {
            border-top: 10px groove; 
            margin: 20px 0; 
        }
        h4{
            text-align:center;
            font-weight: bold;
        }
        h2{
            text-align: center;
            text-shadow: #FC0 1px 0 10px;
            font-weight: bold;
        }
        p {
            font-size: 16px;         
            line-height: 1.6;        
            color: #333;              
            padding: 0;                
            text-align: justify;       
            font-family: Arial, sans-serif;  
            margin-left: 20px;
        }
            /* Card-specific styles */
        .notification {
            width: 300px;
            height: auto;
            padding: 4px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            color: #333;
            z-index: 1005;
            position: absolute;
            border-radius: 9px;
            text-align: center;
            margin-top: -10px;
            color: orange;
            font-weight: bold;
            position: absolute;
            padding: 10px;
        }
            .content{
                background-color: transparent;
                margin-top: -10px;
            }
            #notificationCard {
                opacity: 1;
                transition: opacity 0.5s ease-in-out;
                margin-left: 20em;
                width: 1000px;
                width: auto;
                height: auto;
                border: none;
                z-index: 1;
            }

                .section {
            margin: 20px 0;
            padding: 10px;
        }
        .title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: auto;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slider img {
            width: 100%;
            object-fit: cover;
            pointer-events: none;
        }
        /* Hover to pause */
        .slider-container:hover .slider {
            animation-play-state: paused;
        }
        /* Landscape Slides */
        .slider img.landscape {
            height: 225px;
            width: 100vw;
        }
        /* Portrait Sections (Sections 4-7) */
        .portrait-section {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        .portrait-container {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .portrait-container img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease-in-out;
        }

        .portrait-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
            cursor: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-collection-play-fill" viewBox="0 0 16 16"><path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6zm6.258-6.437a.5.5 0 0 1 .507.013l4 2.5a.5.5 0 0 1 0 .848l-4 2.5A.5.5 0 0 1 6 12V7a.5.5 0 0 1 .258-.437"/></svg>') 16 16, auto;
        }
     
        .portrait-container {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        .portrait-container:hover img {
            transform: scale(1.05);
        }
        .hover-yellow:hover { background-color: #FFFACD; }
        .hover-orange:hover { background-color: #FFDAB9; }
        .hover-skyblue:hover { background-color: #ADD8E6; }
        .hover-lightred:hover { background-color: #FFC0CB; }

        .space{
            margin-left: 20px;
            margin-top: 6em;
            width: 100%;
            margin-bottom:5px;
            font-size: 15px;
        }
        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            color: orange;
        }

        .close-btn:hover {
            color: red;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .portrait-section {
                flex-wrap: wrap;
            }
            .portrait-container {
                flex-basis: 100%;
            }
        }
        @media (max-width: 480){
                html, body{
                    height: 100vh;
                    background: whitesmoke;
                    overflow: hidden;
                }
        }
    </style>
</head>

<body>

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>
    <div class="right-panel">
    <div class="space">
    <?php if ($licenseStatusMessage): ?>
        <div class="notification">
            <?php echo $licenseStatusMessage; ?>
            <button class="close-btn" onclick="closeNotification()"><i class="bi bi-x-circle-fill"></i></button>
        </div>
    <?php endif; ?>
</div>

        <!-- Content -->
        <div class="content table-responsive">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-lg-1">
                    <div class="card-body" id="notificationCard" >
                    <h2 style=" text-shadow: 0px 4px 4px white;">Welcome! <?php echo $firstName; $lastName;?> <?php echo $lastName; ?></h2>
                    </div>

                    </div>
                </div>
                <!-- /Widgets -->
            </div>
            <!-- .animated -->
        </div>
        <?php if ($regValidityStatus == 0): ?>
            <div  class="notification" style="margin-left: 25em; position: absolute; display: none;">
                <?php echo $licenseStatusMessage; ?>
            </div>
        <?php endif; ?><br>

    <div class="carousel-container"style="margin-top: -70px;">
        <div class="carousel">
            <img src="images/tem.png" alt="Slide 1">
            <img src="images/temp.png" alt="Slide 2">
            <img src="images/tempo.png" alt="Slide 3">
            <img src="images/tempor.png" alt="Slide 4">
            <img src="images/tempora.png" alt="Slide 5">
        </div>
        <!-- Progress bar with slide number -->
        <div class="progress-bar">
            <div class="slide-number">1 / 5</div> 
            
        </div>
    </div>
 
    <div class="clearfix"></div>
<!-- Footer -->
<?php include_once('includes/footer.php'); ?>
      
<!-- /#right-panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/js/main.js"></script>

<script>
    
const carousel = document.querySelector('.carousel');
const carouselContainer = document.querySelector('.carousel-container');
const images = document.querySelectorAll('.carousel img');
const progressBar = document.querySelector('.progress-bar');
const slideNumber = document.querySelector('.slide-number');
const intervalTime = 5000; 
let index = 0;
let intervalId; 

function startSlideshow() {
    intervalId = setInterval(() => {
        index++;
        if (index >= images.length) {
            index = 0; 
        }

        carousel.style.transform = `translateX(-${index * 100}%)`;

        const progress = ((index + 1) / images.length) * 100;
        progressBar.style.width = `${progress}%`;

        slideNumber.textContent = `${index + 1} / ${images.length}`;
    }, intervalTime);
}

function pauseSlideshow() {
    clearInterval(intervalId);
}

startSlideshow();

carouselContainer.addEventListener('mouseenter', pauseSlideshow);
carouselContainer.addEventListener('mouseleave', startSlideshow);

// Hide and remove the notification card after 10 seconds
setTimeout(function() {
    var notificationCard = document.getElementById('notificationCard');
    if (notificationCard) {
        notificationCard.style.transition = 'opacity 0.5s'; // Add transition effect
        notificationCard.style.opacity = '0'; // Fade out the card

        // After the fade-out effect, remove the element from the DOM
        setTimeout(function() {
            notificationCard.remove(); // Remove the card element
        }, 500); // Wait for the fade-out effect before removing
    }
}, 10000); // 10 seconds in milliseconds

function closeNotification() {
    const notification = document.querySelector('.notification');
    if (notification) {
        notification.style.display = 'none';
    }
}

</script>

</body>
</html>
<?php } ?>