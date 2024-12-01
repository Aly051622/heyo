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
<link rel="stylesheet" href="css/responsive.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FAQs | CTU Danao VPMS</title>
    <!-- Include external styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .faq-header {
            background-color: orange;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .faq-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            background-color: #f1f1f1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-question {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .faq-answer {
            font-size: 14px;
            color: #555;
        }

        .breadcrumb {
            background-color: transparent;
        }
    </style>
</head>
<body>
     <!-- Include sidebar -->
     <?php include_once('includes/sidebar.php'); ?>
    
    <?php include_once('includes/header.php');?>
 <div class="right-panel">
 <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                    <div class="page-title">
                        <h3>FAQs</h3>
                    </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li class="active">Frequently Asked Questions</li>
                                <li class="active">FAQs</li>                              
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header -->
    <div class="faq-header">
        <h1>Frequently Asked Questions</h1>
    </div>

    <!-- FAQ Section -->
    <div class="faq-container">
        <!-- FAQ Items -->
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

        <div class="faq-item">
            <div class="faq-question">Q: What should I do if I forget my parking pass?</div>
            <div class="faq-answer">A: Visit the support chat or use the 'Forgot Pass' feature on the login page. You'll be guided to retrieve or reset your pass.</div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Q: How to recover my account if I forget my email/password?</div>
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
    
<?php include_once('includes/footer.php'); ?>
</body>
</html>
