<?php
class Db {
    public $databaseConnection;
    //Database Settings
    public $database = 'pigeon';
    public $servAddress = 'localhost';
    public $user = 'root';
    public $pass = '';

    public function __construct(){
        $this->databaseConnect();
    }
    public function databaseConnect(){
        global $database;
        global $servAddress;
        global $user;
        global $pass;

        if($GLOBALS['appMode']==0){
            //Connection to test database
            $this->databaseConnection = mysqli_connect($servAddress,$user,$pass);
        }else if($GLOBALS['appMode']==1){
            //Production database
            $this->databaseConnection = mysqli_connect("130.113.143.45:3306", "yash", "y@$#mysQl!", "pigeon");
        }

        /*if(!$this->databaseConnection){
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
        if(!mysqli_select_db($this->databaseConnection,$database)){
            $output = "Unable to locate database.";
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }*/
        $output = "Database connection established.";
        //TEST: console msg
        //echo '<script type="text/javascript">console.log("' . $output . '");</script>';
    }

    public function getReportDetails($id){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportName, reportPhone, reportEmail, reportDepartment, reportRequest, reportCustomRequest, reportSummary, reportDetails, reportPriority, reportDate, reportTime, duration, admin_priority, admin_notes, markedForDeletion, resolved, dateResolved, dateEdited FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"Query fail");
            return $error;
        }

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
        //Make query
        $ajaxQuery = $this->databaseConnection->prepare('INSERT INTO reports (reportID,reportName,reportPhone,reportEmail,reportDepartment,reportRequest,reportCustomRequest,reportSummary,reportDetails,reportPriority,reportDate,reportTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $ajaxQuery->bind_param("isssssssssss",$id,$na,$ph,$em,$dep,$req,$cus,$summ,$det,$pri,$dat,$tim);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }

    public function editReportView($id){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportSummary, reportName, reportPhone, reportEmail, reportDate, reportTime, duration, admin_priority, admin_notes FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"Query fail");
            return $error;
        }

        $result = $ajaxQuery->get_result();
        $json_result = array();

        while($row=$result->fetch_assoc()){
            $assoc_result[] = $row;
        }
        return $assoc_result;
    }

    public function editReportUpdate($id,$summ,$na,$ph,$em,$dat,$tim,$admPr,$dur,$nte,$edtDate){
        //Make query
        $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET reportSummary = ?, reportName = ?, reportPhone = ?, reportEmail = ?, reportDate = ?, reportTime = ?, duration = ?, admin_priority = ?, admin_notes = ?, dateEdited = ? WHERE reportID = ?');
        $ajaxQuery->bind_param("ssssssisssi",$summ,$na,$ph,$em,$dat,$tim,$dur,$admPr,$nte,$edtDate,$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }

    public function resolveReport($id,$res,$dat){
        if($res=="0"){
            $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET resolved = 0, dateResolved = ? WHERE reportID = ?');
        }else if($res=="1"){
            $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET resolved = 1, dateResolved = ? WHERE reportID = ?');
        }

        $ajaxQuery->bind_param("si",$dat,$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }

    public function deleteReport($id){
        $ajaxQuery = $this->databaseConnection->prepare('DELETE FROM reports WHERE reportID = ? LIMIT 1');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }
}
?>
