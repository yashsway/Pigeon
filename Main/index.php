<?php
require_once ('init.php');

$_dev_mode = 0; //0 for dev 1 for prod	

if ($_dev_mode == 0){	
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);	
}

$test_2 = new Test('hello');

echo $test_2->hello();
echo $test_2::$testman;



?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Pigeon</title>
    <meta name="description" content="IT Support Ticketing App" />
    <meta name="author" content="Yash Gopal" />

    <!--mobile viewport optimization-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--yaml framework-->
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/core/base.min.css" />
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/forms/gray-theme.css" />
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/navigation/hlist.css" />
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/typography.css" />
    <link rel="stylesheet" href="../Frameworks/yaml412-130728/yaml412-130728/yaml/screen/grid-960gs-16.css" />
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
    <link rel="stylesheet" type="text/css" href="../Frameworks/datedropper-master/datedropper-master/datedropper.css" />
    <!--noUiSlider-->
    <link href="../Frameworks/noUiSlider/jquery.nouislider.min.css" rel="stylesheet">
    <!--Custom Stylesheet-->
    <link rel="stylesheet" href="supplementary.css" />

</head>

<body>
    <nav class="ym-hlist">
        <ul>
            <li>
                <h2 class="nav-title nav-element" id="nav-title"><span class="glyphicon glyphicon-flag"></span> Pigeon</h2>
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
            <table class="table main-panel_dev" id = "main_panel">
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
		            $table_values = new Db();
		            $result = $table_values->get_all_pigeons();
		            
		            
		            while($row = $result->fetch_assoc()){
			         	echo "<tr id =". $row['id']."><td class='report-elements report-title'>".$row['report_summary']."</td>";    
			            echo "<td class='report-elements report-id'>#001</td>
                        <td class='report-elements report-date'>April 27th</td>
                        <td class='report-elements report-duration'>10mins</td>
                        <td class='report-elements report-urgency'>High</td>
                        <td class='report-elements report-tools'>
                            <button class='btn btn-default view'>View</button>
                            <button class='btn btn-danger delete'>Delete</button>
                        </td><tr>";
			            
			        }    
		           // echo "<tr><td>test</td></tr>";    
		                
		            ?>
                        
                </tbody>
            </table>
        </div>
        <div class="col-md-2 col-sm-2 side-panel">
        </div>
    </div>
    <!--Report Full Detail modal overlay-->
    <div class="modal fade full-info" id="full-info" tabindex="-1" role="dialog" aria-labelledby="full-info-title" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="editReport_close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="full-info-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!--Column 1-->
                        <div class="col-md-4">
                            <div class="name_container">
                                <span class="fa fa-user fa-lg full-info-icons" aria-hidden="true"></span>
                                <p class="full-info-text name"></p>
                            </div>
                            <div class="department_container">
                                <span class="fa fa-building fa-lg full-info-icons" data-toggle="tooltip" data-placement="top" title="Department"></span>
                                <p class="full-info-text department"></p>
                            </div>
                        </div>
                        <!--Column 2-->
                        <div class="col-md-4">
                            <div class="phone_container">
                                <span class="fa fa-phone fa-lg full-info-icons" aria-hidden="true"></span>
                                <p class="full-info-text phone"></p>
                            </div>
                        </div>
                        <!--Column 3 -->
                        <div class="col-md-4">
                            <div class="email_container">
                                <span class="fa fa-envelope fa-lg full-info-icons" aria-hidden="true"></span>
                                <p class="full-info-text email"></p>
                            </div>
                        </div>
                        <!--Full span-->
                        <div class="col-md-12">
                            <div class="request_container">
                                <span class="fa fa-life-ring fa-lg full-info-icons" data-toggle="tooltip" data-placement="top" title="Request Category"></span>
                                <p class="full-info-text request"></p>
                            </div>
                        </div>
                        <!--Full span-->
                        <div class="col-md-12">
                            <div class="details_container">
                                <span class="fa fa-file-text fa-lg full-info-icons" data-toggle="tooltip" data-placement="top" title="Details"></span>
                                <br/>
                                <p class="full-info-text details"></p>
                            </div>
                            <br>
                        </div>
                        <!--Column 1-->
                        <div class="col-md-6">
                            <div class="priority_container">
                                <span class="fa fa-exclamation-triangle fa-lg full-info-icons"  data-toggle="tooltip" data-placement="top" title="Client Priority"></span>
                                <p class="full-info-text priority"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="date_container">
                                <span class="fa fa-calendar fa-lg full-info-icons"></span>
                                <p class="full-info-text date"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="time_container">
                                <span class="fa fa-clock-o fa-lg full-info-icons"></span>
                                <p class="full-info-text time"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="adminDetailsSection">
                        <!--Column 1-->
                        <div class="col-md-12">
                            <div>
                                <span class="fa fa-thumb-tack fa-2x" data-toggle="tooltip" data-placement="top" title="Administrative Details" id="adminDetailsSection_icon"></span>
                            </div>
                        </div>
                        <div class="col-md-6 paperBackground">
                            <div class="adminPriority_container">
                                <span class="">
                                    <span class="fa fa-bell-o fa-lg full-info-icons"  data-toggle="tooltip" data-placement="top" title="Your Priority"></span>
                                    <p class="full-info-text adminPriority"></p>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 paperBackground">
                            <div class="duration_container">
                                <p class="full-info-text duration"></p>
                                <span class="fa fa-rocket fa-lg full-info-icons" data-toggle="tooltip" data-placement="top" title="Duration"></span>
                            </div>
                        </div>
                        <div class="col-md-12 paperBackground">
                            <div class="notes_container">
                                <span class="fa fa-comment-o fa-lg full-info-icons" data-toggle="tooltip" data-placement="top" title="Notes"></span>
                                <br/>
                                <p class="full-info-text notes"></p>
                            </div>
                        </div>
                    </div>
                    <form class="grid-form" id="editReport">
                    <h2>Edit Form</h2>
                    <fieldset>
                        <legend>Client</legend>
                        <div data-row-span="3">
                            <div data-field-span="1">
                                <label>Name</label>
                                <input type="text" minlength="1" maxlength="22" placeholder="Peter Gregory" id="editReport_name" required>
                            </div>
                            <div data-field-span="1">
                                <label>Phone</label>
                                <input type="text" maxlength="19" placholder="905-525-9140 x55555" id="editReport_phone" required>
                            </div>
                            <div data-field-span="1">
                                <label>Email</label>
                                <input type="text" maxlength="28" placeholder="gregp@mcmaster.ca" id="editReport_email" required>
                            </div>
                        </div>
                        <div data-row-span="2">
                            <div data-field-span="1">
                                <label>Date</label>
                                <input type="text" placeholder="mm/dd/yyyy" id="editReport_date">
                            </div>
                            <div data-field-span="1">
                                <label>Time</label>
                                <input type="text" placeholder="2:30 PM or 'anytime'" id="editReport_time">
                            </div>
                        </div>
                    </fieldset><br>
                    <fieldset>
                        <legend>Administrator</legend>
                        <div data-row-span="2">
                            <div data-field-span="1">
                                <label>Admin Priority</label>
                                <form>
                                    <input type="radio" name="adminPriority" value="Inactive" data-toggle="tooltip" data-placement="top" title="Deactive the issue, temporarily"><img class="adminPriority_icons" src="assets/icons/grey-flag.png">
                                    <input type="radio" name="adminPriority" value="Low" data-toggle="tooltip" data-placement="top" title="Whenever possible."><img class="adminPriority_icons" src="assets/icons/green-flag.png">
                                    <input type="radio" name="adminPriority" value="Medium" data-toggle="tooltip" data-placement="top" title="As soon as possible."><img class="adminPriority_icons" src="assets/icons/orange-flag.png">
                                    <input type="radio" name="adminPriority" value="High" data-toggle="tooltip" data-placement="top" title="Urgent!"><img class="adminPriority_icons" src="assets/icons/red-flag.png">
                                </form>
                            </div>
                            <div data-field-span="1" data-toggle="tooltip" data-placement="top" title="How long will it take to resolve the problem?">
                                <label>Duration - <span id="duration_tooltip"></span></label>
                                <div class="sliders" id="editReport_durationSlider"></div>
                            </div>
                        </div>
                        <div data-row-span="2">
                            <div data-field-span="2">
                                <label>Notes (300 characters)</label>
                                <textarea rows="2" cols="20" name="report_notes" wrap="hard" maxlength="300" style="resize:none;height:90px" id="editReport_notes"></textarea>
                            </div>
                        </div>
                    </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" id="edit_issue">Edit</button>
                    <div class="vDivider saveTools" id="editForm_divider"></div>
                    <button class="btn btn-sm btn-primary saveTools" id="editReport_save">Save</button>
                    <button class="btn btn-sm btn-warning saveTools" id="editReport_discard">Discard</button>
                    <em id="editReport_infoMsg">Hover over information for additional help text.&nbsp</em>
                    <button class="btn btn-sm btn-default resolutionTools" id="contact_client">Contact Client</button>
                    <button class="btn btn-success resolutionTools" id="resolve_issue">Resolve</button>
                </div>
                
            </div>
        </div>
    </div>
    <!--New Report Filing modal-->
    <div class="modal fade newReport" id="file-new-report" tabindex="-1" role="dialog" aria-labelledby="newReport-title" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="newReport_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="newReport-title">New Report</h4>
                </div>
                <div class="modal-body">
                    <form class="grid-form" id="newReport">
                        <fieldset>
                            <div data-row-span="3">
                                <div data-field-span="1">
                                    <label>Name</label>
                                    <input type="text" minlength="1" maxlength="22" placeholder="Peter Gregory" id="newReport_name" required>
                                </div>
                                <div data-field-span="1">
                                    <label>Phone</label>
                                    <input type="text" maxlength="19" placeholder="902-525-9140 x55555" id="newReport_phone" required>
                                </div>
                                <div data-field-span="1">
                                    <label>Email</label>
                                    <input type="text" maxlength="28" placeholder="gregp@mcmaster.ca" id="newReport_email" required>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div data-row-span="4">
                                <div data-field-span="1">
                                    <label>Department</label>
                                    <select id="newReport_department">
                                        <option value="blank" selected="selected"></option>
                                        <option value="Residence Admissions" title="Residence Admissions">Res Admissions</option>
                                        <option value="Res Life" title="Residence Life">Res Life</option>
                                        <option value="Res Facilities" title="Residence Facilities">Res Facilities</option>
                                        <option value="NQ Service Centre" title="North Quad Service Centre">NQ Service Centre</option>
                                        <option value="WQ Service Centre" title="West Quad Service Centre">WQ Service Centre</option>
                                        <option value="Conference Services" title="Conference & Event Services">Conference Services</option>
                                    </select>
                                </div>
                                <div data-field-span="1">
                                    <label>Request Category</label>
                                    <select id="newReport_requestCategory">
                                        <option value="blank" selected="selected"></option>
                                        <option value="PC Issues" title="PC Issues">PC Issue (Slow, Malware etc)</option>
                                        <option value="PC Setup" title="PC Setup">PC Setup</option>
                                        <option value="Printer Setup" title="Printer Setup">Printer Setup</option>
                                        <option value="Printer Issue" title="Printer Issues">Printer Issue</option>
                                        <option value="Internet/Network Issues" title="Network Issues">Internet/Network Issues</option>
                                        <option value="Shared Drives" title="Shared Drive Setup">Shared Drives</option>
                                        <option value="Other" title="Other">Other</option>
                                    </select>
                                </div>
                                <div data-field-span="2">
                                    <label>If Other, please specify</label>
                                    <input type="text" id="newReport_otherRequest" maxlength="48" disabled>
                                </div>
                            </div>
                            <div data-row-span="4">
                                <div data-field-span="4">
                                    <label>Report Summary (60 characters max)</label>
                                    <input type="text" id="newReport_summary" minlength="10" maxlength="60" required>
                                </div>
                            </div>
                            <div data-row-span="4">
                                <div data-field-span="4">
                                    <label>Details (500 characters max)</label>
                                    <textarea rows="2" cols="20" name="report_details" wrap="hard" maxlength="500" style="resize:none;height:130px" id="newReport_details"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div data-row-span="2">
                                <div data-field-span="1" id="newReport_priority">
                                    <label>Priority For You</label>
                                    <form>
                                        <input type="radio" name="priority" value="Low" data-toggle="tooltip" data-placement="top" title="Whenever possible."><img src="assets/icons/green-flag.png">
                                        <input type="radio" name="priority" value="Medium" data-toggle="tooltip" data-placement="top" title="As soon as possible."><img src="assets/icons/orange-flag.png">
                                        <input type="radio" name="priority" value="High" data-toggle="tooltip" data-placement="top" title="Urgent!"><img src="assets/icons/red-flag.png">
                                    </form>
                                </div>
                                <div data-field-span="1" id="newReport_meetingTime">
                                    <label>When are you free?</label>
                                    <input type="text" placeholder="mm/dd/yyyy" id="newReport_date">
                                    <input type="text" placeholder="2:30 PM or 'anytime'" id="newReport_time">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" id="newReport_progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                    <em id="newReport_infoMsg">All fields are required, except details.&nbsp</em>
                    <button class="btn btn-default" id="newReport_clear">Clear</button>
                    <button class="btn btn-success btn-lg" id="newReport_submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../Frameworks/bootstrap/js/bootstrap.min.js"></script>
    <script src="../Frameworks/datedropper-master/datedropper-master/datedropper.js"></script>
    <script src="../Frameworks/pietimer-master/jquery.pietimer.min.js"></script>
    <script src="../Frameworks/noUiSlider/jquery.nouislider.all.min.js"></script>
    <script src="script.js"></script>
</body>
</html>