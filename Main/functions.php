<?php

function fetchReports(){
    //Access the global array 'reports' from within this function
    global $reports;
    $connect = new Db();
    $databaseConnection = $connect->databaseConnection;
    $result = mysqli_query($databaseConnection,'SELECT * FROM reports');
    if(!$result){
        $output = "Error fetching reports: " . mysqli_error($databaseConnection);
        //TEST: Console msg
        echo '<script type="text/javascript">console.log(' . $output . ')</script>';
        exit();
    }
    require_once('obj/reportBlueprint.php');
    while($row = mysqli_fetch_array($result)){
        $reports[] = new reportBlueprint($row['reportID'],$row['reportName'],$row['reportPhone'],$row['reportEmail'],$row['reportDepartment'],$row['reportRequest'],$row['reportCustomRequest'],$row['reportSummary'],$row['reportDetails'],$row['reportPriority'],$row['reportDate'],$row['reportTime'],$row['duration'],$row['admin_priority'],$row['admin_notes'],$row['markedForDeletion'],$row['resolved']);
    }
    //TEST: Console msg
    //echo '<script>console.log("Report(s) in the database: '. count($reports) . '");</script>';
    //echo '<script>console.log("Reports successfully fetched.");</script>';
    return $reports;
}
function reports_indexReturn($id){
    //Access the global array 'reports' from within this function
    global $reports;
    //Find the array index of the report with the help of the report ID
    for($i=0;$i<count($reports);$i++){
        if($id==$reports[$i]->ID){
            return $i;
        }
    }
}
function priorityFlagCodeGenerator($value){
    switch($value){
        case "Inactive":
            return '<img src="assets/icons/grey-flag.png"/>';
        case "Low":
            return '<img src="assets/icons/green-flag.png"/>';
        case "Medium":
            return '<img src="assets/icons/orange-flag.png"/>';
        case "High":
            return '<img src="assets/icons/red-flag.png"/>';
        default:
            return '<img src="assets/icons/bomb.png"/>';
    }
}
function resolutionFlagCodeGenerator($value){
    switch($value){
        case 0:
            return '<img src="assets/icons/close.png"/>';
        case 1:
            return '<img src="assets/icons/checkmark.png"/>';
        default:
            return '<img src="assets/icons/bomb.png"/>';
    }
}
function displayReports($d){
    $GLOBALS['displayReports'] = $d;
}

?>
