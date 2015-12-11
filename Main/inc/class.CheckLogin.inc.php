<?php
class CheckLogin{
    //Database Settings
    public $database = "hcs-starrez-help";
    public $servAddress = 'localhost';
    public $user = 'root';
    public $pass = '';

    public static $_login_status = false;
    public $_con;


    public function __construct(){
    }

    public function checkLogin($username, $password){
        //$mysqli = $this->mysqli;
        $this->connect();

        $query_1 = $this->_con->prepare('select * from user where username = ? and password = ? LIMIT 1');

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
        global $servAddress;
        global $user;
        global $pass;
        global $database;

        if($GLOBALS['appMode']==0){
            //Mac 127.0.0.1
            $this->_con = mysqli_connect($servAddress,$user,$pass);
        }else if($GLOBALS['appMode']==1){
            $this->_con = mysqli_connect("130.113.143.45","radetest", "c0c2c0l2", "hcs-starrez-help","3306");
        }

       /* if(!$this->_con){
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
        if(!mysqli_select_db($this->_con,'hcs-starrez-help')){
            $output = "Unable to locate database " . $GLOBALS['appMode'];
            //TEST: console msg
            echo '<script type="text/javascript">console.log("' . $output . '");</script>';
            exit();
        }*/
        $output = "Database connection established.";
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
            //header("Location: index.html.php");
            //exit;
            return "success";
        }else{
             return "fail";
        }

    }

    public static function check_session_age($name){
        if (time('now') > $_SESSION[$name]){
            header("Location: landing-page.html.php");
            exit;
        }else{
            CheckLogin::extend_session($name,3);
        }
    }

    public static function extend_session($name,$seconds){
        $_SESSION[$name] += $seconds;
    }

    public function kill_session(){
        //Set the SESSION equal to an empty array
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
