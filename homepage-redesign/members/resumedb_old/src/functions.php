<?php
//$server_root="http://swe.scripts.mit.edu"; 
$server_root="http://swe.mit.edu/"; 
date_default_timezone_set('America/New_York');

function protect_student_page($refer_path)
{ 
	global $server_root;
	if($_SESSION['logged'] && $_SESSION['username']!="")
	{
		//check that the student exist in the db
		if(is_student($_SESSION['username']))
			$student_username = $_SESSION['username'];
		else
		{
			$_SESSION['feedback'] = "Error: Please create a user account and log in first";
			header("location: $server_root"."/resumedb/");
			exit;		
		}
	}
	else
	{
		$_SESSION['feedback'] = "Error: Please create a user account and log in first";
		header("location: $server_root"."/resumedb/");
		exit;	
	}
	
	  // employers can't be here
  	if (isset($_SESSION['userlevel']) && ($_SESSION['userlevel'] == 'employer')){
    	header("Location: $server_root"."/resumedb/");
    	exit;
  	}
  	
  	if ($_SESSION['admin']){
	    if (isset($_GET['user']))
	      $student_username = $_GET['user'];
	    if(!is_student($student_username))
	    {
		    $_SESSION['feedback']="Error: Invalid user";
		    header("location: $server_root"."/resumedb/");
	   	}
	}
	
	return $student_username; 	 

}

function protect_employer_page($refer_path)
{ 
	global $server_root;
	if($_SESSION['logged'] && $_SESSION['username']!="")
	{
		//check that the student exist in the db
		if(is_employer($_SESSION['username']))
		{
			$employer_username = $_SESSION['username'];
		}
		else
		{
			$_SESSION['feedback'] = "Error: Please create a user account and log in first";
			header("location: $server_root"."/resumedb/");
			exit;		
		}
	}
	else
	{
		$_SESSION['feedback'] = "Error: Please create a user account and log in first";
		header("location: $server_root"."/resumedb/");
		exit;	
	}
	if ($_SESSION['admin']){
	    if (isset($_GET['user']))
	      $student_username = $_GET['user'];
	    if(!is_employer($employer_username))
	    {
		    $_SESSION['feedback']="Error: Invalid user";
		    header("location: $server_root"."/resumedb/");
	   	}
	}
	
	return $employer_username; 	 
}


function protect_admin_page($refer_path)
{
	global $server_root;
	if($_SESSION['logged'] && $_SESSION['username']!="")
	{
		//check that the student exist in the db
		if(is_admin($_SESSION['username']))
			$student_username = $_SESSION['username'];
		else
		{
			$_SESSION['feedback'] = "Error: You do not have permission to access this page";
			header("location: $server_root");
			exit;		
		}
	}
	else
	{
		$_SESSION['feedback'] = "Error: Please create a user account and log in first";
		header("location: $server_root");
		exit;	
	}
	
	  // employers can't be here
  	if (isset($_SESSION['userlevel']) && ($_SESSION['userlevel'] == 'employer')){
    	header("Location: $server_root");
    	exit;
  	}	
	return $student_username; 	 
}

function session_defaults()
{
	$_SESSION['logged'] = false;
	$_SESSION['uid']='0';
	$_SESSION['username'] = '';
	$_SESSION['admin']=false;
	$_SESSION['userlevel']="";
	$_SESSION['cookie'] = 0;
	$_SESSION['remember'] = false;	
}

class User{
	var $failed = false;	//failed login attempt
	var $id = 0;		//current user id	
	var $table='';
	var $username='';
	
	function User($table)
	{
		$this->table = $table;
		$this->_checkSession();	
		if($_SESSION['logged'])
		{
			$this->_checkSession();	
		}
		else if(isset($_COOKIE['SWEwebLogin']))
		{
			$this->_checkRemembered($_COOKIE['SWEwebLogin']);
		}
	}
	
	function _checkLogin($username,$password,$remember)
	{
		//echo "$username<br>$password<br>$remember";
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string(md5($password));
		$sql = "select * from $this->table where username='$username' and password='$password'";
		$row = db_getRow($sql);
		
		if(!empty($row))
		{
			$this->_setSession($row,$remember);
			return true;
		}
		else
		{
			$this->failed=true;	
			$this->_logout();
			return false;
		}			
	}
	
	function _setSession(&$values,$remember,$init=true)
	{
		$this->id = $values['s_users_id'];
		$_SESSION['uid'] = $this->id;
		$_SESSION['username']= htmlspecialchars($values['username']);
		$_SESSION['cookie']=$values['cookie'];
		$_SESSION['logged']=true;
		if($remember)
			$this->_updateCookie($values['cookie'],true);
		if($init)
		{
			$session = mysql_real_escape_string(session_id());
			$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
			$sql = "update $this->table set session='$session', ip='$ip' where s_users_id=$this->id";
			db_query($sql);	
		}
		if($values['access'] == "admin")
			$_SESSION['admin']=true;
		else
			$_SESSION['admin']=false;
		if($values['e_users_id'] != "")
			$_SESSION['userlevel'] = "employer";
		else if ($values['s_users_id'] != "")
			$_SESSION['userlevel'] = "student";
		
	}
	
	function _updateCookie($cookie,$save)
	{
		$_SESSION['cookie'] = $cookie;
		//If the cookie from the DB is blank, generate a new one, and update the db:
		if(strlen($_SESSION['cookie'])<32)
		{
			$new_cookie = random_hash();
			$sql = "update $this->table set cookie='$new_cookie' where username='".$_SESSION['username']."'";
			//echo $sql;
			db_query($sql);	
		}
		if($save)
		{
			$cookie = serialize(array($_SESSION['username'],$cookie));
			setcookie('SWEwebLogin',$cookie, time()+31104000);
		}	
	}
	
	function _checkRemembered($cookie)
	{
		list($username,$cookie) = @unserialize($cookie);	
		if(!$username or !$cookie) return;
		$username = mysql_real_escape_string($username);
		$cookie = mysql_real_escape_string($cookie);
		$sql = "select * from $this->table where username=$username and cookie=$cookie";
		$row=db_getRow($sql);
		if(!empty($row))
		{
			$this->_setSession($result,true);
		}
	}
	
	function _checkSession()
	{
		$username = mysql_real_escape_string($_SESSION['username']);
		$cookie = mysql_real_escape_string($_SESSION['cookie']);
		$session = mysql_real_escape_string(session_id());
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$sql = "select * from $this->table where username='$username' and cookie='$cookie' and session = '$session' and ip='$ip'";
		$row = db_getRow($sql);
		if(!empty($row))
			$this->_setSession($row,false,false);	
		else
			$this->_logout();
	}
	
	function _printSessionStatus()
	{
		if(isset($_SESSION['uid']))
		{
			echo "Logged in as: ".$_SESSION['username']."<br>";
			echo "Level: ";
			if($_SESSION['admin']) echo "admin"; else echo "student";
			echo "<br>User ID: ".$_SESSION['uid']."<br>";
		}
		else
			echo "Not logged in";
	}
	
	function _logout()
	{
		session_defaults();
	}
	function _userExists($username)
	{
		$sql = "select username from s_users where username='$username'";
		if(db_getFirstResult($sql) == "")
			return false;
		else
			return true;
	}
	function _Register($post,$password,$confirm)
	{
		if(!$this->_userExists($username))
		{
			$username = $post['username'];
			$sql_arr['username'] = $username;
			$sql_arr['firstname'] = $post['firstname'];
			$sql_arr['lastname'] = $post['lastname'];

			$sql_arr['password'] = $password;
			$sql_arr['confirm_hash'] = $confirm;
			$sql_arr['is_confirmed'] = 0;
			$sql_arr['access'] = 'student';

			db_insert("s_users",$sql_arr);
			
			$this->username=$username;
			$this->id = mysql_insert_id();
			if($this->_sendConfirmationEmail($confirm))
				$_SESSION['feedback'] = "User account successfully created. Please check your e-mail to activate your account";
			else
				$_SESSION['feedback'] = "Error sending the confirmation e-mail. Please contact the webmaster";
		}
		else
			$_SESSION['feedback'] = "Error: Username already exists";
	}
	
	function _sendConfirmationEmail($hash)
	{
		global $server_root;
		if($this->username != "")
		{
			$email=$this->username."@mit.edu";
			$subject = "Your Resume Database Account";
			$message = "Thank your for your registration to the SWE resume database. To confirm your account please click here: ";
			$message .= $server_root."resumedb/students/confirm.php?user=$this->username&confirm=$hash";
			$header="FROM: no-reply@swe.scripts.mit.edu \n\r";
//			echo "$email<br>$subject<br>$message";
			mail($email,$subject,$message,$header);
			return true;
		}
		return false;
	}
	function getUsername()
	{
		return $this->username;
	}
}

function get_student_id($username)
{
	$sql = "select s_users_id from s_users where username='".mysql_real_escape_string($username)."'";
	return db_getFirstResult($sql);
}

function get_username($id)
{
	$sql = "select username from s_users where s_users_id='".mysql_real_escape_string($id)."'";
	return db_getFirstResult($sql);
}

function is_student($username)
{
	$sql = "select username from s_users where username='".mysql_real_escape_string($username)."'";
	if(db_getFirstResult($sql) != "")
		return true;
	else
		return false;	
}

function is_employer($username)
{
	$sql = "select username from e_users where username='".mysql_real_escape_string($username)."'";
	if(db_getFirstResult($sql) != "")
		return true;
	else
		return false;	
}


function is_admin($username)
{
	$sql = "select username from s_users where username='".mysql_real_escape_string($username)."' and access='admin'";
	if(db_getFirstResult($sql) != "")
		return true;
	else
		return false;	
}


function site_header()
{
	global $server_root,$prepath, $page_title;
	
?>





<!-- This template is for everything in the root directory --> 
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
<title>MIT SWE | Society of Women Engineers</title> 
<meta name="keywords" content="MIT SWE, MIT, Society of Women Engineers, MIT Society of Women Engineers" /> 
<meta name="description" content="Massachusetts Institute of Technology Society of Women Engineers" /> 
<link rel="stylesheet" type="text/css" href="/default.css" /> 
<link rel="stylesheet" type="text/css" href="/chromestyle.css" /> 
<script type="text/javascript" src="/chrome.js"> </script> 
<!-- Google Analytics !--> 
<script type="text/javascript"> 
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
 
</script> 
<script type="text/javascript"> 
try {var pageTracker = _gat._getTracker("UA-12260683-1");
pageTracker._trackPageview();
} catch(err) {}
</script> 
<!-- End Google Analytics !--> 
 
 
<SCRIPT language="JavaScript"> 
    <!--
 
    if (document.images)
    {
      preload_image_object = new Image();
      // set image url
      image_url = new Array();
      image_url[0] = "/images/top.png";
      image_url[2] = "/images/nav.png";
 
       var i = 0;
       for(i=0; i<=5; i++) 
         preload_image_object.src = image_url[i];
    }
 
    //--> 
</SCRIPT>     
 
 
</head> 
 
 
 
<body> 
 
<div id="blackbar"> 
	<div id="blackbar-text"> 
	<span style="float: left;"><a href="http://mit.edu">massachusetts institute of technology</a></span> 
	<span style="float: right;"> 
	<a href="/contact.php">contact</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="http://mitswe.blogspot.com/">mitswe.blogspot.com</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="http://www.swe.org/regionf/">swe region F</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="http://societyofwomenengineers.swe.org/">swe.org</a> 
	</span> 
	</div> 
</div> 
 
<div id="preheader"> 
<div> 
<ul id="nav1"> 
<li id="nav1_0"><span></span></li> 
<li id="nav1_1"><a href="http://swe.mit.edu"><span>Home</span></a></li> 
<li id="nav1_2"><span></span></li> 
<li id="nav1_3"><a href="/nationalmembership.php"><span>National SWE Membership Information</span></a></li> 
</ul> 
</div> 
 
 
 
<div id="header"> 
 
 
<div class="chromestyle" id="chromemenu"> 
<ul id="nav2"> 
<li id="nav2_1"><a href="/about/" rel="dropmenu1"><span>About</span></a></li> 
<li id="nav2_2"><a href="/members/" rel="dropmenu2"><span>For Members</span></a></li> 
<li id="nav2_3"><a href="/outreach/" rel="dropmenu3"><span>Outreach</span></a></li> 
<li id="nav2_4"><a href="/resources/careertips.php" rel="dropmenu4"><span>Resources</span></a></li> 
<li id="nav2_5"><a href="/employers/" rel="dropmenu5"><span>For Employers</span></a></li> 
<li id="nav2_6"><a href="/alumni/" rel="dropmenu6"><span>For Alumni</span></a></li> 
</ul> 
</div> 
 
<!--1st drop down menu -->                                                   
<div id="dropmenu1" class="dropmenudiv" style="width:116px; margin-left: 18px;"> 
<a href="/about/officers.php">board 2011</a>
<a href="/contact.php">contact us</a>
<a href="/about/departments.php">departments</a>
<a href="/about/info.php">national SWE</a>
<a href="/members/photos.php">photos</a>
</div> 
                                               
<div id="dropmenu2" class="dropmenudiv" style="width: 182px;"> 
<a href="/members/companyprofile.php">a day in the life of</a>
<a href="/members/awards.php">board awards</a>
<a href="/members/calendar.php">calendar</a>
<a href="/members/newsletter.php">newsletter</a>
<a href="/members/opportunities.php">open opportunities</a>
<a href="/resumedb/">resume database</a>
<a href="/members/scholarships.php">scholarships</a>
<a href="http://swe.mit.edu/wiki">wiki for board</a>
</div> 
                                               
<div id="dropmenu3" class="dropmenudiv" style="width: 154px;"> 
<a href="/elementaryschool/">elementary school</a>
<a href="/middleschool/">middle school</a>
<a href="/highschool/">high school</a>
</div> 
                                             
<div id="dropmenu4" class="dropmenudiv" style="width: 160px;"> 
<a href="/resources/careerevents.php">career events</a>
<a href="/resources/careerfairbanquet.php">career fair banquet</a>
<a href="http://jobresource.com/groups/ccenter.asp?fct=1&id=986830721">job postings</a>
<a href="/resumedb/">resume database</a> 
</div> 
                                             
<div id="dropmenu5" class="dropmenudiv" style="width: 188px;"> 
<a href="/members/companyprofile.php">a day in the life of</a>
<a href="http://career-fair.mit.edu">career fair website</a>
<a href="/contact.php">contact us</a>
<a href="/employers/holdanevent.php">hold an event</a>
<a href="/resumedb/">resume database</a>
<a href="/employers/sponsorship.php">sponsorship</a>
</div> 
                                                 
<div id="dropmenu6" class="dropmenudiv" style="width: 156px;"> 
<a href="/contact.php">contact us</a> 
</div> 
 
</div> 
 
<div id="container"> 
 
<script type="text/javascript">  
cssdropdown.startchrome("chromemenu")
</script>  
 
 
<div id="body" style="background: white;"> 
  
<div id="templatehead"> 
<div id="templatehead-text"> 
<!-- Page Title --> 
Resume Database
 
 
</div> 
</div> 
 
<table border="0" width="957" cellspacing="0" cellpadding="0"> 
<tr> 
 
<td valign="top"> 
<div id="left" style="width: 940px; background: white;"> 
<div id="margins"> 
<!-- Body --> 

	<div id='resume_menu'>
	<?php	
	if($_SESSION['logged'])
	{
		echo "<A HREF='$server_root/resumedb/index.php?logout=true'>Logout</a>";
	}
	
	if($_SESSION['admin'])
		echo " | <A HREF='$prepath/admin/'>Admin Menu</a>";
//	else
//		echo "<A HREF='$server_root'>[Login]</a>";
	?>
	</div>
		
	<div id="primarycontent">
	<?php
		breadCrumb($_SERVER['REQUEST_URI']);
		echo "<BR><br>";
 
	if (func_num_args() == 1)
		$page_title = func_get_arg(0);


   if ($page_title != "") 
      echo '<h3 class="page_title">'.$page_title.'</h3>';
    
    if (isset($_SESSION['feedback']) && strlen($_SESSION['feedback']) > 0){
      echo '<h4 class="error_feedback">'.$_SESSION['feedback'].'</h4>';
      $_SESSION['feedback']="";
    }
}

function random_hash ()
{
  list($usec, $sec) = explode(' ', microtime());
  $seed= (float) $sec + ((float) $usec * 100000);
  mt_srand($seed) ;
  $password = mt_rand(1,99999999);
  $password = md5($password);
  return $password;
}

function random_gen($length)
{
  $random= "";
  srand((double)microtime()*1000000);
  $char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $char_list .= "abcdefghijklmnopqrstuvwxyz";
  $char_list .= "1234567890";
  $strset=$char_list;
  // Add the special characters to $char_list if needed

  for($i = 0; $i < $length; $i++)
  {
    $random .= substr($strset,(rand()%(strlen($strset))), 1);
  }
  return $random;
} 

function print_mainform($username)
{
	global $s_depts,$s_degrees,$s_years,$degreeids,$jobtypeids,$s_jobtypes,$industryids,$s_industries,$student_firstname,$student_lastname,$student_alt_email,$server_root;
	$self=$_SERVER['PHP_SELF'];
?>
	<form method='POST' enctype="multipart/form-data" action="<?php echo $self; if($_SERVER['QUERY_STRING']>' '){echo '?'.$_SERVER['QUERY_STRING'];} ?>">
	<div id='input_box'>
	<table><tr>
		<td><b>First/Last Name:<b></td><td><input type='text' name='firstname' value='<?php echo $student_firstname;?>'> <input type='text' name='lastname' value='<?php echo $student_lastname;?>'></td></tr>
		<td><b>Email:</b></td><td><input type='text' disabled value='<?php echo $username."@mit.edu";?>'></td></tr>
		<td><b>Alt Email:</b></td><td><input type='text' name='student_alt_email' value='<?php echo $student_alt_email;?>'></td></tr>
		<td><b>Citizenship:</b></td><td>
			<select name='student_citizenship' width='200' style="width:200px;">
			<option value="" <?php echo user_form_selected("s_users",array("citizenship"=>""),$username) ?>></option>
			<option value="us" <?php echo user_form_selected("s_users",array("citizenship"=>"us"),$username)?>>US</option>
			<option value="perm" <?php echo user_form_selected("s_users",array("citizenship"=>"perm"),$username)?>>Permanent Resident</option>
			<option value="intl" <?php echo user_form_selected("s_users",array("citizenship"=>"intl"),$username)?>>International</option>
			</select> 
		</td></tr>
		<?php
			if($_SESSION['admin'])
			{
				echo "<td><b>User Level:</b></td><td><select name='userlevel'>";
				echo "<option value='student' ".user_form_selected("s_users",array("access"=>"student"),$username).">Student</option>";
				echo "<option value='admin' ".user_form_selected("s_users",array("access"=>"admin"),$username).">Admin</option>";
				echo "</td></tr>";
			} ?>
		
	</table>
	</div>
	
	
	<h4>Resume Upload</h4>
	<div id='input_box'>	
	<table><tr>
		<td><b>Resume:</b></td><td><input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
			<input type="file" name="resume_file" size="30" /> <span class="form_detail">2 MB max (PDF)</span><br>
			<?php
				if($location = resume_uploaded($username))
				{
					echo "<A HREF='$server_root/resumedb$location'>Click here</a> to view your resume<br>";
					echo "<input type='checkbox' name='delete_resume'> Check here to delete your resume";
				}
				else
					echo "<i>No resume upload detected</i>";
			?>
		</td></tr>
	</table>
	</div>
	
	<h4>Degree Information</h4>
	<div id='input_box'>
		<?php
			if (isset($s_degrees)){$iters = count($s_degrees);}
			else {$iters = 1;}
			echo "<table id='degree_section' name='degree_section'>";		
			for ($j=0; $j<$iters; $j++){
				print_degree_dropdown($s_depts[$j],$s_degrees[$j],$s_years[$j],$j);
			}
			echo "</table>";
			echo "<b>Note:</b> Any degrees with missing fields will not be saved<br><br>";
			?>
		<input type="button" value="Add Another Degree" onclick="addRowToTable('degree_section')" />
	 	<span class="form_detail">Only the most recent degree in a given field!
	</div>
	
	<h4>Interests</h4>
	<div id='input_box'>
		<b>Note:</b>Filling this section out will better allow companies to search for your resume.<br>
		<i>Shift or Apple + Click to select multiple entries</i>
		<table><tr>
			<td>Job Types:</td><td><?php	print_jobtype_selection($s_jobtypes);?> </td>
			<tr><td>Industries:</td><td><?php	print_industries_selection($s_industries);?> </td>
			</tr>
		</table>
	</div>
	<input type='submit' value='Save Your Profile' name='submit'>
	</form>
<?php
}


function print_company_details($company_id)
{
	$row_comployer = array();
	if($company_id != "")
	{
		//get employer infomration
		$sql1 = "select * from e_companies where e_companies_id = '$company_id'";
		$row_employer = db_getRow($sql1);
		
		//get contacts information
		$sql2 = "select * from e_users where e_companies_id= '$company_id'";
		$res_contacts = db_query($sql2);
	}	
	
	echo "<a name='title'><h4>$page_title</h4></a>";
	echo "<form name='myForm' method='POST' action='$self'>";
	?>
		<DIV ID="cal1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
	<?php
	
	//Contact (First, Last)
	//Contact Email
	//Company Name
	//Access Expiration
	echo "<h4>Company Information</h4>";
	echo "<table>";
	echo "<tr><td>Company Name</td><td><input type='text' name='company_name' value=\"";form_fill_value($row_employer['name'],"",$_POST['company_name']);echo "\"></td></tr>";
	echo "<tr><td>Access Expiration:</td><td>";
	print_input_date("date1",date_convert($row_employer['expiration']),date('m/d/Y'),$_POST['date1']);
	echo "<input type=button onClick='setDate(document.myForm.date1,6)' name='six_month' value='6 Months'><input type=button onClick='setDate(document.myForm.date1,12)' name='year' value='12 Months'></td></tr>";	
	echo "<tr><td>Check or Transaction #</td><td><input type=text name='company_invoice' value='";form_fill_value($row_employer['transaction_id'],"",$_POST['company_invoice']);echo "'></td></tr>";
	echo "<tr><td>Amount Paid: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$</td><td><input type=text name='company_paid_amount' value='";form_fill_value($row_employer['paid_amount'],"",$_POST['company_paid_amount']);echo "'></td></tr>";
	echo "<tr><td>Date Paid (YYYY-mm-dd):</td><td><input type=text name='company_paid_date' value='";form_fill_value($row_employer['paid_date'],"",$_POST['company_paid_date']);echo "'></td></tr>";
	echo "</table>";
	if($row_employer['name'] != "")
	{
		echo "<input type='hidden' name='company_id' value='".$row_employer['e_companies_id']."'>";
		echo "<input type='submit' name='update_company' value='Update Info'>";
	}
	echo "<h4>Add new contact</h4>";
	echo "<table>";	
	echo "<tr><td>Contact (First, Last):</td><td><input type='text' name='firstname' value='";form_fill_value($_POST['firstname']);echo "'>";
	echo "<input type='text' name='lastname' value='";form_fill_value($_POST['lastname']);echo "'></td></tr>";
	echo "<tr><td>Contact Email:</td><td><input type='text' name='username' value='";form_fill_value($_POST['username']);echo "'></td></tr>";
	echo "<tr><td>Phone:</td><td><input type=text name='phone' value='";form_fill_value($_POST['phone']);echo "'></td></tr>";
	echo "</table>";
	if($company_id == "")
		echo "<input type='submit' name='new_company' value='Add Company'>";
	else
		echo "<input type='submit' name='update_contact' value='Add Contact'>";
	echo "</form><br>";		
	if(db_numrows($res_contacts) > 0)
	{
		echo "<hr>";
		echo "<h4>Edit Contacts</h4>";	
		while($row=mysql_fetch_array($res_contacts))
		{
			echo "<form name='myForm1' method='POST' action='$self'>";
			echo "<table>";	
			echo "<tr><td>Contact (First, Last):</td><td><input type='text' name='firstname' value='";form_fill_value($row['firstname']);echo "'>";
			echo "<input type='text' name='lastname' value='";form_fill_value($row['lastname']);echo "'></td></tr>";
			echo "<tr><td>Contact Email:</td><td><input type='text' name='username' value='";form_fill_value($row['username']);echo "'></td></tr>";
			echo "<tr><td>Phone:</td><td><input type=text name='phone' value='";form_fill_value($row['phone']);echo "'></td></tr>";
			echo "</table>";
			echo "<input type='hidden' name='e_users_id' value ='".$row['e_users_id']."'>";
			echo "<input type='hidden' name='company_id' value ='".$company_id."'>";
			echo "<input type='submit' name='update_contact' value='Update Info'>";
			$message = "Are you sure you want to delete this user?";
			echo "<input type='submit' name='delete_contact' value='Delete' onclick=\"return confirm_entry('".$message."')\">";
			echo "<input type='submit' name='send_confirmation' value='Re-Send E-Mail Confirmation'>";
			echo "</form><br><br>";
		}
	}
	
}


function print_degree_dropdown($dept,$degree,$student_year,$j)
{
	?>
	
	<tr><td>
	<?php
	echo "Degree:</td><td> <select name=\"s_depts[]\"><option value=''></option>";
	$sql = "SELECT name,abbreviation FROM departments ORDER BY sort_order";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(trim($dept) == trim($row[1]))
	   		echo ' selected="selected" ';
  		echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select><br>";
	
	
	echo "<select name=\"s_degrees[]\"><option value=''></option>";
	$sql = "SELECT name,abbreviation FROM degrees ORDER BY sort_order";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
  	   	if($degree === $row[1])
	    		echo ' selected="selected" ';
    	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";	
	
	echo "<select name=\"s_years[]\"><option value=''></option>";
	$year = date("Y");
	for ($i=-1;$i<5;$i++){
	  echo '<option value="'.($year+$i).'"';
	    if($student_year === ((string)($year+$i))){ echo ' selected="selected" ';}
	  echo '>'.($year+$i).'&nbsp;</option>';
	}
	echo "</select>";
	if($j>=1)
	{
		echo "<input type=button value='delete' onClick=\"removeRowFromTable(event)\">";	
	}	
	echo "</td>";
	echo "</tr>";
}


function print_jobtype_selection($user_selections)
{	
	
	echo "<select name=\"s_jobtypes[]\" size=4 multiple>";
	$sql = "SELECT name,abbreviation FROM jobtypes ORDER BY sort_order";
	$res = db_query($sql);
	echo "<option value='all'";
    if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">All Jobtypes</option>";
	
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(is_array($user_selections))
	    {
	  		if(in_array($row['abbreviation'],$user_selections))
		   		echo ' selected="selected" ';
   		}
	  	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}

function print_industries_selection($user_selections,$size='10')
{	
	echo "<select name=\"s_industries[]\" size=$size multiple>";
	$sql = "SELECT name,abbreviation FROM industries ORDER BY sort_order";
	$res = db_query($sql);
	echo "<option value='all'";
    if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">All Industries</option>";
	
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(is_array($user_selections))
	    {
	  		if(in_array($row['abbreviation'],$user_selections))
		   		echo ' selected="selected" ';
   		}
	  	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}

function print_majors_selection($user_selections)
{	
	echo "<select name=\"s_majors[]\" size=5 multiple>";
	$sql = "SELECT name,abbreviation FROM departments ORDER BY sort_order";
	$res = db_query($sql);
	echo "<option value='all'";
    if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">All Majors </option>";
	
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(is_array($user_selections))
	    {
	  		if(in_array($row['abbreviation'],$user_selections))
		   		echo ' selected="selected" ';
   		}
	  	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}

function print_degrees_selection($user_selections)
{	
	
	echo "<select name=\"s_degrees[]\" size=4 multiple>";
	$sql = "SELECT name,abbreviation FROM degrees ORDER BY sort_order";
	$res = db_query($sql);
	echo "<option value='all'";
    if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">All Degrees </option>";
	
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(is_array($user_selections))
	    {
	  		if(in_array($row['abbreviation'],$user_selections))
		   		echo ' selected="selected" ';
   		}
	  	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}
function print_citizenship_selection($user_selections)
{	
	$names=array("US Citizen","Permanent Resident","International");
	$abbrevs=array("us","perm","intl");
	
	echo "<select name=\"s_citizenship[]\" size=4 multiple>";
	echo "<option value='all'";
    if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">Any Status</option>";
	$count = 0;
	foreach($names as $name){
  		echo '<option value="'.$abbrevs[$count].'"';
	    if(is_array($user_selections))
	    {
	  		if(in_array($abbrevs[$count],$user_selections))
		   		echo ' selected="selected" ';
   		}
	  	echo '>'.$name.'&nbsp;</option>';
	 	$count++;
	}
	echo "</select>";
}
function print_years_selection($user_selections)
{
	echo "<select name=\"s_years[]\" size=4 multiple style='width:100px;' width='100px'>";
	echo "<option value='all'";
	   if(is_array($user_selections))
    {
  		if(in_array("all",$user_selections))
	   		echo ' selected="selected" ';
	}
	echo ">All Years </option>";
	
	$year = date("Y");
	for ($i=0;$i<6;$i++){
	  echo '<option value="'.($year+$i).'"';
	  if(is_array($user_selections))
	  {	 
	   	if(in_array(((string)($year+$i)),$user_selections))
	   		echo ' selected="selected" ';
   	  }
	  echo '>'.($year+$i).'&nbsp;</option>';
	}
	echo "</select>";
}

function user_form_selected($table,$result,$username)
{
		$keys = array_keys($result);
		$sql = "select $keys[0] from $table where username='$username'";
		if(db_getFirstResult($sql) == $result[$keys[0]])
			return "selected";
		else
			return "";
}

function resume_uploaded($username)
{
	global $prepath;
	//1. Check if location is in DB
	//2. check if file actually exists
	$user_id = get_student_id($username);
	$sql = "select file_location from s_resumes where s_users_id='$user_id'";

	$location = db_getFirstResult($sql);	
	if($location != "")
	{
		if(is_file("$prepath/$location"))
		{
			return $location;
		}
		else
		{
			db_query("delete from s_resumes where s_users_id='$user_id'");
			return false;	
		}
	}
	else
		return false;
	
}

function form_fill_value($string,$true="",$false="")
{
	if(!empty($string))
	{
		if($true == "")		// If $true is left blank, assume the value is the string itself
			$value = $string;
		else
			$value = $true;
	}
	else
		$value = $false;
	echo stripslashes($value);
}

function date_convert($date)
{
	if($date == "")
		return "";
	$tmp = explode("-",$date); //2008-10-25
	return $tmp[1]."/".$tmp[2]."/".$tmp[0];
}

##############################################################################
# breadcrumb.php                  Version 1.1                                #
# Copyright 2000 Jacob Stetser    jstetser@icongarden.com                    #
# Created Dec 30, 2000            Last Modified May 2, 2001                 #
##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright [and -left] 2000 Jacob Stetser. All Rights Reserved except as    #
# provided below.                                                            #
#                                                                            #
# breadcrumb.php may be used and modified free of charge by anyone so long   #
# as this copyright notice and the comments above remain intact. By using    #
# this code you agree to indemnify Jacob Stetser from any liability that     #
# might arise from it's use.                                                 #
#                                                                            #
# This script is released under the BSD license.                             #
# The author recognizes this script's indebtedness to evolt.org, Martin      #
# Burns, Adrian Roselli and countless other ideas of its kind. This script   #
# is therefore unencumbered free code.                                       #
##############################################################################

function breadCrumb($PATH_INFO) {
	global $page_title, $server_root;
	
	// Remove these comments if you like, but only distribute 
	// commented versions.
	
	// Replace all instances of _ with a space
	$PATH_INFO = str_replace("_", " ", $PATH_INFO);
	// split up the path at each slash
	$pathArray = explode("/",$PATH_INFO);
	
	// Initialize variable and add link to home page
	if(!isset($server_root)) { $server_root=""; }
	$breadCrumbHTML = '<a href="'.$server_root.'/" title="Home Page">Home</a> &gt; ';
	
	// initialize newTrail
	$newTrail = $server_root."/";
	
	// starting for loop at 1 to remove root
	for($a=1;$a<count($pathArray)-1;$a++) {
		// capitalize the first letter of each word in the section name
		$crumbDisplayName = ucwords($pathArray[$a]);
		// rebuild the navigation path
		$newTrail .= $pathArray[$a].'/';
		// build the HTML for the breadcrumb trail
		$breadCrumbHTML .= '<a href="'.$newTrail.'">'.$crumbDisplayName.'</a> &gt; ';
	}
	// Add the current page
	if(!isset($page_title)) { $page_title = "Current Page"; }
	$breadCrumbHTML .= '<strong>'.$page_title.'</strong>';
	
	// print the generated HTML
	print($breadCrumbHTML);
	
	// return success (not necessary, but maybe the 
	// user wants to test its success?
	return true;
}

?>