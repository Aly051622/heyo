<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
  } else{ 
    
    $uid = $_SESSION['vpmsuid'];

    // Fetch user information and validity status using email to join the uploads table
    $userQuery = mysqli_query($con, "SELECT u.*, up.validity AS upload_validity, up.expiration_date FROM tblregusers u LEFT JOIN uploads up ON u.email = up.email WHERE u.ID='$uid'");

    if (!$userQuery) {
        die("Error in query: " . mysqli_error($con)); // Add error handling for debugging
    }

    $userData = mysqli_fetch_array($userQuery);

    // Debug: Check if user data is fetched successfully
    if (!$userData) {
        die("Error fetching user data: " . mysqli_error($con));
    }

    // Get the expiration date
    $currentDate = date('Y-m-d');
    $expirationDate = $userData['expiration_date'];

    // Check if expiration date is valid
    $expirationTimestamp = $expirationDate ? strtotime($expirationDate) : null; // Check if expiration_date is not null
    $currentTimestamp = strtotime($currentDate);

    // Determine validity status
    $regValidityStatus = $userData['validity']; // Validity from tblregusers
    $uploadValidityStatus = $userData['upload_validity']; // Validity from uploads table
    $licenseStatusMessage = "";

    // Check if the license is expired and set the notification message
    if ($regValidityStatus == 0) {
        // Validity is 0 means the license is invalid
        $licenseStatusMessage = "Your driver's license is expired. Please renew it.";
    } elseif ($regValidityStatus == -2) {
        // User is unvalidated, do not show license messages
        $licenseStatusMessage = ""; // Explicitly set to empty for clarity
    } elseif ($uploadValidityStatus == 0) {
        // Check validity in uploads for invalidated clients
        $licenseStatusMessage = "Your driver's license is expired. Please renew it.";
    } elseif ($uploadValidityStatus == -2) {
        // Unvalidated users do not receive notifications
        $licenseStatusMessage = ""; // Explicitly set to empty for clarity
    } elseif ($expirationTimestamp && $expirationTimestamp < $currentTimestamp && $expirationTimestamp >= strtotime("-3 months", $currentTimestamp)) {
        // License expired but within 3 months grace period
        $licenseStatusMessage = "Your driver's license has expired. You have 3 months to renew it before your account is voided.";
    }

    // Sanitize user data for output
    $firstName = isset($userData['FirstName']) ? htmlspecialchars($userData['FirstName'], ENT_QUOTES, 'UTF-8') : 'User';
    $lastName = isset($userData['LastName']) ? htmlspecialchars($userData['LastName'], ENT_QUOTES, 'UTF-8') : '';

    ?>


<!doctype html>

 <html class="no-js" lang="">
<head>
    
    <title>Client Dashboard | CTU DANAO Parking System</title>
   
<link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
<link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
<link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
<link rel="stylesheet" href="../admin/assets/css/style.css">
<link rel="stylesheet" href="css/responsive.css">
<link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet" type="text/css">

    <style>
        body{
            overflow-x: auto;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
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
            max-width: 300px;
            height: auto;
            padding: 4px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            color: #333;
            z-index: 1005;
            position: absolute;
            border-radius: 9px;
            text-align: center;
            margin-top: -10px;
            color: green;
            font-weight: bold;
            position: absolute;
        }
            .content{
                background-color: transparent;
                margin-top: -30px;
            }
            #notificationCard {
                opacity: 1;
                transition: opacity 0.5s ease-in-out;
                padding: 5px;
                margin-left: 35em;
                max-width: 1000px;
                width: auto;
                height: auto;
                border: none;
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
        /* Responsive */
        @media (max-width: 768px) {
            .portrait-section {
                flex-wrap: wrap;
            }
            .portrait-container {
                flex-basis: 100%;
            }
        }
    </style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
    <?php include_once('includes/sidebar.php'); ?>
    <div class="right-panel">
    <?php if ($licenseStatusMessage): ?>
                            <div class="notification"><?php echo $licenseStatusMessage; ?></div>
                        <?php endif; ?>
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-lg-1">
                            <div class="card-body " id="notificationCard">
                                <h2>Welcome <?php echo $firstName; $lastName;?> <?php echo $lastName; ?> !</h2>
                        </div>
                    </div>
                </div>
                <!-- /Widgets -->
            </div>
            <!-- .animated -->
        </div>

        <!-- Notification card with disappearing effect -->
        <?php if ($regValidityStatus == 0): ?>
            <div  class="notification" style="margin-left: 25em; position: absolute;">
                <?php echo $licenseStatusMessage; ?>
            </div>
        <?php endif; ?>

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

<?php include_once('includes/slide.php'); ?>
    <div class="clearfix"></div>
<!-- Footer -->

<?php include_once('includes/footer.php'); ?>
        </div>
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


</script>

</body>
</html>
<?php } ?>