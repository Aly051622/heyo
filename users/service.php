<?php
session_start();
error_reporting(0);
include('../DBconnection/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['vpmsuid']) || strlen($_SESSION['vpmsuid']) == 0) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Support | CTU Danao VPMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
        }

        #chat-box-container {
            margin: 20px auto;
            max-width: 800px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        #chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .message {
            margin-bottom: 10px;
        }

        .message-user {
            text-align: right;
            color: #007bff;
        }

        .message-support {
            text-align: left;
            color: #444;
        }

        #message-input {
            width: calc(100% - 90px);
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        #send-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #send-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div id="chat-box-container">
    <h2>Chat Support</h2>
    <div id="chat-box"></div>
    <div style="display: flex; margin-top: 10px;">
        <input type="text" id="message-input" placeholder="Type your message...">
        <button id="send-button">Send</button>
    </div>
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

        // Send message to the server
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

    // Function to fetch messages periodically
    function fetchMessages() {
        fetch('get_messages.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && Array.isArray(data.messages)) {
                    chatBox.innerHTML = ''; // Clear chat box
                    data.messages.forEach(msg => {
                        addMessageToChat(msg.username, msg.message, msg.isSupport);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);
</script>
</body>
</html>
