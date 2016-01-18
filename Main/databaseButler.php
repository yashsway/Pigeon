<?php
function def(){
    //The following PHP code needs to be re-executed as this is a different instance
    require_once('init.php');
    require_once('functions.php');
}
function authentication(){
    def();
    $repAuth = new Db();
    $res = $repAuth->authenticate($_POST['auth']);
    echo $res;
}
function reportLookup(){
    def();
    //Establish a new connection
    $repLookCon = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Run the getReportDetails function from the DB class to get the necessary information
    $result = $repLookCon->getReportDetails($query_ID);
    //Encode the associative array into JSON and echo it back
    echo json_encode($result);
}
function reportMark(){
    def();
    //Establish a new connection
    $repMarkCon = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Run the markReport function from the DB class (Send it both the report ID & the addition parameter to specify a mark or an un-mark)
    $result = $repMarkCon->markReport($query_ID,$_GET['reqParam']);
    echo $result;
}
function reportInsert(){
    def();
    //Establish a new connection
    $repInsCon = new Db();
    //Run the insertReport function from the DB class (Send it the report ID & the JSON data of the submitted form)
    $result = $repInsCon->insertReport($_POST['id'],$_POST['na'],$_POST['ph'],$_POST['em'],$_POST['dep'],$_POST['req'],$_POST['cus'],$_POST['summ'],$_POST['det'],$_POST['pri'],$_POST['dat'],$_POST['tim']);
    echo $result;
}
function reportEditView(){
    def();
    //Establish a new connection
    $repEditCon = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Run the editReport function from the DB class (Send it the report ID)
    $result = $repEditCon->editReportView($query_ID);
    echo json_encode($result);
}
function reportEditUpdate(){
    def();
    //Establish a new connection
    $repEditUpCon = new Db();
    //Run the editReportUpdate function from the DB class (Send it all the edit form data + the report ID)
    $result = $repEditUpCon->editReportUpdate($_POST['id'],$_POST['summ'],$_POST['na'],$_POST['ph'],$_POST['em'],$_POST['dat'],$_POST['tim'],$_POST['admPr'],$_POST['dur'],$_POST['nte'],date("jS F, Y"));
    echo $result;
}
function reportResolve(){
    def();
    //Establish a new connection
    $repRes = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Run the resolveReport function from the DB class (send it the report ID)
    $result = $repRes->resolveReport($query_ID,$_GET['reqParam'],date("jS F, Y"));
    echo $result;
}
function reportDelete(){
    def();
    //Establish a new connection
    $repDel = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Run the deleteReport function from the DB class (send it the report ID)
    $result = $repDel->deleteReport($query_ID);
    echo $result;
}
function reportTag(){
    def();
    //Establish a new connection
    $tagUp = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_REQUEST['queryID'];
    //Call the TAG ALGORITHM IN THE PARAMETER TO DEFINE $val
    //Run the tagUpdate function from the DB class (send it the report ID)
    $result = $tagUp->tagUpdate($query_ID);
    echo json_encode($result);
}
function reportCheck(){
    def();
    //Establish a new connection
    $repCheck = new Db();
    //Run the checkReport function from the DB class (Send the user query)
    if(is_numeric($_REQUEST['ticket'])){
        $result = $repCheck->checkReport($_REQUEST['ticket']);
        echo $result;
    }else{
        echo "Invalid Ticket #.";
    }
}
function reportTotal(){
    def();
    //Establish a new connection
    $repTot = new Db();
    //Run the totalReports function from the DB class
    $result = $repTot->totalReports();
    echo json_encode($result);
}
function appStats(){
    def();
    //New Connection
    $stat = new Db();
    //Stats function from the Db class
    $result = $stat->getStatistics();
    echo json_encode($result);
}
function currentUser(){
    session_start();
    echo (string)$_SESSION['user_name'];
}
//TEST:
//echo json_encode(array('name'=>$_POST['na'],'reqType'=>$_POST['reqType']));
//echo "it works";
//echo ((isset($_REQUEST['reqType']))==1);
if((isset($_REQUEST['reqType']))==1){
    if($_REQUEST['reqType']==0){
        reportLookup();
    }else if($_REQUEST['reqType']==1){
        reportMark();
    }else if($_REQUEST['reqType']==2){
        //echo json_encode(array("name"=>$_POST['na'],"reqType"=>$_POST['reqType']));
        reportInsert();
    }else if($_REQUEST['reqType']==3){
        reportEditView();
    }else if($_REQUEST['reqType']==4){
        reportEditUpdate();
    }else if($_REQUEST['reqType']==5){
        reportResolve();
    }else if($_REQUEST['reqType']==6){
        reportDelete();
    }else if($_REQUEST['reqType']==7){
        reportTag();
    }else if($_REQUEST['reqType']==8){
        reportCheck();
    }else if($_REQUEST['reqType']==9){
        reportTotal();
    }else if($_REQUEST['reqType']==10){
        authentication();
    }else if($_REQUEST['reqType']==11){
        appStats();
    }else if($_REQUEST['reqType']==12){
        currentUser();
    }
}
?>
