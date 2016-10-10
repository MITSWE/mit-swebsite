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

$page_title = "Account Confirmation";
	
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
	site_header();
	include ("$prepath/src/footer.php");
	exit;
	
}
if(isset($_GET['username']) && isset($_GET['confirm']))
{	
	$username = $_GET['username'];
	$hash = $_GET['confirm'];
	$sql = "select username from e_users where username='".mysql_real_escape_string($username)."' and confirm_hash='".mysql_real_escape_string($hash)."' and is_confirmed='0'";
	if(db_getFirstResult($sql) != "")
	{
//		$sql = "update s_users set is_confirmed='1' where username='$username'";
//		db_query($sql);

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
		}
		site_header();
		echo "Your account has been successfully confirmed. Please set your password below<br>";?>
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
	else
	{
		$_SESSION['feedback'] = "Error: Either you have already been confirmed, or your confirmation is invalid";
		site_header();
	}
}
	
include ("$prepath/src/footer.php");	
?>	
