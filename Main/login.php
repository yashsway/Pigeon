<?php
require_once('init.php');
//Error display
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if (isset($_REQUEST['user_name'])){
    $check_login = new CheckLogin();
    $result = $check_login->init_session($_GET['user_name'], $_GET['pass_word']);
    echo $result;
}
?>
