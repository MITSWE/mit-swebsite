<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/includes/validate.js" /></script><!-- include form validatino functions--> 
<?php

if(!isset($_SESSION['uid']))
	session_defaults();
	
$user = new User('s_users');

if(isset($_POST['submit']))
{
	$user->_checkLogin($_POST['username'],$_POST['password'],isset($_POST['remember'])?1:0);
}	
	
require_once("AjaxHandler.class.php");

$ajax = new AjaxHandler();
echo $ajax->getJSCode();
if(isset($_POST['submit']))
{
	//validate, set password
	$pwd1=$_POST['pwd1'];
	$pwd2=$_POST['pwd2'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	
	$validate=true;		//in case AJAX doesn't work.
	if($pwd1!=$pwd2)
	{
		$validate=false;
		$_SESSION['feedback'] = 'Error: Your passwords do not match';
	}
	else if(strlen($pwd1) < 6)
	{
		$validate=false;
		$_SESSION['feedback'] = "Error: Your password must be greater than 6 characters";
	}
	else if(strlen($firstname) == 0)
	{
		$validate=false;
		$_SESSION['feedback'] = "Error: Please completely fill out your first and last name";
	}
	else if(strlen($lastname) == 0)
	{
		$validate=false;
		$_SESSION['feedback'] = "Error: Please completely fill out your first and last name";
	}
	
	$username=$_POST['username'];
	if($user->_userExists($username))
	{
		$validate=false;
		$_SESSION['feedback']="Error: your username already exists. Please use the Forgot password link to retreive your password.";
	}
	
	if(strlen($pwd1)!= 0 && strlen($username)!=0 && $validate)
	{
		$cookie=random_hash();
		$pwd = md5($pwd1);
		$user->_Register($_POST,$pwd,$cookie);
	}
}

		site_header("User Registration");
		echo "<form name='login' method='POST' action='$self' onSubmit='return validateForm()'>";
		echo "<span class='required'>Required fields (*)</span>";
		echo "<table><tr>";
		echo "<td>Name (First/Last):<span class='required'>*</span></td><td><input type='text' name='firstname'> <input type='text' name='lastname'></td></tr>";
		echo "<td>E-mail:<span class='required'>*</span></td><td><input type='text' name='username'> @mit.edu</td></tr>";
		echo "<tr><td>Choose Password:<span class='required'>*</span></td><td><input type='password' id='pwd1' name='pwd1' onBlur=\"".$ajax->bindInline("ValidatePassword",array('pwd1'=>"pwd1",'pwd2'=>"pwd2"),"pwd2")."\"></td></tr>";
		echo "<tr><td>Re-type Password:</td><td><input type='password' id='pwd2' name='pwd2' onKeyUp=\"".$ajax->bindInline("ValidatePassword",array('pwd1'=>"pwd1",'pwd2'=>"pwd2"),"pwd2")."\"> ";
		echo "<div id='results' name='results'> </div>";
		echo "</td></tr>";

		echo "<tr><td> </td><td align='center'><input type='submit' name='submit' value='Register'>";
		echo "</tr></table></form>";

	
	include ("$prepath/src/footer.php");	
?>	
