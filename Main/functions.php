<?php
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
        case 0:
            return '<img src="assets/icons/grey-flag.png"/>';
        case 1:
            return '<img src="assets/icons/green-flag.png"/>';
        case 2:
            return '<img src="assets/icons/orange-flag.png"/>';
        case 3:
            return '<img src="assets/icons/red-flag.png"/>';
        default:
            return '<img src="assets/icons/hourglass.png"/>';
    }
}
function resolutionFlagCodeGenerator($value){
    switch($value){
        case 0:
            return '<img src="assets/icons/close.png"/>';
        case 1:
            return '<img src="assets/icons/checkmark.png"/>';
        default:
            return '<img src="assets/icons/hourglass.png"/>';
    }
}
function tagCodeGenerator($value){
    switch($value){
        case 0:
            return '';
        case 1:
            return '<img src="assets/icons/new.png"/>';
        case 2:
            return '<img src="assets/icons/targetSoon.png"/>';
        case 3:
            return '<img src="assets/icons/targetNow.png"/>';
        case 4:
            return '<img src="assets/icons/sad.png"/>';
        case 5:
            return '<img src="assets/icons/view.png"/>';
        default:
            return '<img src="assets/icons/hourglass.png"/>';
    }
}

//Tag determination algorithms
function tagGenerator($data){
    $tg = -1;

    //No tag, general report
    if($data[0]['timesViewed']>=1){
        $tg = 0;
    }

    //Check how much time is left from now till the due date in days.
    $dueDate = new DateTime($data[0]['reportDate']);
    $currDate = new DateTime(date("Y-m-d"));
    $interval = $currDate->diff($dueDate);
    $interval = (int)($interval->format('%r%a'));

    if($data[0]['reportTime']!='anytime'){
        $dueTime = new DateTime(date("g:i a",strtotime($data[0]['reportTime'])));
        $currTime = new DateTime(date("g:i a"));
        $interval2 = ($dueTime > $currTime); //Is the due time still ahead of current time?
        /*$interval2 = date_diff($currTime,$dueTime);
        $interval2 = $interval2->format('%r%h');*/
    }

    //Target Now?
    if($interval<0){
        $tg = 0;
    }else if($interval==0){
        $tg = 3;
    }else if(($data[0]['reportPriority']==1 && $interval<=2) || ($data[0]['reportPriority']==2 && $interval<=4) || ($data[0]['reportPriority']==3 && $interval<=6) || ($data[0]['admin_priority']==1 && $interval<=3) || ($data[0]['admin_priority']==2 && $interval<=6) || ($data[0]['admin_priority']==3 && $interval<=9)){
        $tg = 2;
    }else if($data[0]['timesViewed']>=15){
        $tg = 2;
    }

    //Check
    if($data[0]['admin_priority']==(-1)){
        if($data[0]['tag']!=2 && $tg != 2 && $tg !=3){
            $tg = 5;
        }
    }

    //Time lapsed since last edited
    if($data[0]['dateEdited']!=''){
        $dateEdited = new DateTime($data[0]['dateEdited']);
        $interval3 = $dateEdited->diff($currDate);
        $interval3 = (int)($interval3->format('%r%a'));
        if($data[0]['tag']!=2 && $tg!=2){
            if($interval3<=2){
                $tg = 5;
            }
        }
    }
    //Failure
    //Due Date passed
    if($interval<0){
        $tg = 4;
    }
    //Time passed
    if($data[0]['reportTime']!='anytime'){
        //Due date = today, but past time due
        if(($interval==0 && $interval2==false)){
            $tg = 4;
        }
    }

    //No tags if report has been resolved.
    if($data[0]['resolved']==1){
        $tg = 0;
    }

    //New Report?
    if($data[0]['timesViewed']==0){
        $tg = 1;
    }

    return $tg;
}
function displayReports($d){
    $GLOBALS['displayReports'] = $d;
}
?>
