<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_employer_page($_SERVER['SCRIPT_NAME']);


if(isset($_POST['submit']))
{
	$validate=1;
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$phone = $_POST['company_phone'];
	$username = $_POST['company_username'];
	$name = $_POST['company_name'];
	$e_companies_id = $_POST['e_companies_id'];
	$e_users_id=$_POST['e_users_id'];
	$description = $_POST['description'];
	
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
	else if(strlen($username) == 0)
	{
		$validate=0;
		$_SESSION['feedback'] = "Error: Please specify an E-Mail address";	
	}
	if($validate)
	{
		$sql_arr=array();
		$sql_arr['name'] = $name;
		$sql_arr['description'] = mysql_real_escape_string($description);
		db_update("e_companies",$sql_arr,"e_companies_id='$e_companies_id'");
				
		$sql_arr = array();
		$sql_arr['firstname'] = mysql_real_escape_string($firstname);
		$sql_arr['lastname'] = mysql_real_escape_string($lastname);
		$sql_arr['username'] = mysql_real_escape_string($username);
		$sql_arr['phone'] = mysql_real_escape_string($phone);	
		db_update("e_users",$sql_arr,"e_users_id ='$e_users_id'");
		
		$keys = array("e_companies_industries",
					"e_companies_depts",
					"e_companies_degrees",
					"e_companies_jobtypes",
					"e_companies_citizenship");

		$arr['e_companies_industries'] = $_POST['s_industries'];
		$arr['e_companies_depts'] = $_POST['s_majors'];
		$arr['e_companies_degrees'] = $_POST['s_degrees'];
		$arr['e_companies_jobtypes'] = $_POST['s_jobtypes'];
		$arr['e_companies_citizenship'] = $_POST['s_citizenship'];
		
		foreach($keys as $key)
		{	
			$sql_arr = array();
			if(isset($arr[$key]))
			{
//				echo "$key: $e_companies_id<br>";
				db_query("delete from $key where e_companies_id = '$e_companies_id'");
				foreach($arr[$key] as $abbrev)
				{
					$sql_arr["e_companies_id"] = $e_companies_id;
					$sql_arr["abbrev"] = $abbrev;
					db_insert($key,$sql_arr);
				}
			}
		}
		
		$_SESSION['feedback'] = "Your profile has been updated";
	}
}

$sql = "select * from e_users where username = '$username'";
$res = db_query($sql);
$page_title = "Your Profile";
site_header();

if($res && db_numrows($res) == 1)
{
	$user_row = mysql_fetch_array($res);
	$company_id= mysql_real_escape_string($user_row['e_companies_id']);
	$sql = "select * from e_companies where e_companies_id = '$company_id'";
	$res = db_query($sql);
	$company_row =mysql_fetch_array($res);
	
	$sql = "select abbrev from e_companies_degrees where e_companies_id='$company_id'";
	$e_companies_degrees = db_getArray($sql);

	$sql = "select abbrev from e_companies_depts where e_companies_id='$company_id'";
	$e_companies_depts = db_getArray($sql);

	$sql = "select abbrev from e_companies_industries where e_companies_id='$company_id'";
	$e_companies_industries = db_getArray($sql);

	$sql = "select abbrev from e_companies_jobtypes where e_companies_id='$company_id'";
	$e_companies_jobtypes = db_getArray($sql);

	$sql = "select abbrev from e_companies_citizenship where e_companies_id='$company_id'";
	$e_companies_citizenship = db_getArray($sql);
	
	$sql = "select description from e_companies where e_companies_id='$company_id'";
	$description = db_getFirstResult($sql);
	
	// get the data
	$firstname = $user_row['firstname'];
	$lastname = $user_row['lastname'];
	$email = $user_row['email'];
	$user_phone = $user_row['phone'];
	$user= $user_row['username'];
	$e_users_id = $user_row['e_users_id'];
	
	$company_name = stripslashes($company_row['name']);
	$company_expiration = $company_row['expiration'];
	$e_companies_id = $company_row['e_companies_id'];
	$paid_amount = $company_row['paid_amount'];
	$transaction_id = $company_row['transaction_id'];
	$paid_date = $company_row['paid_date'];


	//populate fields with the data
	echo "<form name='myForm' method='POST' action='$self'>";
	echo "<h4>Company and Payment Information</h4>";
	echo "<table>";
	echo "<tr><td>Company Name</td><td><input type='text' name='company_name' value=\"";form_fill_value($company_name);echo "\"></td></tr>";
	echo "<tr><td>Access Expiration:</td><td>$company_expiration</td></tr>";
	echo "<tr><td>Paid Amount:</td><td>$$paid_amount</td></tr>";
	echo "<tr><td>Paid Date:</td><td>$paid_date</td></tr>";
	echo "<tr><td>Transaction ID:</td><td>$transaction_id</td></tr></table>";
	echo "Questions about your account? Please <A href='mailto: swe-exec@mit.edu?subject=Question about resume database account'>contact us</a>.";
	
	echo "<h4>Contact Information</h4>";
	echo "<table>";	
	echo "<tr><td>Contact (First, Last):</td><td><input type='text' name='firstname' value=\"";form_fill_value($firstname);echo "\">";
	echo "<input type='text' name='lastname' value=\"";form_fill_value($lastname);echo "\"></td></tr>";
	echo "<tr><td>Contact Email:</td><td><input type='text' name='company_username' value=\"";form_fill_value($user);echo "\"></td></tr>";
	echo "<tr><td>Phone:</td><td><input type=text name='company_phone' value=\"";form_fill_value($user_phone);echo "\"></td></tr>";
	echo "</table>";
	echo "<input type='hidden' name='e_companies_id' value=\"";form_fill_value($e_companies_id);echo "\">";
	echo "<input type='hidden' name='e_users_id' value=\"";form_fill_value($e_users_id);echo "\">";
	
	?>
	<h4>About Your Company</h4>
	<div id='input_box'>
		Shift or Apple + Click to select multiple entries
		<table><tr>
			<tr><td>Description:</td><td><textarea cols='50' rows='5' name='description'><?php echo form_fill_value($description);?></textarea> </td>
			<tr><td>Industries:</td><td><?php	print_industries_selection($e_companies_industries);?> </td>
			</tr>
		</table>
	</div>
	
<!--	<h4>Who are you recruiting?</h4>
	<div id='input_box'>
		Shift or Apple + Click to select multiple entries
		<table><tr>
			<td>Majors:</td><td><?php print_majors_selection($e_companies_depts);?> </td></tr>
			<tr><td>Degrees:</td><td><?php print_degrees_selection($e_companies_degrees);?> </td></tr>
			<tr><td>Job Types:</td><td><?php print_jobtype_selection($e_companies_jobtypes);?> </td>
			<tr><td>Citizenship:</td><td><?php print_citizenship_selection($e_companies_citizenship);?> </td>
			</tr>
		</table>
	</div>
-->	
	<?php	

	echo "<input type='submit' name='submit' value='Submit'>";
	echo "</form>";
	
}


include ("$prepath/src/footer.php");	
?>	
