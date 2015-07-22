<?php
require_once('init.php');
//Error display
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if (isset($_REQUEST['user_name'])){
    $check_login = new CheckLogin();
    $result = $check_login->init_session($_REQUEST['user_name'], $_REQUEST['pass_word']);
    echo $result;
}
?>
