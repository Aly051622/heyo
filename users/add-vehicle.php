<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Manila');

include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsuid']==0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $catename = $_POST['catename'];
        $vehcomp = $_POST['vehcomp'];
        $model = $_POST['model'];
        $color = $_POST['color'];
        $vehreno = $_POST['vehreno'];
        $ownername = $_POST['ownername'];
        $ownercontno = $_POST['ownercontno'];

        if ($_POST['model'] === "Others, please specify") {
            $model = $_POST['otherModel'];
        }

        $imagePath = '';
        if ($vehcomp === 'Chevrolet') {
            if ($model === 'Tracker') {
                $imagePath = '../admin/vehicles/chevrolet/tracker.png';
            } elseif ($model === 'Trailblazer') {
                $imagePath = '../admin/vehicles/chevrolet/trailblazer.png';
            } elseif ($model === 'Suburban') {
                $imagePath = '../admin/vehicles/chevrolet/suburban.jpg';
            } elseif ($model === 'Corvette') {
                $imagePath = '../admin/vehicles/chevrolet/corvette.png';
            } elseif ($model === 'Tahoe') {
                $imagePath = '../admin/vehicles/chevrolet/tahoe.jpg';
            } elseif ($model === 'Trax') {
                $imagePath = '../admin/vehicles/chevrolet/trax.png';
            } elseif ($model === 'Captiva') {
                $imagePath = '../admin/vehicles/chevrolet/captiva.png';
            } elseif ($model === 'Camaro') {
                $imagePath = '../admin/vehicles/chevrolet/camaro.png';
            } elseif ($model === 'Others, please specify') {
                $imagePath = '../admin/vehicles/chevrolet/camaro.png';
            }
        } elseif ($vehcomp === 'Honda Motors') {
            if ($model === 'DIO') {
                $imagePath = '../admin/vehicles/honda motors/honda dio.png';
            } elseif ($model === 'Click 125') {
                $imagePath = '../admin/vehicles/honda motors/click125.png';
            } elseif ($model === 'Click 125 (Special Edition)') {
                $imagePath = '../admin/vehicles/honda motors/click125 sepecial.png';
            } elseif ($model === 'Honda Click 150i') {
                $imagePath = '../admin/vehicles/honda motors/click150.png';
            } elseif ($model === 'Wave RSX (DISC)') {
                $imagePath = '../admin/vehicles/honda motors/waversx (disc).png';
            } elseif ($model === 'ADV160') {
                $imagePath = '../admin/vehicles/honda motors/adv160.png';
            } elseif ($model === 'Beat (Playful)') {
                $imagePath = '../admin/vehicles/honda motors/beatplayful.png';
            } elseif ($model === 'Beat (Premium)') {
                $imagePath = '../admin/vehicles/honda motors/beatpremium.png';
            } elseif ($model === 'CBR150R') {
                $imagePath = '../admin/vehicles/honda motors/cbr150r.png';
            } elseif ($model === 'PCX160 - CBS') {
                $imagePath = '../admin/vehicles/honda motors/pcx160 cbs.png';
            } elseif ($model === 'Winner X (Standard)') {
                $imagePath = '../admin/vehicles/honda motors/winnerx (standard).png';
            } elseif ($model === 'XRM125 DS') {
                $imagePath = '../admin/vehicles/honda motors/xrm125 ds.png';
            } elseif ($model === 'CRF150L') {
                $imagePath = '../admin/vehicles/honda motors/cr150l.png';
            } elseif ($model === 'TMX125 Alpha') {
                $imagePath = '../admin/vehicles/honda motors/tmx125 alpha.png';
            } elseif ($model === 'AirBlade160') {
                $imagePath = '../admin/vehicles/honda motors/airblade160.png';
            } elseif ($model === 'Winner X (ABS Premium)') {
                $imagePath = '../admin/vehicles/honda motors/winnerx (abs premium).png';
            } elseif ($model === 'Wave RSX (Drum)') {
                $imagePath = '../admin/vehicles/honda motors/waversx (drum).png';
            } elseif ($model === 'XRM125 MOTARD') {
                $imagePath = '../admin/vehicles/honda motors/xrm125 motard.png';
            } elseif ($model === 'RS125') {
                $imagePath = '../admin/vehicles/honda motors/rs125.png';
            } elseif ($model === 'XR150L') {
                $imagePath = '../admin/vehicles/honda motors/xr150l.png';
            } elseif ($model === 'CRF300L') {
                $imagePath = '../admin/vehicles/honda motors/crf300l.png';
            } elseif ($model === 'NX500') {
                $imagePath = '../admin/vehicles/honda motors/nx500.png';
            } elseif ($model === 'EM1 e') {
                $imagePath = '../admin/vehicles/honda motors/em1e.png';
            } elseif ($model === 'CRF1100L Africa Twin') {
                $imagePath = '../admin/vehicles/honda motors/crf1100 africatwin.png';
            } elseif ($model === 'Others, please specify') {
                $imagePath = '../admin/vehicles/honda motors/click125.png';
                }
            } elseif ($vehcomp === 'Benelli') {
                if ($model === 'Benelli Leoncino 500') {
                    $imagePath = '../admin/vehicles/benelli/leoncino500.png';
                } elseif ($model === 'Benelli TNT135') {
                    $imagePath = '../admin/vehicles/benelli/tnt135.png';
                } elseif ($model === 'Benelli TNT302s') {
                    $imagePath = '../admin/vehicles/benelli/tnt302s.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/benelli/leoncino500.png';
            }
            } elseif ($vehcomp === 'CFMoto') {
                if ($model === 'CFMoto 300SR') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
                } elseif ($model === 'CFMoto 400NK') {
                    $imagePath = '../admin/vehicles/cfmoto/400nk.png';
                } elseif ($model === 'CFMoto 650NK') {
                    $imagePath = '../admin/vehicles/cfmoto/650nk.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
            }
            } elseif ($vehcomp === 'Changhe') {
                if ($model === 'CFMoto 300SR') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
                } elseif ($model === 'CFMoto 400NK') {
                    $imagePath = '../admin/vehicles/cfmoto/400nk.png';
                } elseif ($model === 'CFMoto 650NK') {
                    $imagePath = '../admin/vehicles/cfmoto/650nk.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
            }
            } elseif ($vehcomp === 'Changan') {
                if ($model === 'CFMoto 300SR') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
                } elseif ($model === 'CFMoto 400NK') {
                    $imagePath = '../admin/vehicles/cfmoto/400nk.png';
                } elseif ($model === 'CFMoto 650NK') {
                    $imagePath = '../admin/vehicles/cfmoto/650nk.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
            }
            } elseif ($vehcomp === 'Chery') {
                if ($model === 'CFMoto 300SR') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
                } elseif ($model === 'CFMoto 400NK') {
                    $imagePath = '../admin/vehicles/cfmoto/400nk.png';
                } elseif ($model === 'CFMoto 650NK') {
                    $imagePath = '../admin/vehicles/cfmoto/650nk.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
            }
            } elseif ($vehcomp === 'Dongfeng') {
                if ($model === 'CFMoto 300SR') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
                } elseif ($model === 'CFMoto 400NK') {
                    $imagePath = '../admin/vehicles/cfmoto/400nk.png';
                } elseif ($model === 'CFMoto 650NK') {
                    $imagePath = '../admin/vehicles/cfmoto/650nk.png';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/cfmoto/300sr.png';
            }
            } elseif ($vehcomp === 'Kawasaki') {
                if ($model === 'Kawasaki Barako II') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki Barako II.jpg';
                } elseif ($model === 'Kawasaki CT100') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki CT100.jpg';
                } elseif ($model === 'Kawasaki Dominar 400') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki Dominar 400.jpg';
                } elseif ($model === 'Kawasaki Ninja 400') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki Ninja 400.jpg';
                } elseif ($model === 'Kawasaki Ninja ZX-25R') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki Ninja ZX-25R.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/kawasaki/Kawasaki Barako II.png';
            }
            } elseif ($vehcomp === 'Kymco') {
                if ($model === 'Kymco Super 8') {
                    $imagePath = '../admin/vehicles/kymco/Kymco Super 8.jpg';
                } elseif ($model === 'Kymco Xciting 300i') {
                    $imagePath = '../admin/vehicles/kymco/Kymco Xciting 300i.jpg';
                } elseif ($model === 'Kymco AK550') {
                    $imagePath = '../admin/vehicles/kymco/Kymco AK550.jpg';
                } elseif ($model === 'Kymco Like 150i') {
                    $imagePath = '../admin/vehicles/kymco/Kymco Like 150i.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/kymco/Kymco Like 150i.jpg';
            }
            } elseif ($vehcomp === 'Piaggio') {
                if ($model === 'MotorStar MSX200-II') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar MSX200-II.jpg';
                } elseif ($model === 'MotorStar Xplorer X200R') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar Xplorer X200R.jpg';
                } elseif ($model === 'MotorStar Nicess 110') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar Nicess 110.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar MSX200-II.jpg';
            }
            } elseif ($vehcomp === 'Racal') {
                if ($model === 'MotorStar MSX200-II') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar MSX200-II.jpg';
                } elseif ($model === 'MotorStar Xplorer X200R') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar Xplorer X200R.jpg';
                } elseif ($model === 'MotorStar Nicess 110') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar Nicess 110.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/motorstar/MotorStar MSX200-II.jpg';
            }
            } elseif ($vehcomp === 'Rusi') {
                if ($model === 'Rusi Classic 250') {
                    $imagePath = '../admin/vehicles/rusi/Rusi Classic 250.jpg';
                } elseif ($model === 'Rusi Flash 150') {
                    $imagePath = '../admin/vehicles/rusi/Rusi Flash 150.jpg';
                } elseif ($model === 'Rusi Mojo 200') {
                    $imagePath = '../admin/vehicles/rusi/Rusi Mojo 200.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/rusi/Rusi Classic 250.jpg';
            }
            } elseif ($vehcomp === 'Suzuki Motors') {
                if ($model === 'Suzuki Raider R150') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Raider R150.jpg';
                } elseif ($model === 'Suzuki Skydrive') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Skydrive.jpg';
                } elseif ($model === 'Suzuki Burgman Street') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Burgman Street.jpg';
                } elseif ($model === 'Suzuki Smash 115') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Smash 115.jpg';
                } elseif ($model === 'Suzuki GSX-R150') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki GSX-R150.jpg';
                } elseif ($model === 'Suzuki Gixxer') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Gixxer.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/suzuki motors/Suzuki Raider R150.jpg';
                }
            } elseif ($vehcomp === 'SYM') {
                if ($model === 'SYM Maxsym 400i') {
                    $imagePath = '../admin/vehicles/sym/SYM Maxsym 400i.jpg';
                } elseif ($model === 'SYM Bonus X') {
                    $imagePath = '../admin/vehicles/sym/SYM Bonus X.jpg';
                } elseif ($model === 'SYM RV1-2') {
                    $imagePath = '../admin/vehicles/sym/SYM RV1-2.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/sym/SYM Maxsym 400i.jpg';
                }
            } elseif ($vehcomp === 'TVS') {
                if ($model === 'TVS Apache RTR 200", ') {
                    $imagePath = '../admin/vehicles/tvs/TVS Apache RTR 200.jpg';
                } elseif ($model === 'TVS Apache RTR 160') {
                    $imagePath = '../admin/vehicles/tvs/TVS Apache RTR 160.jpg';
                } elseif ($model === 'TVS Dazz') {
                    $imagePath = '../admin/vehicles/tvs/TVS Dazz.jpg';
                } elseif ($model === 'TVS XL100') {
                    $imagePath = '../admin/vehicles/tvs/TVS XL100.jpg';
                } elseif ($model === 'Others, please specify') {
                    $imagePath = '../admin/vehicles/tvs/TVS Apache RTR 200.jpg';
                }
            
}

        $checkPlateQuery = mysqli_query($con, "SELECT * FROM tblvehicle WHERE RegistrationNumber='$vehreno'");
        $plateExists = mysqli_num_rows($checkPlateQuery);

        if ($plateExists > 0) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { 
                    showModal('Plate Number already exists'); 
                });</script>";
        } else {
           // Query the database to fetch user details using the contact number
// Your existing contact number check query
$checkContactQuery = mysqli_query($con, "SELECT * FROM tblregusers WHERE MobileNumber='$ownercontno'");
$userExists = mysqli_num_rows($checkContactQuery);

if ($userExists > 0) {
    // Fetch the user data
    $userData = mysqli_fetch_assoc($checkContactQuery);
    $firstName = $userData['FirstName'];
    $lastName = $userData['LastName'];
    $fullName = "$firstName $lastName"; // Full name

    // Prepare QR code content with full name and other vehicle details
    $qrCodeData = "Vehicle Type: $catename\nPlate Number: $vehreno\nName: $fullName\nContact Number: $ownercontno\nModel: $model";
    // Base64 encode the QR code data (optional)
    $encodedData = base64_encode($qrCodeData); // Encode the QR data
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qrCodeData) . "&size=150x150";

    // Generate the QR code image
    $qrImageName = "qr" . $vehreno . "_" . time() . ".png";
    $qrImagePath = "../admin/qrcodes/" . $qrImageName;
    $qrCodeContent = file_get_contents($qrCodeUrl);
    file_put_contents($qrImagePath, $qrCodeContent);

   // Create a new image with the name and QR code
$outputImagePath = "../admin/qrcodes/My_QR" . $lastName . ".png"; // Define the final image path
$qrImage = imagecreatefrompng($qrImagePath); // Load the QR code image

$qrWidth = imagesx($qrImage); // Get the width of the QR code image
$qrHeight = imagesy($qrImage); // Get the height of the QR code image

// Create an image canvas (increased height to accommodate text)
$outputImage = imagecreatetruecolor($qrWidth, $qrHeight + 50); // Increase the height for text
$white = imagecolorallocate($outputImage, 255, 255, 255); // Allocate the white color
$black = imagecolorallocate($outputImage, 0, 0, 0); // Allocate the black color
imagefilledrectangle($outputImage, 0, 0, $qrWidth, $qrHeight + 50, $white); // Fill the background with white

// Add the full name text above the QR code
$fontPath = '../fonts/VintageMintageFreeDemo-LVPK4.otf'; // Path to your font file
$fontSize = 10; // Adjust font size for better visibility

// Split the full name into lines of maximum 20 characters
$maxLength = 20;
$lines = [];
while (strlen($fullName) > $maxLength) {
    $line = substr($fullName, 0, $maxLength);
    $fullName = substr($fullName, $maxLength);
    $lines[] = $line;
}
$lines[] = $fullName; // Add any remaining part

// Add text with padding and ensure text is center-aligned
$padding = 2; 
$textSpacing = 5; // Space between text and QR code
$yPosition = $padding + 15; // Initial vertical position for the text
foreach ($lines as $line) {
    // Calculate text bounding box to center-align
    $textBox = imagettfbbox($fontSize, 0, $fontPath, $line);
    $textWidth = abs($textBox[4] - $textBox[0]);
    $xPosition = (int)(($qrWidth - $textWidth) / 2); // Center-align horizontally, cast to int

    // Render text on the image
    imagettftext($outputImage, $fontSize, 0, $xPosition, (int)$yPosition, $black, $fontPath, $line); 

    // Increment the Y position for the next line
    $yPosition += (int)($fontSize + 2); // Cast to int
}

// Add space between text and QR code
$yPosition += $textSpacing;
imagecopy($outputImage, $qrImage, ($qrWidth - $qrWidth) / 2, $yPosition, 0, 0, $qrWidth, $qrHeight); 

// Save the final image with QR code and full name
imagepng($outputImage, $outputImagePath); 
imagedestroy($qrImage);
imagedestroy($outputImage);
$inTime = date('Y-m-d H:i:s');

// Sanitize input values to avoid SQL injection (for security)
$catename = mysqli_real_escape_string($con, $catename);
$vehcomp = mysqli_real_escape_string($con, $vehcomp);
$model = mysqli_real_escape_string($con, $model);
$color = mysqli_real_escape_string($con, $color);
$vehreno = mysqli_real_escape_string($con, $vehreno);
$ownername = mysqli_real_escape_string($con, $ownername);
$ownercontno = mysqli_real_escape_string($con, $ownercontno);
$outputImagePath = mysqli_real_escape_string($con, $outputImagePath);
$imagePath = mysqli_real_escape_string($con, $imagePath);

// Insert query to store vehicle details along with the generated QR code image path
$query = "INSERT INTO tblvehicle (VehicleCategory, VehicleCompanyname, Model, Color, RegistrationNumber, OwnerName, OwnerContactNumber, QRCodePath, ImagePath, InTime) 
          VALUES ('$catename', '$vehcomp', '$model', '$color', '$vehreno', '$ownername', '$ownercontno', '$outputImagePath', '$imagePath', '$inTime')";

if (mysqli_query($con, $query)) {
    echo "<script>alert('Vehicle Entry Detail has been added');</script>";
    echo "<script>window.location.href ='view-vehicle.php'</script>";
} else {
    echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
}

} else {
    echo "<script>alert('Contact number not found in the user database. Please ensure the contact number is registered.');</script>";
}



        }
    }
?>





<!doctype html>
<html class="no-js" lang="">
<head>
    
    <title>CTU- Danao Parking Management System- Add Vehicle</title>
   

    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
         /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: whitesmoke; 
            padding-top: 60px; 
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #otherModelInput {
            display: none;
        }
    </style>
    
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    
    <script>
let modal;

function showModal(message) {
    modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `<div class="modal-content"><span class="close" onclick="closeModal()">&times;</span><p>${message}</p></div>`;
    document.body.appendChild(modal);
    modal.style.display = "block";
    window.onclick = e => { if (e.target === modal) closeModal(); };
}

function closeModal() {
    if (modal) {
        modal.style.display = "none";
        document.body.removeChild(modal);
        modal = null;
    }
}

function updateMakeBrandOptions() {
    const catename = document.getElementById("catename").value.trim();
    const vehcomp = document.getElementById("vehcomp");
    const otherMakeInput = document.getElementById("otherMake");
    vehcomp.innerHTML = '<option value="">Select Make/Brand</option>';
    const options = {
        "Two Wheeler Vehicle": ["Benelli", "CFMoto", "Honda Motors", "Kawasaki", "Kymco", "MotorStar", "Piaggio", "Racal", "Rusi", "Suzuki Motors", "SYM", "TVS", "Yamaha", "Others, please specify"],
        "Four Wheeler Vehicle": ["Changhe", "Changan", "Chery", "Chevrolet", "Dongfeng", "Ford", "Foton", "GAC", "Geely", "Honda", "Hyundai", "Isuzu", "Kia", "Lexus", "Mazda", "MG (Morris Garages)", "Mitsubishi", "Nissan", "Peugeot", "Subaru", "Suzuki", "Toyota", "Volkswagen", "Others, please specify"],
        "Bicycles": ["Battle", "Brusko", "Cannondale", "GT", "Hiland", "Kona", "Nakto", "RoyalBaby", "Others, please specify"]
    };

    if (options[catename]) {
        options[catename].forEach(make => {
            const option = document.createElement("option");
            option.value = make;
            option.text = make;
            vehcomp.appendChild(option);
        });
    }

    vehcomp.addEventListener("change", () => {
        otherMakeInput.style.display = vehcomp.value === "Others, please specify" ? "block" : "none";
    });
}

function updateModelOptions() {
    const vehcomp = document.getElementById("vehcomp").value;
    const model = document.getElementById("model");
    model.innerHTML = '<option value="">Select Model</option>';
    const models = {
        "Benelli": ["Benelli Leoncino 500", "Benelli TNT135", "Benelli TNT302s", "Others, please specify"],
        "CFMoto": ["CFMoto 300SR", "CFMoto 400NK", "CFMoto 650NK", "Others, please specify"],
        "Changhe": ["Changhe A6", "Changhe Journey MPV M60", "Others, please specify"], //WALA PANI
        "Changan": ["Changan CS15", "Changan Alsvin", "Changan CS35 Plus", "Changan Uni-T", "Others, please specify"], //WALA PANI
        "Chery": ["Chery Tiggo 2 Pro", "Chery Tiggo 5X Pro", "Chery Tiggo 7 Pro", "Chery Tiggo 8 Pro", "Others, please specify"], //WALA PANI
        "Dongfeng": ["Dongfeng M-HERO", "Dongfeng Rich 6 EV 450", "Dongfeng Aeolus Huge", "Others, please specify"], //WALA PANI
        "Honda Motors": ["Click 125", "Click 125 (Special Edition)", "Honda Click 150i", "DIO","AirBlade160", "Beat (Playful)", "Beat (Premium)", "PCX160 - CBS", "PCX160 - ABS", "ADV160", "CBR150R", "CB150X", "Winner X (ABS Premium)", "Wave RSX (DISC)", "Wave RSX (Drum)", "Winner X (Standard)", "Winner X (ABS Premium)", "XRM125 DS", "XRM125 MOTARD", "RS125", "XR150L", "CRF150L", "CRF300L", "CRF300 Rally", "X-ADV", "NX500", "CRF1100L Africa Twin", "EM1 e", "TMX125 Alpha", "Others, please specify"],
        "Kawasaki": [ "Kawasaki Rouser NS200", "Kawasaki Rouser RS200", "Kawasaki Barako II", "Kawasaki CT100", "Kawasaki Dominar 400", "Kawasaki Ninja 400", "Kawasaki Ninja ZX-25R", "Others, please specify"],
        "Kymco": ["Kymco Super 8", "Kymco Xciting 300i", "Kymco AK550", "Kymco Like 150i", "Others, please specify"],
        "MotorStar": ["MotorStar MSX200-II", "MotorStar Xplorer X200R", "MotorStar Nicess 110", "Others, please specify"],
        "Piaggio": ["Piaggio Vespa Primavera", "Piaggio Vespa GTS", "Others, please specify"],
        "Racal" : ["Racal 115", "Racal King 175", "Racal Raptor 250", "Racal Classic 150", "Racal KRZ 150", "Racal Adventure 200", "Racal 160", "Others, please specify"], //WALA PASAAAAD
        "Rusi": ["Rusi Flash 150", "Rusi Mojo 200", "Rusi Classic 250", "Others, please specify"],
        "Suzuki Motors": ["Suzuki Raider R150", "Suzuki Skydrive", "Suzuki Burgman Street", "Suzuki Smash 115", "Suzuki GSX-R150", "Suzuki Gixxer", "Others, please specify"],
        "SYM": ["SYM Maxsym 400i", "SYM Bonus X", "SYM RV1-2", "Others, please specify"],
        "TVS": ["TVS Apache RTR 200", "TVS Apache RTR 160", "TVS Dazz", "TVS XL100", "Others, please specify"],
        "Yamaha": ["Yamaha Mio Aerox", "Yamaha Mio Soul i 125", "Yamaha Mio i 125", "Yamaha Mio Sporty", "Yamaha NMAX", "Yamaha XMAX", "Yamaha FZi", "Yamaha YZF-R15", "Yamaha Sniper 155", "Others, please specify"],
        "Chevrolet": ["Colorado", "Spark", "Trailblazer", "Tracker", "Trax", "Tahoe", "Suburban", "Corvette", "Camaro", "Captiva", "Others, please specify"],
        "Foton": ["Foton Thunder", "Foton Tunland V9", "Foton Toplander", "Others, please specify"], //WAALA
        "Ford": ["EcoSport", "Everest", "Mustang", "Ranger", "Expedition", "Others, please specify"],
        "GAC": ["GAC Emkoo", "GAC Empow", "GAC GS3 Emzoom", "GAC M6 Pro", "GAC GS8", "Others, please specify"], //WALA PANI
        "Geely": ["Geely Azkarra", "Geely Coolray", "Geely Emgrand", "Geely GX3 Pro", "Others, please specify"], //WALA PANI
        "Honda": ["Accord", "Brio", "Civic", "City", "CR-V", "HR-V", "Pilot", "Others, please specify"],
        "Hyundai": ["Accent", "Kona", "Santa Fe", "Tucson", "Elantra", "Others, please specify"],
        "Isuzu": ["Alterra", "D-Max", "MU-X", "N-Series (Truck)", "F-Series (Truck)", "Others, please specify"],
        "Kia": ["Carnival", "Picanto", "Rio", "Seltos", "Sportage", "Others, please specify"],
        "Lexus": ["ES", "NX", "RX", "Others, please specify"],
        "Mazda": ["Mazda BT-50", "Mazda CX-3", "Mazda CX-30", "Mazda CX-5", "Mazda CX-60", "Mazda CX-8", "Others, please specify"], //wla pani picture
        "MG (Morris Garages)": ["MG6", "RX5", "ZS", "Others, please specify"],
        "Mitsubishi": ["Lancer", "Mirage", "Mirage G4", "Montero Sport", "Outlander", "Strada", "Xpander", "L300", "Others, please specify"],
        "Nissan": ["Almera", "Juke", "Livina", "Navara", "Patrol", "Terra", "X-Trail", "Others, please specify"],
        "Peugeot": ["308", "3008", "5008", "Others, please specify"],
        "Subaru": ["Forester", "Legacy", "Outback", "XV", "Others, please specify"],
        "Suzuki": ["Celerio", "Ertiga", "Jimny", "S-Presso", "Swift", "Vitara", "Others, please specify"],
        "Toyota": ["Corolla", "Fortuner", "Innova", "Land Cruiser", "RAV4", "Vios", "Avanza", "Others, please specify"],
        "Volkswagen": ["Golf", "Jetta", "Passat", "Tiguan", "Others, please specify"],
        "Battle": ["Battle Excellence-870 Mountain Bike", "Others, please specify"],
        "Brusko": ["Brusko Arrow Mountain Bike", "Others, please specify"],
        "Cannondale": ["Cannondale Trail 7 Mountain Bike", "Others, please specify"],
        "GT": ["GT Avalanche Elite Mountain Bike", "Others, please specify"],
        "Hiland": ["Hiland 26er Mountain Bike", "Others, please specify"],
        "Kona": ["Kona Lava Dome Mountain Bike", "Others, please specify"],
        "Nakto": ["Nakto Ranger Electric Bike", "Others, please specify"],
        "RoyalBaby": ["RoyalBaby Freestyle Kids Mountain Bike", "Others, please specify"]

    };

    if (models[vehcomp]) {
        models[vehcomp].forEach(modelOption => {
            const option = document.createElement("option");
            option.value = modelOption;
            option.text = modelOption;
            model.appendChild(option);
        });
    }
}
</script>

</head>
<body>
   <?php include_once('includes/sidebar.php');?>
    <!-- Right Panel -->

   <?php include_once('includes/header.php');?>
<div class="right-panel">
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Add Vehicle</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="add-vehicle.php">Vehicle</a></li>
                            <li class="active">Add Vehicle</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    
                   
                </div> <!-- .card -->

            </div><!--/.col-->


            <!-- Plate Number Exists Modal -->
<div class="modal fade" id="plateExistsModal" tabindex="-1" role="dialog" aria-labelledby="plateExistsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plateExistsModalLabel">Duplicate Plate Number</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The plate number you entered already exists in the database. Please check your entry.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

      

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Add </strong> Vehicle
                    </div>
                    <div class="card-body card-block">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            

                        <!-- Vehicle Category Dropdown (Type Vehicle) -->
<div class="row form-group">
<div class="col col-md-3"><label for="select" class="form-control-label">Vehicle Type</label></div>
<div class="col-12 col-md-9">
<select name="catename" id="catename" class="form-control" onchange="updateMakeBrandOptions()">
    <option value="0">Select Vehicle Type</option>
    <?php 
    $query = mysqli_query($con, "SELECT * FROM tblcategory");
    while ($row = mysqli_fetch_array($query)) { ?>    
        <option value="<?php echo $row['VehicleCat'];?>"><?php echo $row['VehicleCat'];?></option>
    <?php } ?> 
</select>
</div>
</div>

<!-- Make/Brand Dropdown (Make/Brand) - Initially empty -->
<div class="row form-group">
    <div class="col col-md-3"><label for="vehcomp" class="form-control-label">Make/Brand</label></div>
    <div class="col-12 col-md-9">
        <select id="vehcomp" name="vehcomp" class="form-control" required="true" onchange="updateModelOptions()">
            <option value="">Select Make/Brand</option>
            </select>

        <!-- Custom input for Make/Brand -->
<input type="text" id="otherMake" name="otherMake" class="form-control" placeholder="Please specify Make/Brand" style="display:none; margin-top:10px;">
</div>
</div>

<!-- Model Dropdown -->
<div class="row form-group">
    <div class="col col-md-3"><label for="model" class="form-control-label">Model</label></div>
    <div class="col-12 col-md-9">
        <select id="model" name="model" class="form-control" required="true">
            <option value="">Select Model</option>
        </select>
        
        <!-- Custom input for Model -->
<input type="text" id="otherModelInput" name="otherModel" class="form-control" placeholder="Please specify Model" style="display:none; margin-top:10px;">
</div>
</div>

        <!-- Color Input Field with Autocomplete -->
<div class="row form-group">
<div class="col col-md-3">
<label for="color" class="form-control-label">Color</label>
</div>
<div class="col-12 col-md-9">
<!-- Color input field with datalist for autocomplete -->
<input type="text" id="color" name="color" class="form-control" placeholder="Vehicle Color" required="true" list="colorList">
<!-- Datalist with predefined colors -->
<datalist id="colorList">
<option value="Black"></option>
<option value="White"></option>
<option value="Blue"></option>
<option value="Red"></option>
<option value="Green"></option>
<option value="Yellow"></option>
<option value="Gray"></option>
<option value="Silver"></option>
<option value="Orange"></option>
<option value="Pink"></option>
<option value="Purple"></option>
<option value="Brown"></option>
</datalist>
</div>
</div>

                         
                             <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Plate Number </label></div>
                                <div class="col-12 col-md-9"><input type="text" id="vehreno" name="vehreno" class="form-control" placeholder="Plate Number" required="true"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Owner Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="ownername" name="ownername" class="form-control" placeholder="Owner Name" required="true"></div>
                            </div>
                             <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Owner Contact Number</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="ownercontno" name="ownercontno" class="form-control" placeholder="Owner Contact Number" required="true" maxlength="11" pattern="[0-9]+"></div>
                            </div>
                           
                            
                            
                           <p style="text-align: center;"> <button type="submit" class="btn btn-primary btn-sm" name="submit" ><i class="fa fa-plus"> Add</i></button></p>
                        </form>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="col-lg-6">
                        
                  
                </div>

           

            </div>


        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>


</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>


</body>
</html>
<?php }  ?>