<?php
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
        //Clean the user-input
        $na = mysqli_real_escape_string($this->databaseConnection,$na);
        $ph = mysqli_real_escape_string($this->databaseConnection,$ph);
        $em = mysqli_real_escape_string($this->databaseConnection,$em);
        $dep = mysqli_real_escape_string($this->databaseConnection,$dep);
        $req = mysqli_real_escape_string($this->databaseConnection,$req);
        $cus = mysqli_real_escape_string($this->databaseConnection,$cus);
        $summ = mysqli_real_escape_string($this->databaseConnection,$summ);
        $det = mysqli_real_escape_string($this->databaseConnection,$det);
        $pri = mysqli_real_escape_string($this->databaseConnection,$pri);
        $dat = mysqli_real_escape_string($this->databaseConnection,$dat);
        $tim = mysqli_real_escape_string($this->databaseConnection,$tim);
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

    public function editReportUpdate($id,$summ,$na,$ph,$em,$dat,$tim,$admPr,$dur,$nte){
        //Clean the user input
        $summ = mysqli_real_escape_string($this->databaseConnection,$summ);
        $na = mysqli_real_escape_string($this->databaseConnection,$na);
        $ph = mysqli_real_escape_string($this->databaseConnection,$ph);
        $em = mysqli_real_escape_string($this->databaseConnection,$em);
        $dat = mysqli_real_escape_string($this->databaseConnection,$dat);
        $tim = mysqli_real_escape_string($this->databaseConnection,$tim);
        $admPr = mysqli_real_escape_string($this->databaseConnection,$admPr);
        $nte = mysqli_real_escape_string($this->databaseConnection,$nte);
        //Make query
        $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET reportSummary = ?, reportName = ?, reportPhone = ?, reportEmail = ?, reportDate = ?, reportTime = ?, duration = ?, admin_priority = ?, admin_notes = ? WHERE reportID = ?');
        $ajaxQuery->bind_param("ssssssissi",$summ,$na,$ph,$em,$dat,$tim,$dur,$admPr,$nte,$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "Query ok";
        }else{
            return "Query fail";
        }
    }
}
?>
