<?php
$server_root="http://swe.mit.edu/"; 
date_default_timezone_set('America/New_York');

function protect_employer_page($refer_path)
{ 
	global $server_root;
	if($_SESSION['logged'] && $_SESSION['username']!="")
	{
		//check that the employer exist in the db
		if(is_employer($_SESSION['username']))
		{
			$employer_username = $_SESSION['username'];
		}
		else
		{
			echo "Error: Please enter a valid username";
			//header("location: $server_root"."/corporate/resume_database");
			exit;		
		}
	}
	else
	{
		?>
		<meta http-equiv="refresh" content="0; url=http://swe.mit.edu/corporate/resume_database"/>
		<?php
		exit;	
	}
	
	return $employer_username; 	 
}

function session_defaults()
{
	$_SESSION['logged'] = false;
	$_SESSION['uid']='0';
	$_SESSION['username'] = '';
}

class User{
	var $failed = false;	//failed login attempt
	var $id = 0;		//current user id	
	var $table='';
	var $username='';
	
	//function User($table)
	//{
	//	$this->table = $table;
	//	$this->_checkSession();	
	//	if($_SESSION['logged'])
	//	{
	//		$this->_checkSession();	
	//	}
	//}
	
	function _checkLogin($username,$password)
	{
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string(md5($password));
		$sql = "select * from $this->table where username='$username' and password='$password'";
		$row = db_getRow($sql);
		
		if(!empty($row))
		{
			//$this->_setSession($row);
			return true;
		}
		else
		{
			$this->failed=true;	
			$this->_logout();
			return false;
		}			
	}
	
	function _setSession(&$values,$init=true)
	{
		$this->id = $values['s_users_id'];
		$_SESSION['uid'] = $this->id;
		$_SESSION['username']= htmlspecialchars($values['username']);
		$_SESSION['logged']=true;
		
		//if($init)
		//{
		//	$session = mysql_real_escape_string(session_id());
		//	$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		//	$sql = "update $this->table set session='$session', ip='$ip' where s_users_id=$this->id";
		//	db_query($sql);	
		//}
	}
	
	function _checkSession()
	{
		$username = mysql_real_escape_string($_SESSION['username']);
		$session = mysql_real_escape_string(session_id());
		$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
		$sql = "select * from $this->table where username='$username' and session='$session' and ip='$ip'";
		$row = db_getRow($sql);
		if(!empty($row))
			$this->_setSession($row,false);	
		else
			$this->_logout();
	}
	
	function _logout()
	{
		session_defaults();
	}
}

function is_employer($username)
{
	$sql = "select username from e_users where username='".mysql_real_escape_string($username)."'";
	if(db_getFirstResult($sql) != "")
		return true;
	else
		return false;	
}


function site_header()
{
	global $server_root,$prepath, $page_title;
	
?>

<html class="no-js">

<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<title>MIT SWE | Resume Upload</title>
		<meta name="keywords" content="MIT SWE, MIT, Society of Women Engineers, MIT Society of Women Engineers" />
		<meta name="description" content="Massachusetts Institute of Technology Society of Women Engineers is the largest diversity student organization on campus and aims to inspire younger generations about engineering, encourage the notion of diversity in engineering, and determine and advocate for the needs of women engineers at MIT and in the professional world." />
		<!-- global styles -->
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/footer-distributed.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="../../css/mt-global.css">
		<link rel="stylesheet" href="../../css/style_form.css">
		<!-- page specific styles -->
		<link rel="stylesheet" href="../css/styles_members.css">

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

</head>

<body class="page  page--home">

	<header class="siteHeader  strip">
	    <div class="wrapper">
	        <!-- Logo -->
	        <a href="http://swe.mit.edu/home/" class="branding"><img src="../../images/SWE_Logo_MIT-horz.png" alt="(MITSWE) SWE Logo"></a>

	         <!-- Primary site Nav -->
	        <a href="#siteNav" class="hamburger  js-menuLink">
			    <span class="hamburger-bun  hamburger-bun--top"></span>
			    <span class="hamburger-patty"></span>
			    <span class="hamburger-bun  hamburger-bun--btm"></span>
			</a>

		<nav id="siteNav" class="navbar  primaryNav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		    <ul class="primaryNav-list">

		        <!-- START: Only visible on mobile -->
		       <!--  <li class="navItem  navItem--supportNumber">
		                            <a class="supportNumber" href="tel:+18775784000">877.578.4000</a>
		                    </li> -->
		        <!-- <li class="navItem  navItem--login">
		            <a class="loginLink" href="https://ac.mediatemple.net/login.mt?redirect=home.mt"><span>Members Only</span></a>
		        </li> -->
		        <!-- END: Only visible on mobile -->


		       	<!-- Web Hosting -->
		        <!--  About -->
		        <li class="navItem  hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="http://swe.mit.edu/about/"><span>About</span></a>

		            <div class="navDropdown">
		                <ul class="nav  nav--stacked" style="clear:both">
		                    <li class="navItem"><a href="http://swe.mit.edu/about/board">Board Members</a></li>
		                    <li class="navItem"><a href="http://swe.mit.edu/about/national_swe_membership">National SWE</a></li>
		                </ul>
		            </div>
		        </li>

		        <!-- Outreach -->
		        <li class="navItem  hasDropdown  js-hasDropdown ">
		            <a class="js-dropdownTrigger" href="http://swe.mit.edu/outreach/"><span >Outreach</span></a>

					 <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="http://swe.mit.edu/outreach/elementary_school">Elementary School</a></li> 
		                    <li class="navItem"><a href="http://swe.mit.edu/outreach/middle_school">Middle School</a></li> 
		                    <li class="navItem"><a href="http://swe.mit.edu/outreach/high_school">High School</a></li> 
		                    <li class="navItem"><a href="http://swe.mit.edu/outreach/special_events">Special Events</a></li>  
		                    <li class="navItem"><a href="http://swe.mit.edu/outreach/resources">Resources</a></li>

		                </ul>
		            </div>
		        </li>
		        <!-- Corporate -->
		        <li class="navItem hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="http://swe.mit.edu/corporate/"><span>Corporate</span></a>
		            <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="http://swe.mit.edu/corporate/banquet">Career Fair Banquet</a></li>
		                    <li class="navItem"><a href="http://swe.mit.edu/corporate/resume_database">Resume Database</a></li>
		                </ul>
		            </div>
		        </li>

		        <!-- Calendar -->
		        <li class="navItem ">
		            <a href="http://swe.mit.edu/calendar/"><span>Calendar</span></a>
		        </li>

		        <!--  News -->
		        <li class="navItem ">
		            <a href="http://swe.mit.edu/news/"><span>News</span></a>
		        </li>

		        <!-- Contact Us -->
		        <li class="navItem ">
		            <a href="http://swe.mit.edu/contact/"><span>Contact Us</span></a>
		        </li>		        

				<!-- Members Only -->
		        <li class="navItem  hasDropdown  js-hasDropdown">
		            <a class="js-dropdownTrigger" href="http://swe.mit.edu/members/"><span>For Members</span></a>
		            <div class="navDropdown">
		                <ul class="nav  nav--stacked">
		                    <li class="navItem"><a href="../../members/section_resources"><span>Section Resources</span></a></li>
		                    <li class="navItem"><a href="../../members/resume_upload"><span>Resume Upload</span></a></li>
		                    <li class="navItem"><a href="http://swe.mit.edu/wiki"><span>Board Wiki</span></a></li>
		                </ul>
		            </div>
		        </li>
		    </ul>
		</nav>


<!-- 
        <div id="siteNav" class="navbar  primaryNav rightNav">
		    <ul class="nav  headerNav  u-pullRight">
				
		           
	        </ul>
        </div>
	    </div> <!-- /.wrapper -->
	    <div class="navDropdown--background offPage"></div> 


	</header> <!-- /.siteHeader --> 

<?php	

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

function print_mainform()
{
	global $student_dept,$student_degree,$student_jobtype,$student_firstname,$student_lastname,$student_email,$student_year,$server_root;
	$self=$_SERVER['PHP_SELF'];
	
	?>
	<form method='POST' enctype="multipart/form-data" action="<?php echo $self; if($_SERVER['QUERY_STRING']>' '){echo '?'.$_SERVER['QUERY_STRING'];} ?>">
	
	
	<div id='input_box'>
	<table>
		<tr><td><b>All Fields Required<b></td><td></tr>
		<tr><td>First Name:</td><td><input type='text' name='firstname' value='<?php echo $student_firstname;?>'></td></tr>
		<tr><td>Last Name:</td><td><input type='text' name='lastname' value='<?php echo $student_lastname;?>'></td></tr>
		<tr><td>Email:</td><td><input type='text' name='email' value='<?php echo $student_email;?>'></td></tr>
		<tr><td>Major:</td><td><?php print_department_dropdown($student_dept);?> </td></tr>
		<tr><td>Degree:</td><td><?php print_degree_dropdown($student_degree);?> </td></tr>
		<tr><td>Graduation Year:</td><td><?php print_year_dropdown($student_year);?> </td></tr>
		<tr><td>Desired Job Type:</td><td><?php	print_jobtype_dropdown($student_jobtype);?> </td></tr>
		<tr><td>Upload Resume PDF: </td><td><input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
			<input type="file" name="resume_file" size="30" /> <span class="form_detail">Title Format: FirstLast.pdf (Max Size 2MB)</span><br>
		</td></tr>
		<tr><td>Would you like your resume <br>to be visible to other MIT SWE <br>National Members in a Sample <br>Resume Folder that will be <br>available on our website?</td><td><?php	print_visible_dropdown($student_visible);?> </td></tr>
	</table>
	</div>
	
	<input type='submit' value='Submit' name='submit'>
	</form>
	<?php
}

function print_department_dropdown($dept)
{
	?>

	<?php

	echo "<select name=\"department\"><option value=''></option>";
	$sql = "SELECT name,abbreviation FROM departments ORDER BY sort_order";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
  	   	if(trim($dept) === trim($row[1]))
	    	echo ' selected="selected" ';
    	echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}

function print_degree_dropdown($degree)
{
	?>

	<?php
	echo "<select name=\"degree\"><option value=''></option>";
	$sql = "SELECT name,abbreviation FROM degrees ORDER BY sort_order";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(trim($degree) == trim($row[1]))
	   		echo ' selected="selected" ';
  		echo '>'.$row[0].'&nbsp;</option>';
	}
	echo "</select>";
}

function print_year_dropdown($year)
{
	?>
	
	<?php	
	
	echo "<select name=\"year\"><option value=''></option>";
	$year = date("Y");
	for ($i=-1;$i<6;$i++){
	  echo '<option value="'.($year+$i).'"';
	  if($student_year === ((string)($year+$i))){ echo ' selected="selected" ';}
	  echo '>'.($year+$i).'&nbsp;</option>';
	}
	echo "</select>";
}

function print_visible_dropdown($visible)
{
	?>
	
	<?php	
	
	$listnoyes = array("No","Yes");
	echo "<select name=\"visible\"><option value=''></option>";
	
	for ($i=0;$i<2;$i++){
		echo '<option value="'.$listnoyes[$i].'"';
		if($student_visible == $listnoyes[$i]){ echo ' selected="selected" ';}
		echo '>'.$listnoyes[$i].'&nbsp;</option>';
	}
	
	echo "</select>";
}

function print_jobtype_dropdown($jobtype)
{	
	?>

	<?php
	echo "<select name=\"jobtype\"><option value=''></option>";
	$sql = "SELECT name,abbreviation FROM jobtypes ORDER BY sort_order";
	$res = db_query($sql);
	
	while ($row = db_fetch_array($res)){
  		echo '<option value="'.$row[1].'"';
	    if(trim($degree) == trim($row[1]))
	   		echo ' selected="selected" ';
  		echo '>'.$row[0].'&nbsp;</option>';
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