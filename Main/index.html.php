<?php
    require_once("init.php");
    //Load all functions
    require_once('functions.php');
    //Display reports?
    displayReports(true);
    //Global array that stores all reports from database from ALL instances
    $reports = array();
    //Fetch reports from the database
    $reports = fetchReports();
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HCS IT Help Desk</title>
    <meta name="description" content="IT Support Ticketing App">
    <meta name="author" content="Yash Gopal">

    <!--mobile viewport optimization-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--yaml framework-->
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/core/base.min.css">
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/forms/gray-theme.css">
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/navigation/hlist.css">
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/typography.css">
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/grid-960gs-16.css">
    <!--Pure CSS-->
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <!--Bootstrap-->
    <link href="../Frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Web Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Slabo+27px|Oswald' rel='stylesheet' type='text/css'>
    <!--Grid Forms-->
    <link rel="stylesheet" type="text/css" href="../Frameworks/gridforms-master/gridforms-master/gridforms/gridforms.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <!--Datedropper-->
    <link rel="stylesheet" type="text/css" href="../Frameworks/datedropper-master/datedropper-master/datedropper.css">
    <!--noUiSlider-->
    <link href="../Frameworks/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
    <!--Custom Stylesheet-->
    <link rel="stylesheet" href="supplementary.css">
</head>

<body>
    <nav class="ym-hlist">
        <ul>
            <li>
                <h2 class="nav-title nav-element" id="nav-title"><span class="glyphicon glyphicon-flag"></span> HCS IT Help Desk</h2>
            </li>
        </ul>
        <a href="#" class="ym-button ym-edit nav-element nav-buttons ym-success" id="login">Login</a>
        <a href="#" class="ym-button ym-add nav-element nav-buttons ym-danger" id="report">Report</a>
    </nav>
    <div class="container-fluid">
        <div class="col-md-2 col-sm-2 side-panel">
        </div>
        <div class="col-md-8 col-sm-8 main-panel-container">
            <!--REMOVED table-responsive from below-->
            <table class="table main-panel">
                <thead>
                    <th class="main-panel-header-contents"><img src="assets/icons/info.png" />
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/clipboard.png" />
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/calendar_month.png" />
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/timeframe.png" />
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/risk.png" />
                    </th>
                    <th class="main-panel-header-contents"><img src="assets/icons/gear.png" />
                    </th>
                </thead>
                <tbody>
                   <?php
                        include 'obj/reportEntry.php';
                        reportEntry();
                    ?>
                    <!--<tr>
                        <td class="report-elements report-id">#001</td>
                        <td class="report-elements report-title">Setup BrandonPro Workstations</td>
                        <td class="report-elements report-date">April 27th</td>
                        <td class="report-elements report-duration">10mins</td>
                        <td class="report-elements report-urgency">High</td>
                        <td class="report-elements report-tools">
                            <button class="btn btn-default view">View</button>
                            <button class="btn btn-danger delete">Delete</button>
                        </td>
                    </tr>-->
                </tbody>
            </table>
        </div>
        <div class="col-md-2 col-sm-2 side-panel">
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
    <script src="script.js"></script>
</body>
</html>
