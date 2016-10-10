<?php
session_start(); 
include "src/header.php";
include "src/database.php";
include "src/functions.php";

?>

<?php
		
	if(isset($_GET['logout']))
	{
		session_defaults();
		echo "<h2>You have been logged out</h2>";
		echo "<META http-equiv=\"refresh\" content=\"2; URL=./\""; 
	}
	if(isset($_POST['submit']))
	{
		$valid=true;
		if($_POST['username']=='')
		{
			$_SESSION['ERROR'] = "Please enter a valid Athena Username";	
			$valid = false;
		}
		
		if($valid){}
	}
	
		
	echo "<h1>Student Registration</h1>";	
	echo "<form name='login' method='POST' action='$self'>";

	echo "<table><tr>";
	echo "<td>E-mail:</td><td><input type='text' name='username'> @mit.edu</td></tr>";
	echo "<tr><td> </td><td align='center'><input type='submit' name='submit' value='Submit'></td>";
	echo "</tr></table></form>";

	include ("src/footer.php");	
?>	
