<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";

if(!isset($_SESSION['uid']))
	session_defaults();
	
$user = new User('s_users');

if(isset($_POST['submit']))
{
	$user->_checkLogin($_POST['username'],$_POST['password'],isset($_POST['remember'])?1:0);
}	
	
	$username = $_GET['user'];
	$hash = $_GET['confirm'];
	$sql = "select username from s_users where username='".mysql_real_escape_string($username)."' and confirm_hash='".mysql_real_escape_string($hash)."' and is_confirmed='0'";
	if(db_getFirstResult($sql) != "")
	{
		$sql = "update s_users set is_confirmed='1' where username='$username'";
		db_query($sql);
		$_SESSION['feedback'] = "Your account has been successfully confirmed. <a href='$server_root"."resumedb/students'>Click here </a> to Log-in.";	
	}
	else
		$_SESSION['feedback'] = "Error: Either you have already been confirmed, or your confirmation is invalid";
	site_header("Account Confirmation");
	
	include ("$prepath/src/footer.php");	
?>	
