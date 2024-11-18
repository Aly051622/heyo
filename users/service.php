<?php
// Include the database connection
include('includes/dbconnection.php');
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
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


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
            z-index: -5; /* Ensure background is behind other content */
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
            z-index: -3; /* Same z-index as .bg-img to stay behind */
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
        #message-input:hover{
            border: none;
            box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
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
        #send-button:hover, .btn-hover, #message-icon:hover{
            color:#0056b3;
            border: solid #0056b3;
            background-color: white ;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }
        .message-icon{
            cursor: pointer;
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
        h4{
            width: 115px;
            margin-left: 20em;
            padding: 10px;
            color: orange;
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
         #faq-section {
            position: relative; /* Make FAQ section layered above */
            z-index: 1; /* Higher z-index to ensure it's in front */
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
        .breadcrumb{
            background: transparent;
            z-index: 1;
        }
    </style>
</head>
<body class="bg-img">
  <!-- Navbar Section -->
  <div id="page-top" class="navbar">
    <a href="../welcome.php" class="btn btn-primary" id="home"  style=" margin-left: 30px; positipn: absolute;">
      <i class="bi bi-caret-left-fill" ></i> Back
    </a>
    <h1>Customer Service</h1>
  </div>

  <!-- Breadcrumbs -->
  <div class="breadcrumbs mb-3">
    <div class="breadcrumbs-inner">
      <div class="row m-0">
          <div class="page-header float-right">
            <div class="page-title" style="background: transparent;  margin-top: 5px; margin-bottom: 30px; margin-left: 75em;">
              <ol class="breadcrumb text-right">
                <li>
                  <!-- Message Icon -->
                  <div class="message-icon" id="message-icon">
                    <i class="bi bi-chat-left-text-fill"></i> Chat with Support
                  </div>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chat Box Container -->
  <div id="chat-box-container">
    <div id="chat-box"></div>
    <input type="text" id="message-input" placeholder="Type your message..." />
    <button id="send-button">
      <i class="bi bi-send-fill"></i> Send
    </button>
  </div>

  <!-- FAQ Section -->
  <div class="container" id="container">
  <h4><i class="bi bi-question-circle-fill"></i> FAQs</h4>
  <div id="faq-section" class="row">
    <!-- Left Column for the first 4 FAQ items -->
    <div class="col-md-6">
      <div class="faq-item">
        <div class="faq-question">Q: How do I view my parked vehicles?</div>
        <div class="faq-answer">A: Log in to your account, go to the 'My Vehicles' section, and you'll see a list of all your currently parked vehicles and their locations.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: How do I print a parking receipt?</div>
        <div class="faq-answer">A: After parking confirmation, click the 'Print Receipt' button on your dashboard, or find the receipt in the 'Parking History' section.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: How do I manage my registered vehicles?</div>
        <div class="faq-answer">A: Go to the 'Manage Vehicles' section under your profile. Here you can add, edit, or remove vehicle information as needed.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: How do I change my parking pass or subscription?</div>
        <div class="faq-answer">A: Navigate to the 'Subscriptions' section in your profile, select the parking pass you want to change, and follow the on-screen instructions to update your plan.</div>
      </div>
    </div>

    <!-- Right Column for the last 4 FAQ items -->
    <div class="col-md-6">
      <div class="faq-item">
        <div class="faq-question">Q: What should I do if I forget my parking pass?</div>
        <div class="faq-answer">A: Visit the support chat or use the 'Forgot Pass' feature on the login page. You'll be guided to retrieve or reset your pass.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: How do I recover my account if I forget my email or password?</div>
        <div class="faq-answer">A: Click 'Forgot Password' on the login page. If you forgot your email, contact support via the live chat, and they will assist you with recovery.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: How do I update my email address in the system?</div>
        <div class="faq-answer">A: Go to your profile settings, click 'Edit Email,' and follow the instructions to change your email. A confirmation link will be sent to your new address.</div>
      </div>
      
      <div class="faq-item">
        <div class="faq-question">Q: Can I transfer my parking slot to another vehicle?</div>
        <div class="faq-answer">A: Yes, you can. Visit the 'Manage Vehicles' section, select the parking reservation, and transfer it to a different registered vehicle.</div>
      </div>
    </div>
  </div>
</div>

  </div>
</body>


<script>
    const chatBox = document.getElementById('chat-box');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatBoxContainer = document.getElementById('chat-box-container');
    const messageIcon = document.getElementById('message-icon');

// Toggle Chat Box on Icon Click
messageIcon.addEventListener('click', () => {
    const isChatBoxVisible = chatBoxContainer.style.display === 'none';

    if (isChatBoxVisible) {
        // Show chat box with animation
        chatBoxContainer.style.display = 'block';
        chatBoxContainer.style.animation = 'slideIn 0.4s ease';
        chatBoxContainer.style.opacity = '1';
    } else {
        // Hide chat box smoothly
        chatBoxContainer.style.opacity = '0';
        setTimeout(() => {
            chatBoxContainer.style.display = 'none';
        }, 400); // Matches animation duration
    }

    // Toggle FAQ visibility
    document.getElementById('container').style.display = isChatBoxVisible ? 'none' : 'block';
});


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

// Function to fetch and display messages periodically
function fetchMessages() {
    // Store the current scroll position
    const isAtBottom = chatBox.scrollTop >= chatBox.scrollHeight - chatBox.clientHeight - 10;

    fetch('get_messages.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.messages)) {
                chatBox.innerHTML = ''; // Clear chat box
                data.messages.forEach(msg => {
                    addMessageToChat(msg.username, msg.message, msg.isSupport);
                });

                // If the user is at the bottom, scroll to the latest message
                if (isAtBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

// Fetch messages every 2 seconds
setInterval(fetchMessages, 2000);

// Toggle FAQs
function toggleFAQ() {
    const faqSection = document.getElementById('faq-section');
    if (faqSection.style.display === 'none' || faqSection.style.display === '') {
        faqSection.style.display = 'block';
    } else {
        faqSection.style.display = 'none';
    }
}

</script>

</body>
</html>
