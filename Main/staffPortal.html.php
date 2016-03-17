<?php
    require_once("init.php");
    session_start(); //start session

    if(isset($_POST['log_out'])){
        CheckLogin::kill_session();
        header("Location: landing-page.html.php");
        exit;
    }

    if (!isset($_SESSION['pigeon_staff'])){
        CheckLogin::kill_session();
        header("Location: landing-page.html.php");
        exit;
    }else{
        CheckLogin::check_session_age('pigeon_staff');
    }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Pigeon</title>
    <meta name="description" content="IT Help Desk App" />
    <meta name="author" content="Yash Gopal" />
    <link rel="icon" type="image/png" href="assets/icons/favicon.png">
    <!--mobile viewport optimization-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap-->
    <link href="../Frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Web Fonts-->

    <!--Grid Forms-->
    <link rel="stylesheet" type="text/css" href="../Frameworks/gridforms-master/gridforms-master/gridforms/gridforms.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <!--Datedropper-->
    <link rel="stylesheet" type="text/css" href="../Frameworks/datedropper-master/datedropper-master/datedropper.css" />
    <!--Animate css-->
    <link href="../Frameworks/animate.css" rel="stylesheet">
    <!--noUiSlider-->
    <link href="../Frameworks/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
    <link href="../Frameworks/timePicker/jquery.timepicker.css" rel="stylesheet"/>
    <!--Custom Stylesheet-->
    <link href='https://fonts.googleapis.com/css?family=Slabo+27px|Oswald' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="supplementary-3.css"/>
</head>

<body>
   <div id="staffHelper">
       <p id="appStatus">Pigeon</p>
       <div id="currentUserWrapper"><p id="currentUser">Hi User!</p></div>
       <div id="logoutWrapper"><button class="btn btn-default" id="logout">Logout</button></div>
   </div>
    <div class="container-fluid main">
        <div class="row">
            <div class="col-md-6 center">
                <?php include 'modules/newReport.html'; ?>
            </div>
            <div class="col-md-6 center">
                <?php include 'modules/checkTicket.html'; ?>
            </div>
            <!--Add more columns here as necessary-->
        </div>
        <div id="newReport-ticketNumber-wrapper">
            <p class="body-text">Submission successful!
                <p id="newReport_ticketNumber"></p>
            </p>
            <p class="help-text">You can use the number above to check the status of your request. We've also sent you an email confirmation with that number for your reference.<br/>We'll get back to you as soon as possible.</p>
        </div>
        <button class="btn btn-sm btn-default back content">Back</button>
    </div>
    <div id="footer_push"></div>
    <div id="info-bar">
        <p>&#169; 2015, Made with &#9829 by Yash Kadaru</p>
    </div>
    <!--New Report Filing modal-->
    <?php include 'forms/newReportForm.html'; ?>
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../Frameworks/bootstrap/js/bootstrap.min.js"></script>
    <script src="../Frameworks/datedropper-master/datedropper-master/datedropper.js"></script>
    <script src="../Frameworks/timePicker/jquery.timepicker.min.js"></script>
    <script src="script-3.js"></script>
</body>

</html>
