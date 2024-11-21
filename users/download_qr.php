<?php
session_start();
include('includes/dbconnection.php');

// Function to securely validate the token and vehicle ID
function isValidToken($vehid, $token) {
    // Create a token from the vehicle ID and timestamp (you can adjust the time limit as needed)
    $validToken = md5($vehid . time());
    return $validToken === $token;
}

// Check if 'token' and 'vehid' are provided in the URL
if (isset($_GET['token']) && isset($_GET['vehid'])) {
    $token = $_GET['token'];
    $vehid = $_GET['vehid'];

    // Fetch the QR Code file path from the database
    $sql = "SELECT QRCodePath FROM tblvehicle WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $vehid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && !empty($row['QRCodePath'])) {
        $qrCodePath = $row['QRCodePath'];

        // Ensure the QR code file exists on the server
        if (file_exists('../admin/' . $qrCodePath)) {
            // Validate the token
            if (isValidToken($vehid, $token)) {
                // Set headers to initiate a file download
                header('Content-Type: image/png'); // Adjust if the QR code is not PNG
                header('Content-Disposition: attachment; filename="' . basename($qrCodePath) . '"');
                readfile('../admin/' . $qrCodePath);
                exit;
            } else {
                echo "Invalid or expired token.";
            }
        } else {
            echo "QR code file not found.";
        }
    } else {
        echo "No QR code found for this vehicle.";
    }
} else {
    echo "Missing token or vehicle ID.";
}
?>
