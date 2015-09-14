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
		//Error display
		//ini_set('display_errors',1);
		//ini_set('display_startup_errors',1);
		//error_reporting(-1);
		
        if($GLOBALS['appMode']==0){
            //Connection to test database
            $this->databaseConnection = mysqli_connect($servAddress,$user,$pass);
        }else if($GLOBALS['appMode']==1){
            //Production database
            $this->databaseConnection = mysqli_connect("130.113.143.45", "yash", "y@$#mysQl!", "pigeon", "3306");
        }
    }

    public function authenticate($auth){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT clientName FROM authkeys WHERE clientKey = ?');
        $ajaxQuery->bind_param("s",$auth);
        $ajaxQuery->execute();

        if(!$ajaxQuery){
            return false;
        }

        $ajaxQuery->bind_result($na);
        while($ajaxQuery->fetch()){
            $assoc_result[0]['clientName'] = $na;
        }

        if($assoc_result[0]['clientName']=="Staff"){
            return "Auth ok";
        }else{
            return "Auth fail";
        }
    }

    public function getReportDetails($id){		
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportName, reportPhone, reportEmail, reportDepartment, reportRequest, reportCustomRequest, reportSummary, reportDetails, reportPriority, reportDate, reportTime, duration, admin_priority, admin_notes, markedForDeletion, resolved, dateResolved, dateEdited, timesViewed FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"Query fail");
            return $error;
        }
		
        $ajaxQuery->bind_result($na,$ph,$em,$dep,$req,$cus,$summ,$det,$pr,$dat,$tim,$dur,$adm_pr,$adm_nte,$mrk,$res,$dat_res,$dat_ed,$times);
        while($ajaxQuery->fetch()){
            $assoc_result[0]['reportName'] = $na;
			$assoc_result[0]['reportPhone'] = $ph;
			$assoc_result[0]['reportEmail'] = $em;
			$assoc_result[0]['reportDepartment'] = $dep;
			$assoc_result[0]['reportRequest'] = $req;
			$assoc_result[0]['reportCustomRequest'] = $cus;
			$assoc_result[0]['reportSummary'] = $summ;
			$assoc_result[0]['reportDetails'] = $det;
			$assoc_result[0]['reportPriority'] = $pr;
			$assoc_result[0]['reportDate'] = $dat;
			$assoc_result[0]['reportTime'] = $tim;
			$assoc_result[0]['duration'] = $dur;
			$assoc_result[0]['admin_priority'] = $adm_pr;
			$assoc_result[0]['admin_notes'] = $adm_nte;
			$assoc_result[0]['markedForDeletion'] = $mrk;
			$assoc_result[0]['resolved'] = $res;
			$assoc_result[0]['dateResolved'] = $dat_res;
			$assoc_result[0]['dateEdited'] = $dat_ed;
			$assoc_result[0]['timesViewed'] = $times;
        }

        $views = $assoc_result[0]["timesViewed"] + 1;
        $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET timesViewed = ? WHERE reportID = ?');
        $ajaxQuery->bind_param("ii",$views,$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"Query fail");
            return $error;
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

        $ajaxQuery->bind_result($summ,$na,$ph,$em,$dat,$tim,$dur,$adm_pr,$adm_nte);

        while($ajaxQuery->fetch()){
			$assoc_result[0]['reportSummary'] = $summ;
            $assoc_result[0]['reportName'] = $na;
			$assoc_result[0]['reportPhone'] = $ph;
			$assoc_result[0]['reportEmail'] = $em;
			$assoc_result[0]['reportDate'] = $dat;
			$assoc_result[0]['reportTime'] = $tim;
			$assoc_result[0]['duration'] = $dur;
			$assoc_result[0]['admin_priority'] = $adm_pr;
			$assoc_result[0]['admin_notes'] = $adm_nte;
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

    public function tagUpdate($id){
        $val = -1;

        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportPriority, reportDate, reportTime, duration, admin_priority, resolved, dateEdited, timesViewed, tag FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        $ajaxQuery->bind_result($pr,$dat,$time,$dur,$adm_pr,$res,$datEd,$viewed,$tag);

        while($ajaxQuery->fetch()){
            $assoc_result[0]['reportPriority'] = $pr;
			$assoc_result[0]['reportDate'] = $dat;
			$assoc_result[0]['reportTime'] = $time;
			$assoc_result[0]['duration'] = $dur;
			$assoc_result[0]['admin_priority'] = $adm_pr;
			$assoc_result[0]['resolved'] = $res;
			$assoc_result[0]['dateEdited'] = $datEd;
			$assoc_result[0]['timesViewed'] = $viewed;
			$assoc_result[0]['tag'] = $tag;
        }

        $val = tagGenerator($assoc_result);

        $ajaxQuery = $this->databaseConnection->prepare('UPDATE reports SET tag = ? WHERE reportID = ?');
        $ajaxQuery->bind_param("ii",$val,$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"Query fail");
            return $error;
        }
		
        $data[0] = array("tag"=>$val);
        return $data;
    }

    public function checkReport($id){		
        $ajaxQuery = $this->databaseConnection->prepare('SELECT resolved, tag FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();
		
		$ajaxQuery->bind_result($res,$tag);
		
        $assoc_result[0]['tag'] = -1;
		
		while($ajaxQuery->fetch()){
			$assoc_result[0]['resolved'] =  $res;
			$assoc_result[0]['tag'] = $tag;
		}
		
        if($assoc_result[0]['resolved']==1){
            return "Resolved.";
        }else{
            switch($assoc_result[0]['tag']){
                case 0:
                    return "Received & Assessing.";
                case 1:
                    return "Received.";
                case 2:
                    return "Assessed. Resolving Soon.";
                case 3:
                    return "Resolving Today.";
                case 4:
                    return "Contact Us Immediately @ ext. 24830/20866";
                case 5:
                    return "Received & Assessing.";
                default:
                    return "Request Not Found.";
            }
        }
    }

    public function totalReports(){
        $result = mysqli_query($this->databaseConnection,'SELECT count(id) as totalReports FROM reports');
        $row = mysqli_fetch_array($result);
        if(!$result){
            return mysqli_error($databaseConnection);
        }
        return $row;
    }
}
?>
