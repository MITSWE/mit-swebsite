<?php
session_start(); 
$prepath="";
include "src/database.php";
include "src/functions.php";
$self = $_SERVER['PHP_SELF'];
if(!isset($_SESSION['uid']))
	session_defaults();
	
$user = new User('s_users');

if(isset($_POST['submit']))
{
	$user->_checkLogin($_POST['username'],$_POST['password']);
}	
$page_title = "Resume Database";
site_header();
if(isset($_GET['logout']))
{
	session_defaults();
	echo "<h2>You have been logged out</h2>";
	echo "Redirecting....<br><br>";
	echo "<META http-equiv=\"refresh\" content=\"1; URL=$self\""; 
}



?>

<p><h1><img src="/images/expand.gif">&nbsp;&nbsp;<a href="students/">For Students</a></h1></p>

<p><h1><img src="/images/expand.gif">&nbsp;&nbsp;<a href="employers/">For Employers</a></h1></p>

<p><h4>Welcome to the MIT Society of Women Engineers Resume Database. To get started, click on one of the links above.</h4></p>


<p></div>

<?php include ('../footer.php');	
?>	
