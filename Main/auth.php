<?php

	//delete above to re-auth
require_once("inc/class.McAuth.inc.php");


if (1==1){
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
}
$token = $_GET['token'];
$authz = $_GET['authz']; //get good token param from successful macid login


//echo $_GET['token'];
//echo $_GET['authz'];

$private_key = "VL2PMKB0jyo2O88gkgF3vEFg";//

$unencrypt = McAuth::getMcAuthParameters($authz, $private_key);//decrypt token
$unencrypt_2 = McAuth::getMcAuthParameters($token, $private_key);//decrypt token



// echo "<pre>";
// 	var_dump($unencrypt_2);
// echo "</pre>";
//
// echo "<pre>";
// 	var_dump($unencrypt);
// echo "</pre>";
//$unencrypt_2 = McAuth::getMcAuthParameters($token, $private_key);//decrypt token

//if user macid in on my list, send them over
$attemptingUser = $unencrypt_2[4];
$validUsers = array("gopalay","kurucr","prancho");
if(in_array($attemptingUser,$validUsers)){
	//Create session cookie
	session_start();
	$_SESSION['hcs_helpDesk_cookie'] = time('now') + 25000;
	$_SESSION['user_name'] = $attemptingUser;
	//Redirect to main app
	header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/index.html.php");
}else{
	//Redirect to unauth page indicating reason for login denial
	header("Location: http://hcs.mcmaster.ca/apps/pigeon/Main/unauth.php");
	die();
}
?>
