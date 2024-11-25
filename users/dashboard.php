<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsuid'] == 0)) {
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['vpmsuid'];

$userQuery = mysqli_query($con, "SELECT u.*, up.validity AS upload_validity, up.expiration_date 
    FROM tblregusers u 
    LEFT JOIN uploads up ON u.email = up.email 
    WHERE u.ID='$uid'");

if (!$userQuery) {
    die("Error in query: " . mysqli_error($con));
}

$userData = mysqli_fetch_array($userQuery);
$currentDate = date('Y-m-d');
$expirationDate = $userData['expiration_date'] ?? null;
$expirationTimestamp = $expirationDate ? strtotime($expirationDate) : null;
$currentTimestamp = strtotime($currentDate);
$regValidityStatus = $userData['validity'] ?? 0;
$uploadValidityStatus = $userData['upload_validity'] ?? 0;

$licenseStatusMessage = "";

if ($regValidityStatus == 0 || $uploadValidityStatus == 0) {
    $licenseStatusMessage = "Your driver's license is expired. Please renew it.";
} elseif ($expirationTimestamp && $expirationTimestamp < $currentTimestamp && $expirationTimestamp >= strtotime("-3 months", $currentTimestamp)) {
    $licenseStatusMessage = "Your driver's license has expired. You have 3 months to renew it before your account is voided.";
}

$firstName = htmlspecialchars($userData['FirstName'] ?? 'User', ENT_QUOTES, 'UTF-8');
$lastName = htmlspecialchars($userData['LastName'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="en">

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
        body {
            font-family: 'Open Sans', sans-serif;
            overflow-x: hidden;
            margin-top: 1em;
        }
        .carousel-container {
            width: 100%;
            margin: 20px auto;
            overflow: hidden;
            position: relative;
        }
        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel img {
            width: 100%;
            height: auto;
        }
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #ffeb3b;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
            text-align: center;
        }
        .slider img {
            max-width: 100%;
            object-fit: cover;
        }
        .section {
            margin: 20px 0;
            padding: 10px;
            text-align: center;
        }
        .portrait-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .portrait-container {
            flex: 1 1 calc(25% - 20px);
            overflow: hidden;
            border-radius: 10px;
        }
        .portrait-container img {
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }
        .portrait-container:hover img {
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            .portrait-container {
                flex: 1 1 100%;
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

    <div class="content">
        <h2>Welcome, <?php echo $firstName . ' ' . $lastName; ?>!</h2>
    </div>

    <div class="carousel-container">
        <div class="carousel">
            <img src="images/tem.png" alt="Slide 1">
            <img src="images/temp.png" alt="Slide 2">
            <img src="images/tempo.png" alt="Slide 3">
        </div>
    </div>

    <div class="section">
        <h3>Overview</h3>
        <div class="slider">
            <img src="images/allArea.png" alt="Area Overview">
            <img src="images/areaA.png" alt="Area A">
        </div>
    </div>

    <div class="section portrait-section">
        <div class="portrait-container">
            <img src="images/admin1.png" alt="Admin Feature 1">
        </div>
        <div class="portrait-container">
            <img src="images/user1.png" alt="User Feature 1">
        </div>
    </div>

    <script>
        const carousel = document.querySelector('.carousel');
        let index = 0;
        setInterval(() => {
            index = (index + 1) % carousel.children.length;
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }, 5000);
    </script>

    <?php include_once('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
    </div>
</html>
