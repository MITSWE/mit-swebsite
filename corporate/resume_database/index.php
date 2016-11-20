<?php
session_start(); 
$prepath="../../members";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

// $_SESSION['logged'] = False;

if(!$_SESSION['logged'])
{
	session_defaults();
	$user = new User('e_users');
}

if(isset($_POST['submit']))
{
	$user->_checkLogin($_POST['username'],$_POST['password']);
}	
	
$page_title = "Login";

if(isset($_POST['submit']))
{
	if(!$_SESSION['logged'])
	{
		//$_SESSION['feedback'] = "You've entered the wrong Username or Password. Please try again.";
		//echo "<table>";
		echo "Incorrect password.  Please try again.";
		//echo "</table>";
	}
}	

site_header();

?>
<div style="height:15vh"></div>
<?

if($_SESSION['logged'])
{
?>
	<meta http-equiv="refresh" content="0; url=http://swe.mit.edu/corporate/resume_database/search.php"/>
<?php
}
else
{	
	echo "<h2>Employee Portal Login:</h2>";
	echo "<form name='login' method='POST' action='$self' style='margin-left:5vw;''>";
	echo "<table>";
	echo "<tr><td>Username:</td><td><input type='text' name='username'></td></tr>";
	echo "<tr><td>Password:</td><td><input type='password' name='password'> <input type='submit' name='submit' value='Submit'></td></tr>";
	echo "</table></form>";
}

	include ("$prepath/src/footer.php");	
?>	
