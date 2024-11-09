<?php session_start(); ?>
<html class="no-js" lang="">
<head>
    <script type="text/javascript" src="js/adapter.min.js"></script>
    <script type="text/javascript" src="js/vue.min.js"></script>
    <script type="text/javascript" src="js/instascan.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="guard.css">

    <title>QR Code Login Scanner | CTU DANAO Parking System</title>

    <style>
        body {
            font-weight: bold;
            height: 100vh;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

/* Media query for mobile devices */
@media (max-width: 480px) {
    body {
        font-weight: normal; /* Adjust font weight if needed for readability */
        height: auto; /* Let content define height on smaller screens */
    }
}


        .no-js {
            background-color: #f9fcff;
            background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
        }
        .container {
        padding: 20px;
        }

        /* Media query for mobile devices */
        @media (max-width: 480px) {
            .container {
                padding: 10px; /* Reduce padding for mobile view */
            }
        }

        .scanner-container, .table-container {
            margin-top: 20px;
            padding: 10px;
        }

        /* Media query for mobile devices */
        @media (max-width: 480px) {
            .scanner-container, .table-container {
                margin-top: 10px; /* Reduce the top margin for mobile */
                padding: 5px; /* Adjust padding for smaller screens */
                width: 100px; /* Make the containers full width */
                box-sizing: border-box; /* Ensure padding and border are included in the width */
                height: 100px;
            }
        }

        video {
            width: 500px; /* Reduced size */
            height: 300px; /* Square scanner */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto; /* Centered */
        }
        /* Media query for smaller screens */
        @media (max-width: 768px) {
            video {
                max-width: 400px; /* Reduce max width on tablets */
            }
        }

        @media (max-width: 480px) {
            video {
                max-width: 100%; /* Full width on small devices */
            }
        }

        table {
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            border-radius: 5px;
        }
        .scanner-label {
            font-weight: bold; 
            color: orange; 
            font-size: 20px; 
            text-align: center; 
            margin-top: 10px;
        }
        .alert {
            transition: opacity 0.5s ease;
        }
            /* Navbar Styles */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        width: 100%;
        height: 65px;
        padding: 8px;
        color: white;
        z-index: 1000;
        background-color: rgb(75, 75, 255);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }

    .navbar-brand a{
        font-size: 24px;
        font-weight: bold;
        text-decoration: none;
        color: white;
    }
    
/* Responsive styles */
@media (max-width: 768px) {
    .navbar {
        height: 50px; /* Allow height to adjust based on content */
    }

    .navbar .nav-item {
        margin: 5px 0; /* Add spacing between items */
    }
}

@media (max-width: 480px) {
    .navbar {
        height: 50px;
        padding: 5px; /* Reduce padding for smaller screens */
    }

    .navbar .nav-item {
        font-size: 14px; /* Reduce font size */
    }
}

        #title{
            margin-left: 50px;
        }
          /* Media Queries for Responsiveness */
          @media (max-width: 768px) {
            #title{
                margin-top: 25px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
                margin-left: 20px;
            }
        }
        @media (max-width: 480px) {
            #title{
                margin-left: 25px;
                top: 100px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
            }
        }
        /*qrbutton add css*/
        .dropbtn{
            color: white;
            padding: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            margin-right: 30px;
            font-family: Arial, sans-serif;
        }
        .dropbtn:hover{
            background-color: white;
            color: orange;
            border: solid orange;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }

        .dropdown-content{
            font-size: 12px;
            border-bottom-left-radius: 9px;
            border-bottom-right-radius: 9px;
            margin-top: 0px;
            width: 135px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        .dropdown-content a:hover {
            background-color: #f3ab19e0;
            color:white;
        }
        
 /* Media Queries for Responsiveness */
 @media (max-width: 768px) {
            .dropbtns {
                margin-top: 9px;
                display: inline;
                position: relative;
                z-index: 1006;
            }

            #drp-con1{
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: 0px;
                z-index:1007;
                margin-left: 20px;
                position: absolute;
            }
            #drp-con2 {
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: -1px;
                z-index:1007;
                margin-left: 195px;
                position: absolute;
            }
            #bt1{
                margin-bottom: 8px; 
                width: 40%;
                text-align: center;
                padding: 5px;
                margin-left: 20px;  
            }
            #bt2{
                margin-left: 195px;
                margin-top: -42px;
                width: 40%;
                padding: 5px;
                z-index: 1006;
            }
        }

    </style>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">

</head>
<body>
<!-- Responsive Navigation Bar -->
<nav class="navbar">
    <div class="navbar-brand"><a href="monitor.php"id="title">Parking Slot Manager</a></div>
        <!-- QR Scanner Dropdown -->
        <div class="dropdown">
            <button class="dropbtn" id="bt1"><i class="bi bi-qr-code-scan"></i> QR Scanner</button>
            <div class="dropdown-content" id="drp-con1">
                <a href="qrlogin.php"><i class="bi bi-car-front-fill"></i> Log-in</a>
                <a href="qrlogout.php"><i class="bi bi-car-front"></i> Log-out</a>
            </div>
        </div>

        <!-- Manual Input Dropdown -->
        <div class="dropdown">
            <button class="dropbtn" id="bt2"><i class="bi bi-journal-album"></i> Manual Input</button>
            <div class="dropdown-content" id="drp-con2">
                <a href="#"><i class="bi bi-car-front-fill"></i> Log-in</a>
                <a href="#"><i class="bi bi-car-front"></i> Log-out</a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="background: transparent;">
    <div class="row">
        <!-- Scanner Section -->
        <div class="col-md-12 scanner-container">
            <video id="preview"></video>
            <div class="scanner-label">SCAN QR CODE <i class="fas fa-qrcode"></i></div>

            <?php
            if (isset($_SESSION['error'])) {
                echo "
                <div class='alert alert-danger mt-2'>
                    <h4>Error!</h4>
                    " . $_SESSION['error'] . "
                </div>
                ";
            }

            if (isset($_SESSION['success'])) {
                echo "
                <div class='alert alert-primary mt-2 alert-dismissible fade show' role='alert' id='successAlert'>
                    <h4>Success!</h4>
                    Added Successfully
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                ";
            }
            ?>
        </div>

        <!-- Table Section -->
        <div class="col-md-12 table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Position/Status</td>
                        <td>Vehicle Type</td>
                        <td>Vehicle Plate Number</td>
                        <td>Parking Slot</td>
                        <td>TIMEIN</td>
                    </tr>
                </thead>
                <tbody>
                <?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "parkingz";

$conn = new mysqli($server, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrData'])) {
    // Assume the scanned QR code data is sent via POST request as 'qrData'
    $qrData = $_POST['qrData'];

    // Example QR code data format:
    // "Vehicle Type: SUV\nPlate Number: ABC1234\nName: John Doe\nUser Type: Regular"

    // Parse the QR code data
    $dataLines = explode("\n", $qrData);
    $vehicleType = str_replace('Vehicle Type: ', '', $dataLines[0]);
    $vehiclePlateNumber = str_replace('Plate Number: ', '', $dataLines[1]);
    $name = str_replace('Name: ', '', $dataLines[2]);
    $positionStatus = str_replace('User Type: ', '', $dataLines[3]); // Using "User Type" as position/status

    // Insert the parsed data into the tblqr_login
    $timeIn = date("Y-m-d H:i:s"); // Capture current time for TIMEIN field
    $sql = "INSERT INTO tblqr_login (Name, PositionStatus, VehicleType, VehiclePlateNumber, TIMEIN)
            VALUES ('$name', '$positionStatus', '$vehicleType', '$vehiclePlateNumber', '$timeIn')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('QR data successfully logged');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Query the current day records
$sql = "SELECT ID, Name, PositionStatus, VehicleType, VehiclePlateNumber, ParkingSlot, TIMEIN 
        FROM tblqr_login 
        WHERE DATE(TIMEIN) = CURDATE()";

$query = $conn->query($sql);

if (!$query) {
    die("Query failed: " . $conn->error);
}
?>

        <?php
        while ($row = $query->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['ID']; ?></td>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['PositionStatus']; ?></td>
                <td><?php echo $row['VehicleType']; ?></td>
                <td><?php echo $row['VehiclePlateNumber']; ?></td>
                <td><?php echo $row['ParkingSlot']; // Parking Slot is left blank ?></td>
                <td><?php echo $row['TIMEIN']; ?></td>
            </tr>
        <?php
        }
        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            alert('No cameras found');
        }
    }).catch(function (e) {
        console.error(e);
    });

    scanner.addListener('scan', function (content) {
        // Send the QR code content to the server via POST request
        fetch('qrlogin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'qrData=' + encodeURIComponent(content),
        })
        .then(response => response.text())
        .then(data => {
            // Reload the page to update the table with the new entry
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    });

    // Automatically hide success alert after 5 seconds
    setTimeout(function() {
        let successAlert = document.getElementById('successAlert');
        if (successAlert) {
            successAlert.style.opacity = '0';
            setTimeout(function() { successAlert.remove(); }, 500); // Fully remove after fade out
        }
    }, 1500);
</script>
</body>
</html>
