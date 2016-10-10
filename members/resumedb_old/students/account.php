<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

date_default_timezone_set('America/New_York');
$upload_dir = "$prepath/resumes/".date("Y")."/";

$user = protect_student_page($_SERVER['SCRIPT_NAME']);

// look up user
$sql = "SELECT * FROM s_users WHERE username = '$user'";
$result = db_query($sql);

if ($result && (db_numrows($result) === 1)){
  // found user

  $new_student = false;

  $myrow = db_fetch_array($result);
  $student_firstname = $myrow['firstname'];
  $student_lastname = $myrow['lastname'];
  $student_email = $student_username.'@mit.edu';
  $student_alt_email = $myrow['alt_email'];
  $student_citizenship = $myrow['citizenship'];
  $student_id = $myrow['s_users_id'];
  $student_userlevel = $myrow['userlevel'];

  $sql = "SELECT s_users_degrees_id,department,degree,year FROM ".
    "s_users_degrees WHERE s_users_id='$student_id'";
 
  $result = db_query($sql);
  if ($result && (db_numrows($result)>0)){
    while($row = db_fetch_array($result)){
      $s_depts[] = $row['department'];
      $s_degrees[] = $row['degree'];
      $s_years[] = $row['year'];

    }
  }

  $sql = "SELECT s_users_jobtypes_id,jobtype FROM ".
    "s_users_jobtypes WHERE s_users_id='$student_id'";
  $result = db_query($sql);
  if ($result && (db_numrows($result)>0)){
    while($row = db_fetch_row($result)){
      $s_jobtypes[] = $row[1];
    }
  }

  $sql = "SELECT s_users_industries_id,industry FROM ".
    "s_users_industries WHERE s_users_id='$student_id'";
  $result = db_query($sql);
  if ($result && (db_numrows($result)>0)){
    while($row = db_fetch_row($result)){
      $s_industries[] = $row[1];
    }
  }
}
else{
  // did not find user
  // attempt to fetch name from ldap...
  $student_id = '';
  $new_student = true;
}

if($_POST['submit'])
{
	$success = true;
	$student_firstname = trim($_POST['firstname']);
	$student_lastname = trim($_POST['lastname']);
	$student_alt_email = trim($_POST['student_alt_email']);  
	$student_citizenship = $_POST['student_citizenship'];
	
	$s_depts = $_POST['s_depts'];
	$s_degrees = $_POST['s_degrees'];
	$s_years = $_POST['s_years'];
	
	$s_jobtypes = $_POST['s_jobtypes'];	
	$s_industries = $_POST['s_industries'];

	
	
	//update which tables?
	$s_users=array();
	if (!preg_match('/^(.){1,255}$/',$student_firstname))
	{
	    $_SESSION['feedback'] = "ERROR - The <span class=\"error_field\">first name</span> is required and must be no more than 255 characters long.";
	    $success = false;
	}
	else
		$s_users['firstname']=$student_firstname;
		

	
	if (!preg_match('/^(.){1,255}$/',$student_lastname))
	{
	    $_SESSION['feedback'] = "ERROR - The <span class=\"error_field\">last name</span> is required and must be no more than 255 characters long.";
	    $success = false;
	}
	else
		$s_users['lastname']=$student_lastname;

	if (!preg_match('/^(.){0,255}$/',$student_alt_email))
	{
	    $_SESSION['feedback'] .= "ERROR - The <span class=\"error_field\">2nd email address</span> must be no more than 255 characters long.";
	    $success = false;
	}
	$s_users['alt_email'] = $student_alt_email;
	
	if ($_SESSION['admin'] && isset($_POST['userlevel']))
		$student_userlevel = $_POST['userlevel'];
	else
		$student_userlevel = "student";
	
	$s_users['access']=$student_userlevel;
	$s_users['citizenship']= $student_citizenship;
	$s_users['alt_email'] = $student_alt_email;
	
	if($success)
	{
		//update users table
		db_update('s_users',$s_users,"s_users_id='$student_id'");
		
		//update degrees table
		//Delete all old, and refresh new
		if(is_array($s_degrees))
		{			
			$sql = "delete from s_users_degrees where s_users_id='$student_id'";
			db_query($sql);
			$count=0;
			foreach($s_degrees as $degree)
			{	
				if($s_depts[$count] != "" && $s_years[$count]!= "" && $degree!="")
				{
					$sql_arr=array("s_users_id"=>$student_id,
							"department"=>$s_depts[$count],
							"degree"=>$s_degrees[$count],
							"year"=>$s_years[$count]);
					db_insert("s_users_degrees",$sql_arr);
				}
				$count++;
			}
		}		
		
		//update Jobs table
		//Delete all old, and refresh new
		if(is_array($s_jobtypes))
		{
			$sql = "delete from s_users_jobtypes where s_users_id='$student_id'";
			db_query($sql);
			$count=0;
			foreach($s_jobtypes as $jobtype)
			{	
				if($jobtype != "")
				{
					$sql_arr=array("s_users_id"=>"$student_id",
							"jobtype"=>"$jobtype");
					db_insert("s_users_jobtypes",$sql_arr);
				}
			}
		}		
		
		//update Industries table
		//Delete all old, and refresh new
		
		if(is_array($s_industries))
		{
			$sql = "delete from s_users_industries where s_users_id='$student_id'";
			db_query($sql);
			$count=0;
			foreach($s_industries as $industry)
			{	
				if($industry != "")
				{
					$sql_arr=array("s_users_id"=>"$student_id",
							"industry"=>"$industry");
					db_insert("s_users_industries",$sql_arr);
				}
			}
		}
		
		// Handle file upload
		if(isset($_POST['delete_resume']))
		{
			$location = resume_uploaded($user);
			if(is_file("$prepath/$location"))
				unlink("$prepath/$location");
			$sql = "delete from s_resumes where s_users_id = '$student_id'";
			db_query($sql);	
			$_SESSION['feedback'] .= "Your resume was deleted.<BR>";
		}
		else if($_FILES['resume_file']['name']!="")
		{
			require("$prepath/src/FileUpload.class.php");
			if(!is_dir($upload_dir))
			{
				mkdir($upload_dir);	
			}
			
			// Preferences
			$upload = new FileUpload();
			$upload->setMaxFilesize(2000000);
//			$upload->setRejectExtensions('doc,png,jpg'); // leave blank or remove to accept all extensions (except .php)
//			$upload->setAcceptableTypes('pdf'); // leave blank or remove to accept all files (except .php)
			$upload->setDefaultExtension("pdf");
			$upload->setOverwriteMode(1);
			
			// this is the only way to accept files ending with 'php' - I don't reccomend it 
			// $upload->acceptPHP(true); 
			
		
			// UPLOAD single file
			$filename = $user."_".random_gen(8); //generated a random string
			$filename = $upload->upload("resume_file",$filename, $upload_dir);
			if($filename)
			{
				$sql_path= mysql_real_escape_string("/resumes/".date("Y")."/")."$filename";
				if($location = resume_uploaded($user))
				{
					//delete old file
					if(is_file("$prepath/$location"))
						unlink("$prepath/$location");
					$sql = "update s_resumes set file_location='$sql_path' where s_users_id='$student_id'";	
					$_SESSION['feedback'] .= "Your resume was successfully updated <br>";
				}
				else
				{
					$sql = "insert into s_resumes (s_users_id,file_location) VALUES (\"$student_id\",\"$sql_path\")";
					$_SESSION['feedback'] .= "Your resume was successfully uploaded <br>";
				}
				db_query($sql);
				

			}
			else
			{
				$_SESSION['feedback'] .= $upload->getError()."<br>";	
			}
	
		}
		
		$_SESSION['feedback'] .= "Your profile has been saved";
	}
	  
}
$page_title = "Your Account";
site_header();

?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 

<?



if($_SESSION['logged'] && $user != "")
{
	print_mainform($user);
}

include ("$prepath/src/footer.php");	
?>	
