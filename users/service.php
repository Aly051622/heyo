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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
<link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
<link rel="stylesheet" href="../admin/assets/css/style.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet" type="text/css">
<link rel="apple-touch-icon" href="../images/aa.png">
      <link rel="shortcut icon" href="../images/aa.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet">
<link rel="stylesheet" href="park.css">
<link rel="stylesheet" href="css/responsive.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Customer Service | CTU Danao VPMS</title>
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
         #faq-section {
            position: relative;
            z-index: 1;
            color: black;
            }
        #faq-section {
            z-index: -1;
            color: black;
        }
        .faq-item {
            margin-bottom: 1px;
            padding: 5px;
            background: white;
            border-radius: 18px;
            height: 30%;
            width: 75%;
            font-size:12px;
            margin-bottom: 2px;
        }
        .faq-question {
            font-weight: bold;
            z-index: 1000;
            background: whitesmoke;
            border-radius: 18px;
            height: 30%;
            width: 90%;
        }
    
      /*sa tanan na ni*/
#right-panel {
    margin-left: 100px;
    transition: margin-left 0.3s ease;
}
.card,
.card-header {
    box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Improve table styling for mobile */
.table-responsive table {
    width: 100%;
    table-layout: auto;
    word-wrap: break-word;
}

.table-responsive th, .table-responsive td {
    white-space: nowrap;
    padding: 8px;
    text-align: left;
}

@media (max-width: 480px) {
    .table-responsive th, .table-responsive td {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
    }
    .table-responsive tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #ddd;
    }
    .table-responsive td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
}



/*for all the braeadcrumbs for users*/
@media (max-width: 1024px) {
    .breadcrumbs {
        width: 95%;
        margin-left: 3em;
    }
}

@media (max-width: 954px) {
    .breadcrumbs {
        width: 90%;
        margin-left: 2em;
    }

    #printbtn,
    #viewbtn {
        padding: 8px 15px;
        font-size: 1rem;
    }
}

@media (max-width: 780px) {
    .breadcrumbs {
        width: 85%;
        margin-left: 1.5em;
    }

    #printbtn,
    #viewbtn {
        padding: 7px 12px;
        font-size: 0.9rem;
    }
}

@media (max-width: 500px) {
    .breadcrumbs {
        width: auto;
        margin-left: -2em;
        padding: 5px;
        display: none;
    }

    #printbtn,
    #viewbtn {
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    body {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    
    .breadcrumbs {
        width: 90%;
        margin-right: 20em;
        padding: 5px;
    }

    #printbtn,
    #viewbtn {
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    body {
        font-size: 12px;
        height: 100vh;
        overflow: auto;
    }
    .card, .card-header, .card-body, .tables{
        width: 330px;
        font-size:12px;
        
    }
    .tables thead{
        padding: 2px;
    }
 
}


@media (max-width: 300px) {
    .breadcrumbs {
        width: 100%;
        margin-left: 0.5em;
        padding: 4px;
    }

    #printbtn,
    #viewbtn {
        padding: 5px 8px;
        font-size: 0.8rem;
    }

    body {
        font-size: 0.8rem;
    }
}




/* Responsive Styles for header na ni */

@media (max-width: 1024px) {
    .breadcrumbs{
        display: flex;
    }
    .navbar-header {
        padding: 5px;
    }
    .user-avatar {
        height: 35px;
        width: 35px;
    }
    .active-indicator {
        bottom: 3em;
        right: 10px;
    }
    .user-area img {
        margin-right: 12px;
        margin-top: -4em;
    }
    
    .dropdown{
        margin-top: -85px;
        margin-right: 40px;
    }
    
    .dropdown-toggle{
        margin-top: 30px;
    }
}

@media (max-width: 780px) {
    .breadcrumbs{
        display: flex;
    }
    .navbar-header {
        padding: 4px;
    }
    .user-avatar {
        height: 30px;
        width: 30px;
    }
    .active-indicator {
        bottom: 2.8em;
        right: 8px;
    }
    .user-area img {
        margin-right: 10px;
        margin-top: -3em;
    }
    
    .dropdown{
        margin-top: -85px;
        margin-right: 40px;
    }
    
    .dropdown-toggle{
        margin-top: 30px;
    }

    #message-icon{
        width: 100px;
    }
}

@media (max-width: 500px) {
    .breadcrumbs{
        display: flex;
    }
    .navbar-header {
        padding: 3px;
        width: 100vw;
    }
    .user-avatar {
        height: 25px;
        width: 25px;
        margin-top: 20px;
    }
    .active-indicator {
        bottom: 2.5em;
        right: 6px;
        font-size: 10px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
    }
    .user-area img {
        margin-right: 8px;
        margin-top: -2em;
    }
    
    .dropdown{
        margin-top: -85px;
        margin-right: 40px;
    }
    
    .dropdown-toggle{
        margin-top: 30px;
    }

    #message-icon{
        width: 70px;
    }
}
@media (max-width: 480px){
    .chatbox-container{
        display: flex;
    }
    body{
        overflow-x: auto;
    }
    .navbar-header {
        padding: 3px;
        width: 100vw;
        height: 68px;
    }
    .user-avatar {
        height: 25px;
        width: 25px;
        margin-top: 20px;
    }
    .active-indicator {
        bottom: 2.5em;
        right: 6px;
        font-size: 10px;
    }
    .user-area {
        flex-direction: column;
        align-items: flex-start;
    }
    .user-area img {
        margin-right: 8px;
        margin-top: -5em;
    } 
    
    .dropdown{
        margin-top: -82px;
        margin-right: 40px;
    }
    
    .dropdown-toggle{
        margin-top: 30px;
    }

    #message-icon{
        width: 70px;
    }
}

@media (max-width: 300px) {
    .navbar-header {
        padding: 2px;
        width: 100vw;
    }
    .user-avatar {
        height: 20px;
        width: 20px;
        margin-top: 10px;
    }
    .active-indicator {
        bottom: 2em;
        right: 4px;
        font-size: 9px;
    }
    .user-area img {
        margin-right: 6px;
        margin-top: -1.5em;
    }
    
    .dropdown{
        margin-top: -85px;
        margin-right: 40px;
    }
    
    .dropdown-toggle{
        margin-top: 30px;
    }

    #message-icon{
        width: 70px;
    }
}

    </style>
</head>
<body>
 <!-- Left Panel -->

 <?php include_once('includes/sidebar.php');?>
 <?php include_once('includes/header.php');?>

 <div class="right-panel">
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                    <div class="page-title" id="message-icon">
                        <i class="bi bi-chat-left-text-fill" ></i> Ask for Help
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li class="active">Customer Service</li>
                                <li class="active">FAQs</li>                              
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

    <!-- FAQ Section -->
    <div class="container" id="container">
        <div class="space"></div>
       <div class="faq"> <i class="bi bi-question-circle-fill "></i> FAQs</div>
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
            </div><br>

            <!-- Right Column for the last 4 FAQ items -->
            <div class="col-md-6">
                <div class="faq-item">
                    <div class="faq-question">Q: What should I do if I forget my parking pass?</div>
                    <div class="faq-answer">A: Visit the support chat or use the 'Forgot Pass' feature on the login page. You'll be guided to retrieve or reset your pass.</div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Q: How to recover account when email/password is forgotten?</div>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.netz/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/js/main.js"></script>


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
    const faqSection = document.getElementById('container');
    if (faqSection.style.display === 'none' || faqSection.style.display === '') {
        faqSection.style.display = 'block';
    } else {
        faqSection.style.display = 'none';
    }
}

</script>

</body>
</html>
