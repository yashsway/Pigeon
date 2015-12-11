<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once("inc/class.McAuth.inc.php");

$token = $_GET['token'];
$authz = $_GET['authz']; //get good token param from successful macid login

//echo $_GET['token'];
//echo $_GET['authz'];

$private_key = "VL2PMKB0jyo2O88gkgF3vEFg";

$unencrypt = McAuth::getMcAuthParameters($authz, $private_key);//decrypt token
$unencrypt_2 = McAuth::getMcAuthParameters($token, $private_key);//decrypt token

//echo "<pre>";
//var_dump($unencrypt_2);
//echo "</pre>";

//if user macid is on my list, send them over
$attemptingUser = $unencrypt_2[4];
$validAdmins = array("kurucr","prancho");
$validStaff = array("gopalay");
if(in_array($attemptingUser,$validAdmins)){
    //Create session cookie
    session_start();
    $_SESSION['pigeon_admin'] = time('now') + 25000;
    $_SESSION['user_name'] = $attemptingUser;
    //Redirect to main app
    header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/index.html.php");
}else if(in_array($attemptingUser,$validStaff)){
    //Create session cookie
    session_start();
    $_SESSION['pigeon_staff'] = time('now') + 25000;
    $_SESSION['user_name'] = $attemptingUser;
    //Redirect back to welcome page where report buttons will be enabled.
    //global $genStaff = true;
    header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/staffPortal.html.php");
}else{
    //Redirect to unauth page indicating reason for login denial
    header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/unauth.php");
    die();
}
?>