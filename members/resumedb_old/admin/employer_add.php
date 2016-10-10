<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_admin_page($_SERVER['SCRIPT_NAME']);
// look up user

$page_title = "Employer Admin";

?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/validate.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 
<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>

<script type="text/javascript">

function getCheckBoxes()
{
	
	var checked_values = "";
	
	for(i=0;i<document.myForm.elements.length;i++)
	{
		if(document.myForm.elements[i].type=="checkbox")
		{
			if(document.myForm.elements[i].checked == true)
				checked_values += document.myForm.elements[i].value + ",";
		}	
	}
	document.myForm.checked_values.value=checked_values;
}

function setDate(obj,time)
{	//mktime(h,m,sec,Month,Day,Year)
	//date("Y-m-d H:i",mktime(substr($row['RSVPTime'],0,2),substr($row['RSVPTime'],2,2),0,substr($row['RSVPDate'],4,2),substr($row['RSVPDate'],6,2),substr($row['RSVPDate'],0,4)));
	if(time=='6')
		date = "<?php echo date('m/d/Y',mktime(00,00,0,date(m)+6,date(d),date(Y))); ?>";
	else if(time=='12')
		date = "<?php echo date('m/d/Y',mktime(00,00,0,date(m),date(d),date(Y)+1)); ?>";
	obj.value=date;
}
</script>

<script language="JavaScript" ID="jscal1x">
	var cal = new CalendarPopup("cal1");
</script>

<?
function print_input_date($name,$value,$default,$cal_name="cal")
{
	//Name is the name of the input field to be passed on when the form is submmitted
	//value is the initial value that the field is populated with (usually we get this from the DB, if loading an event from the DB)
	//default is the value to be populated if $value is blank or null (during implementation, this is usually set to today's date)
	?>
	<input readonly type='text'
	name='<?php echo $name?>' size='10' value="<?php echo form_fill_value($value,'',$default); ?>" 
	style="background-color:#F5F5DC" 
	onClick="<?php echo $cal_name?>.select(document.forms['myForm'].<?php echo $name?>,
	'anchor.<?php echo $name?>','MM/dd/yyyy'); return false;" 
	onkeyDown="<?php echo $cal_name?>.select(document.forms['myForm'].<?php echo $name?>,
	'anchor.<?php echo $name?>','MM/dd/yyyy'); return false;" AUTOCOMPLETE='off' >
	<A HREF="#" onClick="<?php echo $cal_name?>.select(document.forms['myForm'].<?php echo $name?>,
	'anchor.<?php echo $name?>','MM/dd/yyyy'); return false;" NAME="anchor.<?php echo $name?>" 
	ID="anchor.<?php echo $name?>"><IMG src='Calender.gif' border='0'></A>
	<?php
}

if($_SESSION['logged'] && $username != "")
{
/***************************************************
*************Setup POST Handlers***********
/***************************************************/	
	if(isset($_GET['id']))
	{
		$company_id = $_GET['id'];	
	}	
	if(isset($_POST['new_company']) || isset($_POST['update_company']))
	{
		
		$validate=1;
		$transaction_id = $_POST['company_invoice'];
		$paid_amount = $_POST['company_paid_amount'];
		$paid_date= $_POST['company_paid_date'];
		$name = $_POST['company_name'];
		$company_id = $_POST['company_id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$user = $_POST['username'];

	
		$tmp=explode("/",$_POST['date1']);
		$company_expiration=$tmp[2]."-".$tmp[0]."-".$tmp[1];

		if(strlen($name)==0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: Please specify the company name";	
		}		
		else if(strlen($firstname)==0 || strlen($lastname)==0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: Please fill in the First and Last name of the company contact";
		}
		else if(strlen($user) == 0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: Please specify an E-Mail contact for the company";	
		}

		//check if employer already exists
		$sql = "select count(*) from e_users where username = '$user'";
		$count = db_getFirstResult($sql);
		if($count > 0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: that user e-mail already exists.";
		}
		
		if($validate)
		{
/*			//compose sql query
			$sql = "select name from e_companies where e_companies_id='$company_id'";
			if(db_getFirstResult($sql) == "")
				$update=false;
			else
				$update=true;
*/
			// insert or update the company entry
			$sql_arr = array();
			$sql_arr['name'] = $name;
			$sql_arr['expiration']=$company_expiration;
			$sql_arr['transaction_id'] = $transaction_id;
			$sql_arr['paid_amount'] = $paid_amount;
			$sql_arr['paid_date'] = $paid_date;
			if(isset($_POST['new_company']))
			{
				db_insert("e_companies",$sql_arr);
				$company_id = mysql_insert_id();
				$_SESSION['feedback'] .= "Company information added <br>";
			}
			else if(isset($_POST['update_company']))
			{
				$company_id = $_POST['company_id'];
				db_update("e_companies",$sql_arr,"e_companies_id='$company_id'");
				$_SESSION['feedback'] .= "Company information updated<br>";
			}
		}
	}
	if(isset($_POST['new_company']) || isset($_POST['update_contact']))
	{
		$validate = 1;
		$user_id=$_POST['e_users_id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$phone = $_POST['phone'];
		$user = $_POST['username'];
		
		if(isset($_POST['company_id']))
			$company_id = $_POST['company_id'];

		if(strlen($firstname)==0 || strlen($lastname)==0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: Please fill in the First and Last name of the company contact";
		}
		else if(strlen($user) == 0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: Please specify an E-Mail contact for the company";	
		}

		//check if employer already exists
		$sql = "select count(*) from e_users where username = '$user'";
		$count = db_getFirstResult($sql);
		if($count > 0)
		{
			$validate=0;
			$_SESSION['feedback'] = "Error: that user e-mail already exists.";
		}

		if($validate)
		{
			$sql_arr = array();
			if(db_getFirstResult("select username from e_users where e_users_id='$user_id'")=="")
				$new_contact = true;
			else
				$new_contact = false;
			
			if($new_contact)
			{
				$hash = random_hash();
				$sql_arr['is_confirmed'] = '0';	
				$sql_arr['confirm_hash'] = $hash;
			}
			$sql_arr['firstname'] = $firstname;
			$sql_arr['lastname'] = $lastname;
			$sql_arr['username'] = $user;
			$sql_arr['e_companies_id'] = $company_id;
			$sql_arr['phone'] = $phone;
			if($new_contact)
				db_insert("e_users",$sql_arr);
			else
				db_update("e_users",$sql_arr,"username='$user'");
	
			//Compose an E-mail
			if($new_contact)
			{
				$to = $user;
				$subject = "Your MIT Society of Women Engineers resume database account";
				$header = "From: no-reply@swe.scripts.mit.edu \n\r";
				$message = "Dear $firstname,\n\nYou have been registered for access to the SWE resume database. ".
						"To confirm your account, please follow this link: ".$server_root."/employers/confirm.php?username=$user&confirm=$hash".
						"\nYour access to the database will expire on ".$_POST['date1'];
				mail($to,$subject,$message,$header);
				
				$_SESSION['feedback'] .= "A confirmation e-mail has been sent to $user. <br>";
			}
			else
				$_SESSION['feedback'] .= "Contact info updated<br>";
		}
		
		
/*		foreach($_POST as $key=>$value)
			$_SESSION['feedback'] .= "$key : $value <Br>"; */
	}
	if(isset($_POST['delete_contact']))
	{
		$e_users_id = $_POST['e_users_id'];
		db_query("delete from e_users where e_users_id='$e_users_id'");
		$_SESSION['feedback'] .= "User deleted. <br>";	
	}
	if(isset($_POST['send_confirmation']))
	{
		$user_id=$_POST['e_users_id'];
		$firstname = $_POST['firstname'];
		$user = $_POST['username'];
		$hash = random_hash();
		$sql_arr['is_confirmed'] = '0';	
		$sql_arr['confirm_hash'] = $hash;
		
		$to = $user;
		$subject = "Your MIT Society of Women Engineers resume database account";
		$header = "From: no-reply@swe.scripts.mit.edu \n\r";
		$message = "Dear $firstname,\n\nYour password to the MIT SWE resume database has been reset. ".
				"To confirm your account, please follow this link: ".$server_root."/employers/confirm.php?username=$user&confirm=$hash".
				"\nYour access to the database will expire on ".$_POST['date1'];
		mail($to,$subject,$message,$header);

		db_update("e_users",$sql_arr,"e_users_id='$user_id'");
		$_SESSION['feedback'] .= "Password confirmation e-mail has been sent to $user<br>";
	}

	
	site_header();
	echo "<A href='employer_admin.php'>Return to List</a>";
	print_company_details($company_id);		

}
 include ("$prepath/src/footer.php");	
?>	