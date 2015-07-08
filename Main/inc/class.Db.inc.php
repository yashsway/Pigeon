<?php

//notes is my feature
class Db {
    public $databaseConnection;

    public function __construct(){
        $this->databaseConnect();
    }
    public function databaseConnect(){
        if($GLOBALS['appMode']==0){
            //Connection to test database
            $this->databaseConnection = mysqli_connect('localhost','root','');
        }else{
            //Production database
        }

        if(!$this->databaseConnection){
            $output = "Unable to connect to database server.";
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        if(!mysqli_set_charset($this->databaseConnection,'utf8')){
            $output = 'Unable to set database connection encoding.';
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        if(!mysqli_select_db($this->databaseConnection,'pigeonReportsTest')){
            $output = "Unable to locate database.";
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        $output = "Database connection established.";
        //TEST: console msg
        //echo '<script type="text/javascript">console.log("' . $output . '");</script>';
    }

    public function getReportDetails($id){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportName, reportPhone, reportEmail, reportDepartment, reportRequest, reportCustomRequest, reportSummary, reportDetails, reportPriority, reportDate, reportTime, duration, admin_priority, admin_notes, markedForDeletion FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        $result = $ajaxQuery->get_result();
        $json_result = array();

        while($row=$result->fetch_assoc()){
            $assoc_result[] = $row;
        }
        return $assoc_result;
    }

    public function markReport($id,$mark){
        if($mark=="0"){
            $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET markedForDeletion = 0 WHERE reportID = ?');
        }else if($mark=="1"){
            $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET markedForDeletion = 1 WHERE reportID = ?');
        }
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }

    public function insertReport($id,$na,$ph,$em,$dep,$req,$cus,$summ,$det,$pri,$dat,$tim){
        $ajaxQuery = $this->databaseConnection->prepare('INSERT INTO reports (reportID,reportName,reportPhone,reportEmail,reportDepartment,reportRequest,reportCustomRequest,reportSummary,reportDetails,reportPriority,reportDate,reportTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $ajaxQuery->bind_param("isssssssssss",$id,$na,$ph,$em,$dep,$req,$cus,$summ,$det,$pri,$dat,$tim);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }
}
?>
