<?php
class CheckLogin{

    public static $_login_status = false;
    public $_con;


    public function __construct(){
    }

    public function checkLogin($username, $password){
        //$mysqli = $this->mysqli;
        $this->connect();

        $query_1 = $this->_con->prepare('select * from pigeonUsers where username = ? and password = ? LIMIT 1');

        $query_1->bind_param("ss", $username, $password);
        $query_1->execute();

        $result = $query_1->store_result();
        $num_rows = $query_1->num_rows;

        if($num_rows == 1){
            return true;
        }else{
            return false;
        }
    }


    public function connect(){
        $_mode = 0;
        if($_mode == 0){
            //Mac 127.0.0.1
            $this->_con = mysqli_connect('localhost','root','');
        }else{
//            $this->_con = new mysqli("130.113.143.45:3306", "psousa", "B3asl3y", "hcs-starrez-help");	//prod connection - do not use
        }

        if(!$this->_con){
            $output = "Unable to connect to database server.";
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        if(!mysqli_set_charset($this->_con,'utf8')){
            $output = 'Unable to set database connection encoding.';
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        if(!mysqli_select_db($this->_con,'keys')){
            $output = "Unable to locate database keys.";
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }
        $output = "Database connection established to keys.";
        //TEST: console msg
        //echo '<script type="text/javascript">console.log("' . $output . '");</script>';
    }


    public function init_session($user_name, $pass_word){

        $hash_pass = sha1($pass_word);
        $test_login = $this->checkLogin($user_name, $hash_pass);

        if ($test_login){
            session_start();
            $_SESSION['hcs_helpDesk_cookie'] = time('now') + 25000;
            $_SESSION['user_name'] = $user_name;
            //header("Location: http://localhost/HCSProjects/Pigeon/Main/index.html.php");
            //exit;
            return "success";
        }else{
             return "fail";
        }

    }

    public static function check_session_age(){
        if (time('now') > $_SESSION['hcs_helpDesk_cookie']){
            header("Location: landing-page.html");
            exit;
        }else{
            CheckLogin::extend_session(3);
        }
    }

    public static function extend_session($seconds){
        $_SESSION['hcs_helpDesk_cookie'] += $seconds;
    }



    public function kill_session(){
        $_SESSION = array();
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
            session_destroy();
    }
}
?>
