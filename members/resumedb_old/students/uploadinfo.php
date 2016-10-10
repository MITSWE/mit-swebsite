<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

date_default_timezone_set('America/New_York');
$upload_dir = "$prepath/resumes/".date("Y")."/";

if($_POST['submit'])
{
	$success = true;
	$s_firstname = trim($_POST['firstname']);
	$s_lastname = trim($_POST['lastname']);
	$s_email = trim($_POST['email']);
	
	$s_dept = $_POST['department'];
	$s_degree = $_POST['degree'];
	$s_year = $_POST['year'];
	$s_jobtype = $_POST['jobtype'];	
	
	//check submissions
	$student=array();
	$student['s_id'] = mysql_insert_id();

	if (!preg_match('/^(.){1,255}$/',$s_firstname))
	{
	    $_SESSION['feedback'] = "ERROR - The <span class=\"error_field\">first name</span> is required and must be no more than 50 characters long.";
	    $success = false;
	}
	else
		$student['firstname']=$s_firstname;
	
	if (!preg_match('/^(.){1,255}$/',$s_lastname))
	{
	    $_SESSION['feedback'] = "ERROR - The <span class=\"error_field\">last name</span> is required and must be no more than 50 characters long.";
	    $success = false;
	}
	else
		$student['lastname']=$s_lastname;

	if (!preg_match('/^(.){0,255}$/',$s_email))
	{
	    $_SESSION['feedback'] .= "ERROR - The <span class=\"error_field\">email address</span> must be no more than 50 characters long.";
	    $success = false;
	}
	else
		$student['email'] = $s_email;

	$student['department'] = $s_dept;
	$student['degree'] = $s_degree;
	$student['year'] = $s_year;
	$student['jobtype'] = $s_jobtype;
	
	if($success)
	{
		
		// Handle file upload
		if($_FILES['resume_file']['name']!="")
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
			$filename = $s_firstname."_".$s_lastname."_".random_gen(8); //generated a random string
			$filename = $upload->upload("resume_file",$filename, $upload_dir);
			if($filename)
			{
				$sql_path= mysql_real_escape_string("/resumes/".date("Y")."/")."$filename";
				
				$student['file_location']=$sql_path;
				$_SESSION['feedback'] .= "Your resume was successfully uploaded<br>";

				db_query($sql);
			}
			else
			{
				$_SESSION['feedback'] .= $upload->getError()."<br>";	
			}
	
		}
		
		//insert student info into s_info_new table
		db_insert('s_info_new',$student,"s_id='$student_id'");

		$_SESSION['feedback'] .= "Thank you, your resume has been uploaded";
	}
	  
}
$page_title = "Resume Upload";
site_header();

?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 

<?

print_mainform();


//include ("$prepath/src/footer.php");	
?>	
