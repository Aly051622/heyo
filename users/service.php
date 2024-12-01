<?php
session_set_cookie_params([
    'domain' => '.ctudanaoparksys.icu',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');

if (!isset($_SESSION['vpmsuid'])) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Customer Service | CTU Danao VPMS</title>
    <!-- CSS and JS Includes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        <style>
        body {
            background: whitesmoke;
            height: 100vh;
            overflow: hidden;
        }
            .container{
                margin-top: 45px;
                margin-left: 2em;
                position: absolute;
                z-index: -1;
            }
        .breadcrumbs{
            display: block;
        }
        #chat-box {
            margin-left: 3em;
            width: 95%; 
            height: 370px;
            padding: 35px;
            border: none;
            z-index: 999;
            margin-top: -180px;
        }
        /* Scrollbar styling for the chat box */
        #chat-box {
            overflow-y: auto; /* Enables vertical scrolling */
            scrollbar-width: thin; /* For Firefox */
            scrollbar-color: #007bff #ff9933; /* For Firefox */
        }

        #chat-box-container {
        display: none;
    }
        /* Animation for Chat Box */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #chat-box-container {
            display: none;
            opacity: 0;
            animation: slideIn 0.4s ease forwards; /* Slide-in effect */
        }

        .message {
            padding: 10px;
            font-size: 12px;
        }
        .message-support {
            width: fit-content;
            max-width: 70%; /* Adjust width for better readability */
            border-radius: 10px;
            color: #444;
            background-color: #f1f1f1; /* Light background for user messages */
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

        /* Icon styling to maintain spacing */
        .message i {
            margin-right: 10px; /* Space between icon and message text */
        }

        .message-support i {
            margin-left: 10px; /* Space between icon and message text for support */
        }

        #message-input {
            width: calc(100% - 300px);
            padding: 5px;
            z-index: 30px;
            margin-top: 20px;
            position: relative;
            border-radius: 4px;
            border:none;
            box-shadow: dimgray 0px 0px 0px 3px;
            margin-left: 7em;
        }
        #message-input:hover{
            border: none;
            box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
        }
        #send-button, .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: solid white;
            cursor: pointer;
            border-radius: 9px;
            margin-left: 10px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #send-button:hover, .btn-hover{
            color:#0056b3;
            border: solid #0056b3;
            background-color: white ;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }
        #message-icon{
            cursor: pointer;
            width: 175px;
            padding:11.5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-top-left-radius: 7px;
            border-bottom-left-radius: 7px;
            margin-left:-30px ;
        }
        #message-icon:hover{
            color:#0056b3;
            background-color: white ;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }
        h1{
            text-align:center;
            margin-top: -5px;
            margin-left: 22em;
            position: absolute;
            color: white;
            font-size:30px;
        }
        
        button{
            border: none;
            cursor: pointer;

        }
        .faq{
            color: orange;
            padding: 10px;
            font-weight: bold;
            margin-left: -9em;
        }
        h5{
            padding: 5px;
            margin-top: 15px;
            z-index: 1005;
            font-weight; bold;
            font-size: 16px;
            width:100%;
        }
        .card-body{
            margin-top: 30px;
            z-index: 1000;
         }
    </style>
</head>
<body>
<div class="container">
    <h3>Chat with Support</h3>
    <div id="chat-box"></div>
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type your message...">
        <div class="input-group-append">
            <button id="send-button" class="btn btn-primary">Send</button>
        </div>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    function addMessage(username, message, isSupport = false) {
        const messageDiv = document.createElement('div');
        messageDiv.textContent = `${username}: ${message}`;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    sendButton.addEventListener('click', () => {
        const message = messageInput.value.trim();
        if (message === '') return;

        fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                addMessage('You', message);
                messageInput.value = '';
            } else {
                alert('Error sending message');
            }
        });
    });

    setInterval(() => {
        fetch('get_messages.php')
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML = '';
                data.messages.forEach(msg => {
                    addMessage(msg.username, msg.message, msg.isSupport);
                });
            });
    }, 2000);
</script>
</body>
</html>
