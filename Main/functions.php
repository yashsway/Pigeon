<?php
$reports = array();
function fetchReports(){
    $connect = new Db();
    $databaseConnection = $connect->databaseConnection;
    $result = mysqli_query($databaseConnection,'SELECT * FROM reports');
    if(!$result){
        $output = "Error fetching reports: " . mysqli_error($databaseConnection);
        //include 'errorMessage.html.php';
        echo '<script type="text/javascript">console.log(' . $output . ')</script>';
        exit();
    }
    require_once('obj/reportBlueprint.php');
    while($row = mysqli_fetch_array($result)){
        $reports[] = new reportBlueprint($row['reportID'],$row['reportName'],$row['reportPhone'],$row['reportEmail'],$row['reportDepartment'],$row['reportRequest'],$row['reportCustomRequest'],$row['reportSummary'],$row['reportDetails'],$row['reportPriority'],$row['reportDate'],$row['reportTime']);
        //array_push($reports,new reportBlueprint($row['reportID'],$row['reportName'],$row['reportPhone'],$row['reportEmail'],$row['reportDepartment'],$row['reportRequest'],$row['reportCustomRequest'],$row['reportSummary'],$row['reportDetails'],$row['reportPriority'],$row['reportDate'],$row['reportTime']));

        /*$reports[0][] = $row['reportID'];
        $reports[1][] = $row['reportSummary'];
        $reports[2][] = $row['reportDate'];
        $reports[3][] = $row['reportDuration'];
        $reports[4][] = $row['reportPriority'];*/
    }
    echo '<script>console.log("Report(s) in the database: '. count($reports) . '");</script>';
    echo '<script>console.log("Reports successfully fetched.");</script>';
    return $reports;
}
function reports_indexReturn($id){
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
            return '<img src="assets/icons/risk.png"/>';
    }
}

function displayReports($d){
    $GLOBALS['displayReports'] = $d;
}
?>
