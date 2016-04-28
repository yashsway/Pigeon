<?php
    require_once("init.php");
    session_start();//starts session

    if(isset($_POST['log_out'])){
        CheckLogin::kill_session();
        session_destroy();
        header("Location: landing-page.html.php");
        exit;
    }

    //check if there is a cookie upon page load - if there is one, check the age of the current session and if still active, extend by a few hours.
    if (!isset($_SESSION['pigeon_admin'])){
        session_destroy();
        header("Location: landing-page.html.php");
        exit;
    }else{
        CheckLogin::check_session_age('pigeon_admin');
    }
    //Load all functions
    require_once('functions.php');
    //Display reports?
    displayReports(true);
    //Global array that stores all reports from database from ALL instances
    $reports = array();
    //Fetch reports from the database
    $reports = fetchReports();
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Pigeon</title>
        <meta name="description" content="IT Support Ticketing App">
        <meta name="author" content="Yash Gopal">
        <link rel="icon" type="image/png" href="assets/icons/favicon.png">
        <!--mobile viewport optimization-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--yaml framework-->
        <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/core/base.min.css">
        <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/forms/gray-theme.css">
        <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/navigation/hlist.css">
        <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/typography.css">
        <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/grid-960gs-16.css">
        <!--Pure CSS-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
        <!--Bootstrap-->
        <link href="../Frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--Web Fonts-->
        <link href='https://fonts.googleapis.com/css?family=Slabo+27px|Oswald' rel='stylesheet' type='text/css'>
        <!--Grid Forms-->
        <link rel="stylesheet" type="text/css" href="../Frameworks/gridforms-master/gridforms-master/gridforms/gridforms.css">
        <!--Font Awesome-->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!--Datedropper-->
        <link rel="stylesheet" type="text/css" href="../Frameworks/datedropper-master/datedropper-master/datedropper.css">
        <!--noUiSlider-->
        <link href="../Frameworks/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
        <!--Timepicker-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.10/jquery.timepicker.min.css" rel="stylesheet">
        <!--Custom Stylesheet-->
        <link rel="stylesheet" href="supplementary.css">
    </head>
<body>
    <nav class="ym-hlist">
        <ul>
            <li>
                <h2 class="nav-title nav-element" id="nav-title">Pigeon</h2>
            </li>
        </ul>
        <a href="#" class="btn nav-element nav-buttons btn-danger" id="logout"><span class="btn-icons glyphicon glyphicon-off"></span><span class="text">&nbsp;Logout</span></a>
        <a href="#" class="btn nav-element nav-buttons btn-success" id="report"><span class="btn-icons glyphicon glyphicon-plus"></span><span class="text">&nbsp;Report</span></a>
    </nav>
    <div class="container-fluid">
        <div class="col-md-12 col-sm-12 main-panel-container">
            <!--REMOVED table-responsive from below-->
            <table class="table main-panel">
                <thead>
                    <th class="main-panel-header-contents"><img src="assets/icons/info.png" data-toggle="tooltip" data-placement="top" title="ID"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/clipboard.png" data-toggle="tooltip" data-placement="top" title="Summary"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/hammer.png" data-toggle="tooltip" data-placement="top" title="Resolved?"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/tag.png" data-toggle="tooltip" data-placement="top" title="Tag (Click to Update)"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/calendar_month.png" data-toggle="tooltip" data-placement="top" title="Date Due"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/risk.png" data-toggle="tooltip" data-placement="top" title="Client Priority"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/ambulance.png" data-toggle="tooltip" data-placement="top" title="Your Priority"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/timeframe.png" data-toggle="tooltip" data-placement="top" title="How long it will take"/>
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/gear.png" data-toggle="tooltip" data-placement="top" title="Tools"/>
                    </th>
                </thead>
                <tbody id="report-listing">
                   <?php
                        //include 'obj/reportEntry.php';
                        //reportEntry();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Report Full Detail modal overlay-->
    <?php
        include 'forms/reportDetailView.html';
        include 'forms/confirmation.html';
    ?>
            <!--New Report Filing modal-->
            <?php
        include 'forms/newReportForm.html';
    ?>
                <!--jQuery-->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
                <script src="../Frameworks/bootstrap/js/bootstrap.min.js"></script>
                <script src="../Frameworks/datedropper-master/datedropper-master/datedropper.js"></script>
                <script src="../Frameworks/pietimer-master/jquery.pietimer.min.js"></script>
                <script src="../Frameworks/noUiSlider/jquery.nouislider.all.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.10/jquery.timepicker.min.js"></script>
                <script src="script.js"></script>
    </body>

    </html>
