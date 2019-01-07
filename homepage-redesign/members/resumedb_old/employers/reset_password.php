<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];
?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/includes/validate.js" /></script><!-- include form validatino functions--> 
<?php
if(!isset($_SESSION['uid']))
	session_defaults();
	
if(isset($_POST['submit']))
{
	$username=$_POST['username'];
	if(!is_employer($username))
		$_SESSION['feedback'] = "Sorry, we don't recognize that e-mail address. <br>Please ".
						"<a href='registration.php'>click here </a>to register.";
	else
	{
		$subject = "Reset your SWE Resume database password";
		$firstname = db_getFirstResult("select firstname from e_users where username='$username'");
		if(empty($firstname))
			$firstname = "SWE Database User";
		// Create new confirmation has
		$hash = random_hash();
		$sql = "update e_users set confirm_hash='$hash' where username='$username' limit 1";
		db_query($sql);
		
		$reset_link = "$server_root$self?username=$username&confirm=$hash";
			
		$message = "Dear $firstname, \n\n".
				"You have requested for your password to be reset. Please visit the following link to reset your password:".
				"$reset_link \n\n".
				"Thank You,\n\nSWE Resume Interface";
		$to = $username;
		$header = "From: no-reply@swe.scripts.mit.edu \n\r";
		mail($to,$subject,$message,$header);		
		$_SESSION['feedback'] = "Thanks, $firstname. We have sent you an e-mail with instructions to reset your password. Please check your inbox soon";
//		$_SESSION['feedback'] .= "<br>$reset_link";		
	}
}	

if(isset($_POST['reset_password']))
{
	//validate, set password
	$pwd1=$_POST['pwd1'];
	$pwd2=$_POST['pwd2'];
	$username=$_POST['username'];
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
	else if(strlen($pwd1)!= 0 && strlen($username)!=0 && $validate==true)
	{
		$pwd = md5($pwd1);
		$sql_arr['password']=$pwd;
		$sql_arr['is_confirmed']='1';
		$sql_arr['confirm_hash']=random_hash();
		db_update("e_users",$sql_arr,"username='$username'");
		$_SESSION['feedback'] = "Your password has been updated.<br><a href='index.php'>Click here </a>to Log in";
	}
	else
	{
		$_SESSION['feedback'] = "Sorry, there was a problem with resetting your password. Please contact the webmaster";
	}
	
}


if(isset($_GET['confirm']) && isset($_GET['username']))
{
	require_once("AjaxHandler.class.php");

	$ajax = new AjaxHandler();
	echo $ajax->getJSCode();
	
	$hash = $_GET['confirm'];
	$username = $_GET['username'];
	$sql = "select username from e_users where username='$username' and confirm_hash='$hash'";
	$username=db_getFirstResult($sql);
	if($username == "")
	{
		$_SESSION['feedback'] = "Error: you have followed an invalid confirmation link. <A HREF='index.php'>Click here</a> to Log in";
		site_header();
	}
	else
	{
		$page_title = "Reset Password";
		site_header();
		echo "<h4>Reset Password</h4>";?>
		<form name='reset' method='POST' action="<?php echo $self;?>" onSubmit='return validateForm()'>
		<?php
		echo "<table><tr>";
		echo "<td>E-mail:</td><td><input type='text' disabled value='$username'><input type='hidden' name='username' value='$username'></td></tr>";
		echo "<input type='hidden' name='hash' value='$hash'></td></tr>";
		echo "<tr><td>New Password:<span class='required'>*</span></td><td><input type='password' id='pwd1' name='pwd1' onBlur=\"".$ajax->bindInline("ValidatePassword",array('pwd1'=>"pwd1",'pwd2'=>"pwd2"),"pwd2")."\"></td></tr>";
		echo "<tr><td>Re-type Password:</td><td><input type='password' id='pwd2' name='pwd2' onKeyUp=\"".$ajax->bindInline("ValidatePassword",array('pwd1'=>"pwd1",'pwd2'=>"pwd2"),"pwd2")."\"> ";
		echo "<div id='results' name='results'> </div>";
		echo "</td></tr>";

		echo "<tr><td> </td><td align='center'><input type='submit' name='reset_password' value='Reset Password'>";
		echo "</tr></table></form>";
			
	}	
	
}
else
{
	$page_title = "Reset Password";
	site_header();
	echo "Please enter your e-mail address below to reset your password.<br><br>";
	echo "<form name='login' method='POST' action='$self'>";
	echo "<table><tr>";
	echo "<td>E-mail:</td><td><input type='text' name='username'></td></tr>";
	echo "<tr><td> </td><td align='center'><input type='submit' name='submit' value='Reset Password'></td>";
	echo "</tr></table></form>";
}
	
	
	include ("$prepath/src/footer.php");	
?>	
