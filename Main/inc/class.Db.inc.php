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
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);

        if($GLOBALS['appMode']==0){
            //Connection to test database
            $this->databaseConnection = mysqli_connect($servAddress,$user,$pass);
        }else if($GLOBALS['appMode']==1){
            //Production database
            $this->databaseConnection = mysqli_connect("130.113.143.45", "yash", "y@$#mysQl!", "pigeon", "3306");
        }
    }

    public function authenticate($auth){
        $auth = password_hash($auth,PASSWORD_BCRYPT);
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

    //CHANGES: removed reportDetails
    public function getReportDetails($id){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportName, reportPhone, reportEmail, reportDepartment, reportRequest, reportCustomRequest, reportSummary, reportPriority, reportDate, reportTime, duration, admin_priority, admin_notes, markedForDeletion, resolved, dateResolved, dateEdited, timesViewed FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"fail");
            return $error;
        }

        $ajaxQuery->bind_result($na,$ph,$em,$dep,$req,$cus,$summ,$pr,$dat,$tim,$dur,$adm_pr,$adm_nte,$mrk,$res,$dat_res,$dat_ed,$times);
        while($ajaxQuery->fetch()){
            $assoc_result[0]['reportName'] = $na;
            $assoc_result[0]['reportPhone'] = $ph;
            $assoc_result[0]['reportEmail'] = $em;
            $assoc_result[0]['reportDepartment'] = $dep;
            $assoc_result[0]['reportRequest'] = $req;
            $assoc_result[0]['reportCustomRequest'] = $cus;
            $assoc_result[0]['reportSummary'] = $summ;
            //$assoc_result[0]['reportDetails'] = $det;
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
            $error[0] = array("error"=>"fail");
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
            return "ok";
        }else{
            return "fail";
        }
    }

    //CHANGES: removed reportDetails
    public function insertReport($id,$na,$ph,$em,$dep,$req,$cus,$summ,$pri,$dat,$tim){
        //Make query
        $ajaxQuery = $this->databaseConnection->prepare('INSERT INTO reports (reportID,reportName,reportPhone,reportEmail,reportDepartment,reportRequest,reportCustomRequest,reportSummary,reportPriority,reportDate,reportTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $ajaxQuery->bind_param("issssssssss",$id,$na,$ph,$em,$dep,$req,$cus,$summ,$pri,$dat,$tim);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "ok";
        }else{
            return "fail";
        }
    }

    public function editReportView($id){
        $ajaxQuery = $this->databaseConnection->prepare('SELECT reportSummary, reportName, reportPhone, reportEmail, reportDate, reportTime, duration, admin_priority, admin_notes FROM reports WHERE reportID = ?');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        //Error catch
        if(!$ajaxQuery){
            $error[0] = array("error"=>"fail");
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
            return "ok";
        }else{
            return "fail";
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
            return "ok";
        }else{
            return "fail";
        }
    }

    public function deleteReport($id){
        $ajaxQuery = $this->databaseConnection->prepare('DELETE FROM reports WHERE reportID = ? LIMIT 1');
        $ajaxQuery->bind_param("i",$id);
        $ajaxQuery->execute();

        if($ajaxQuery){
            return "ok";
        }else{
            return "fail";
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
            $error[0] = array("error"=>"fail");
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

    public function getStatistics(){
        $result = mysqli_query($this->databaseConnection,'SELECT count(id) as totalReports FROM reports');
        $row = mysqli_fetch_array($result);
        $assoc_result['totalReports'] = $row['totalReports'];
        $result = mysqli_query($this->databaseConnection,'SELECT count(resolved) as totalResolved FROM reports WHERE resolved = 1');
        $row = mysqli_fetch_array($result);
        $assoc_result['totalResolved'] = $row['totalResolved'];
        if(!$result){
            return mysqli_error($databaseConnection);
        }
        return $assoc_result;
    }

    public function getAllReports(){
        $result = mysqli_query($this->databaseConnection,'select reportID,reportSummary,resolved,tag,reportDate,reportTime,reportPriority,admin_priority from reports');
        $i = 0;
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $assoc_result[$i] = $row;
            $i++;
        }
        if(!$result){
            return mysqli_error($databaseConnection);
        }
        return $assoc_result;
    }
    //User Permissions: Edit user permission
    public function edit_user_permission($macid, $level){
        $query = $this->databaseConnection->prepare('select * from users where macid = ? limit 1');
        //var_dump( $query);

        $query->bind_param("s", $macid);
        $query->execute();
        $user_result = $query->store_result();
        $user_token = array();
        $row_count = $query->num_rows;

        if ($row_count == 1){
            $query = $this->databaseConnection->prepare('update users set level = ? where macid = ?');
        //var_dump( $query);

            $query->bind_param("is", $level, $macid);
            $query->execute();

            if ($query){
                return 1;
            }else{
                return 0;
            }
        }
    }

    //User Permissions: Delete User
    public function del_user($macid){
        $query = $this->databaseConnection->prepare('select * from users where macid = ? limit 1');
        //var_dump( $query);

        $query->bind_param("s", $macid);
        $query->execute();
        $user_result = $query->store_result();
        $user_token = array();
        $row_count = $query->num_rows;

        if ($row_count == 1){
            $query = $this->databaseConnection->prepare('delete from users where macid = ?');
        //var_dump( $query);

            $query->bind_param("s", $macid);
            $query->execute();

            if ($query){
                return 1;
            }else{
                return 0;
            }
        }
    }

    //User Permissions: Add User
    public function add_user($macid, $level){
        $query = $this->databaseConnection->prepare('select * from users where macid = ? limit 1');
        //var_dump( $query);

        $query->bind_param("s", $macid);
        $query->execute();
        $user_result = $query->store_result();
        $user_token = array();
        $row_count = $query->num_rows;

        if ($row_count == 1){
            $query = $this->databaseConnection->prepare('update users set level = ? where macid = ?');
        //var_dump( $query);

            $query->bind_param("is", $level, $macid);
            $query->execute();

            if ($query){
                return 1;
            }else{
                return 0;
            }
        }else{
            $query = $this->databaseConnection->prepare('insert into users (macid, level) values (?,?)');
        //var_dump( $query);

            $query->bind_param("si", $macid, $level);
            $query->execute();

            if ($query){
                return 1;
            }else{
                return 0;
            }
        }
    }

    //User Permissions: Get user details
    function get_user($macid){
        //0 = no access
        //1 = user
        //2 = admin

        $query = $this->databaseConnection->prepare('select * from users where macid = ? limit 1');
        //var_dump( $query);

        $query->bind_param("s", $macid);
        $query->execute();
        $user_result = $query->store_result();
        $user_token = array();
        $row_count = $query->num_rows;

        if ($row_count == 0){
            return 0;
            exit;
        }else{
            $query = $this->databaseConnection->prepare('select * from users where macid = ? LIMIT 1');

            $query->bind_param("s", $macid);
            $query->execute();
            $result = $query->get_result();

            while($row = $result->fetch_assoc()){
                $user_token[0] = $row['macid'];
                $user_token[1] = $row['level'];
            }

            if ($user_token[1] == 2){
                return 2;
                exit;
            }else if($user_token[1] == 1){
                return 1;
                exit;
            }else{
                return 0;
                exit;
            }
        }
    }

    //User Permissions: get all users
    function get_all_users(){
        $query = $this->databaseConnection->prepare('select * from users');

            $query->execute();
            $result = $query->get_result();
            return $result;
            exit;
            while($row = $result->fetch_assoc()){
                $user_token[0] = $row['macid'];
                $user_token[1] = $row['level'];
            }

    }
}
?>
