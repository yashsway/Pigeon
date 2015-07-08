<?php
class Db {
    public $databaseConnection;

    public function __construct(){
        $this->databaseConnect();
    }
    public function databaseConnect(){
        if($GLOBALS['appMode']==0){
        $this->databaseConnection = mysqli_connect('localhost','root','');
        }else{
            //Production database
        }

        if(!$this->databaseConnection){
            $output = "Unable to connect to database server.";
            include 'errorMessage.html.php';
            exit();
        }
        if(!mysqli_set_charset($this->databaseConnection,'utf8')){
            $output = 'Unable to set database connection encoding.';
            include 'errorMessage.html.php';
            exit();
        }
        if(!mysqli_select_db($this->databaseConnection,'pigeonReportsTest')){
            $output = "Unable to locate database.";
            include 'errorMessage.html.php';
            exit();
        }
        $output = "Database connection established.";
        //echo '<script>console.log("Connection Success!")</script>';
        echo '<script type="text/javascript">console.log("' . $output . '");</script>';
        //include 'errorMessage.html.php';
        //return $databaseConnection;
    }
}
?>
