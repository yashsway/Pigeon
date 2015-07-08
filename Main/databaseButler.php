<?php
function def(){
    //The following PHP code needs to be re-executed as this is a different instance
    require_once('init.php');
    require_once('functions.php');
}
function reportLookup(){
    def();
    //Establish a new connection
    $repLookCon = new Db();
    //Get the report ID from the URL (src: AJAX request)
    $query_ID = $_GET['queryID'];
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
    $query_ID = $_GET['queryID'];
    //Run the markReport function from the DB class (Send it both the report ID & the addition parameter to specify a mark or an un-mark)
    $result = $repMarkCon->markReport($query_ID,$_GET['reqParam']);
    echo $result;
}
function reportInsert(){
    def();
    //Establish a new connection
    $repInsCon = new Db();
    //Get the report ID from the URL (src: AJAX request)
    //TODO: query ID has to reference a hashing function
    //$query_ID = $_GET['queryID'];
    //Run the insertReport function from the DB class (Send it the report ID & the JSON data of the submitted form)
    $result = $repInsCon->insertReport($_POST['id'],$_POST['na'],$_POST['ph'],$_POST['em'],$_POST['dep'],$_POST['req'],$_POST['cus'],$_POST['summ'],$_POST['det'],$_POST['pri'],$_POST['dat'],$_POST['tim']);
    //Refresh report list
    //reportEntry();
    echo $result;
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
    }else if($_POST['reqType']==2){
        //echo json_encode(array("name"=>$_POST['na'],"reqType"=>$_POST['reqType']));
        reportInsert();
    }
}
?>
