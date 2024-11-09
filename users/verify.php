<?php
session_start();
require '../vendor/autoload.php';
require 'includes/dbconnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationCode($email) {
    $newCode = rand(100000, 999999);
    $_SESSION['verification_code'] = $newCode;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'developershalcyon@gmail.com';
        $mail->Password = 'uhdv sagp oljc smwm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('developershalcyon@gmail.com', 'Parking Verification');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "<p>Your verification code is: <strong>$newCode</strong></p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error sending email: " . $mail->ErrorInfo);
        return false;
    }
}

function saveAccount($email) {
    global $con;
    $query = "UPDATE tblregusers SET status='active' WHERE Email=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    return $stmt->execute();
}

if (isset($_GET['resend'])) {
    if (isset($_SESSION['verification_email'])) {
        $email = $_SESSION['verification_email'];
        if (sendVerificationCode($email)) {
            echo json_encode(['success' => true, 'message' => 'A new code has been sent to your email.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send the code. Please try again.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email address not found.']);
    }
    exit;
}

if (isset($_POST['submit'])) {
    $userInputCode = $_POST['verification_code'];

    if (!isset($_SESSION['verification_code'])) {
        echo json_encode(['success' => false, 'message' => 'Verification code not found.']);
        exit;
    }

    if ($_SESSION['verification_code'] == $userInputCode) {
        if (saveAccount($_SESSION['verification_email'])) {
            unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['attempts']);
            echo json_encode(['success' => true, 'message' => 'Account verified. Redirecting...']);
            header('Location: dashboard.php');
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the account.']);
        }
    } else {
        $_SESSION['attempts'] = $_SESSION['attempts'] ?? 0;
        $_SESSION['attempts']++;

        if ($_SESSION['attempts'] >= 3) {
            echo json_encode(['success' => false, 'message' => 'Maximum attempts reached. Please sign up again.']);
            unset($_SESSION['attempts']);
            header('Location: signup.php');
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect code. Please try again.']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <script src="JavaScript/verify.js" defer></script>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="verification_code" required placeholder="Enter verification code">
        <input type="submit" name="submit" value="Verify">
    </form>

    <p>Didn’t receive a code? 
        <a href="#" id="resendButton">Resend</a>
    </p>
</body>
</html>
