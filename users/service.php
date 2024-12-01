<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<html class="no-js" lang="">
<head>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <title>Customer Service | CTU Danao VPMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: whitesmoke;
            height: 100vh;
            overflow: hidden;
        }
        .container {
            margin-top: 45px;
            margin-left: 2em;
            position: absolute;
        }
        #chat-box {
            margin-left: 3em;
            width: 95%; 
            height: 370px;
            padding: 35px;
            border: none;
            overflow-y: auto;
            margin-top: -180px;
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
            width: calc(100% - 300px);
            padding: 5px;
            z-index: 30px;
            margin-top: 20px;
            position: relative;
            border-radius: 4px;
            border: none;
            box-shadow: dimgray 0px 0px 0px 3px;
            margin-left: 7em;
        }
        #send-button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 9px;
            margin-left: 10px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #send-button:hover {
            color: #0056b3;
            border: solid #0056b3;
            background-color: white;
        }
    </style>
</head>
<body>
<!-- Left Panel -->
<?php include_once('includes/sidebar.php'); ?>
<!-- Right Panel -->
<?php include_once('includes/header.php'); ?>

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title" id="message-icon">
                        <i class="bi bi-chat-left-text-fill"></i> Chat with Support
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li class="active">Customer Service</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<!-- Chat Box Container -->
<div id="chat-box-container">
    <div id="chat-box"></div>
    <input type="text" id="message-input" placeholder="Type your message..." />
    <button id="send-button">
        <i class="bi bi-send-fill"></i> Send
    </button>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    // Function to add a message to the chat box
    function addMessageToChat(username, message, isSupport = false) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(isSupport ? 'message-support' : 'message-user');
        messageDiv.textContent = `${username}: ${message}`;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
    }

    // Function to send a message
    sendButton.addEventListener('click', function () {
        const userMessage = messageInput.value.trim();
        if (userMessage === '') {
            alert('Please enter a message before sending.');
            return;
        }
        // Add user message to chat
        addMessageToChat('You', userMessage);
        messageInput.value = ''; // Clear input

        // Send message to server
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: userMessage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Message sent successfully');
            } else {
                alert('Failed to send message: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    });
</script>

</body>
</html>
