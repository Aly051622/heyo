<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['vpmsuid'])) {
    echo "<script>alert('User not logged in.'); window.location='login.php';</script>";
    exit();
}

// Fetch user details based on the session ID
$userID = $_SESSION['vpmsuid'];
$query = mysqli_query($con, "SELECT MobileNumber FROM tblregusers WHERE ID='$userID'");
$user = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Customer Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .bg-img {
            background: url('images/ctuser.png');
            height: 100vh;
            background-size: cover;
            background-position: center;
            position: relative;
            z-index: -5;
            overflow: hidden;
        }

        .bg-img::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: -3;
        }

        .container{
            margin-top: -100px;
            margin-left: 11em;
            position: absolute;
        }

        .navbar {
            margin-left: -20px;
            margin-top:-20px;
            overflow: hidden;
            width: 100vw;
            background-color: #ff9933;
            padding: 10px 0;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        #chat-box {
            margin-left: 10em;
            width: 70%; 
            height: 500px;
            padding: 35px;
            border: none;
            z-index: 1000;
            margin-top: -90px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #007bff #ff9933;
        }

        .message {
            padding: 10px;
            font-size: 12px;
        }

        .message-support {
            width: fit-content;
            max-width: 70%;
            border-radius: 10px;
            color: #444;
            background-color: #f1f1f1;
            padding: 10px;
            margin: 5px 0;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 5px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-left: -20px;
        }

        .message-user {
            width: fit-content;
            max-width: 70%;
            border-radius: 10px;
            color: #fff;
            background-color: #007bff; 
            padding: 10px;
            margin: 5px 0;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 5px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
        }

        #message-input {
            width: calc(100% - 800px);
            padding: 5px;
            z-index: 30px;
            margin-top: 20px;
            position: relative;
            border-radius: 4px;
            border:none;
            box-shadow: dimgray 0px 0px 0px 3px;
            margin-left: 22em;
        }

        #send-button, .btn, #message-icon {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: solid white;
            cursor: pointer;
            border-radius: 9px;
            margin-left: 10px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        #send-button:hover, .btn-hover, #message-icon:hover {
            color:#0056b3;
            border: solid #0056b3;
            background-color: white;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }

        .message-icon {
            cursor: pointer;
        }

        h1 {
            text-align:center;
            margin-top: -5px;
            margin-left: 22em;
            position: absolute;
            color: white;
            font-size:30px;
        }

        button {
            border: none;
            cursor: pointer;
        }

        h4 {
            width: 115px;
            margin-left: 20em;
            padding: 10px;
            color: orange;
        }

        h5 {
            padding: 5px;
            margin-top: 15px;
            z-index: 1005;
            font-weight: bold;
            font-size: 16px;
            width:100%;
        }

        .card-body {
            margin-top: 30px;
            z-index: 1000;
        }

        #faq-section {
            position: relative;
            z-index: 1;
            color: black;
        }

        #faq-section {
            z-index: 5000;
            color: black;
        }

        .faq-item {
            margin-bottom: 12px;
            z-index: 1000;
            padding:10px;
            background: whitesmoke;
            border-radius: 18px;
            height: 30%;
            width: 100%;
            font-size:14px;
        }

        .faq-question {
            font-weight: bold;
            z-index: 1000;
            background: whitesmoke;
            border-radius: 18px;
            height: 30%;
            width: 90%;
        }

        .breadcrumb {
            background: transparent;
            z-index: 1;
        }
    </style>
</head>
<body class="bg-img">
  <!-- Navbar Section -->
  <div id="page-top" class="navbar">
    <a href="../welcome.php" class="btn btn-primary" id="home"  style="margin-left: 30px;">
      <i class="bi bi-caret-left-fill"></i> Back
    </a>
    <h1>Customer Service</h1>
  </div>

  <!-- Breadcrumbs -->
  <div class="breadcrumbs mb-3">
    <div class="breadcrumbs-inner">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active"><span>Customer Service</span></li>
      </ol>
    </div>
  </div>

  <!-- Chat Section -->
  <div class="container">
    <div id="chat-box"></div>
    <textarea id="message-input" rows="4"></textarea>
    <button id="send-button">Send</button>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Wait until DOM is loaded to attach event listeners

        // Handle sending the message
        document.getElementById('send-button').addEventListener('click', function () {
            const message = document.getElementById('message-input').value;
            if (message.trim() !== "") {
                fetch('send_message.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const chatBox = document.getElementById('chat-box');
                        chatBox.innerHTML += `<div class="message-user">${message}</div>`;
                        document.getElementById('message-input').value = ''; // Clear input after sending
                    } else {
                        alert(data.message); // Show error if message is not sent successfully
                    }
                })
                .catch(err => {
                    alert('Error: ' + err); // Handle any errors that occur during the fetch
                });
            }
        });
    });
  </script>
</body>
</html>
