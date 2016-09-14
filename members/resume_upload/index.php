<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";

date_default_timezone_set('America/New_York');
$upload_dir = "$prepath/resumes/".date("Y")."/";

if($_POST['submit'])
{	
	$student=array();
	$student['s_id'] = mysql_insert_id();
	
	$s_firstname = trim($_POST['firstname']);
	$s_lastname = trim($_POST['lastname']);

	$student['firstname'] = $s_firstname;
	$student['lastname'] = $s_lastname;
	$student['email'] = $_POST['email'];
	$student['department'] = $_POST['department'];
	$student['degree'] = $_POST['degree'];
	$student['year'] = $_POST['year'];
	$student['jobtype'] = $_POST['jobtype'];
	$student['visible'] = 0;
	if($_POST['visible'] == "Yes") { $student['visible'] = 1; }
		
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
		// $upload->setRejectExtensions('doc,png,jpg'); // leave blank or remove to accept all extensions (except .php)
		// $upload->setAcceptableTypes('pdf'); // leave blank or remove to accept all files (except .php)
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
			db_query($sql);
		}
		
		//insert student info into s_info_new table
		db_insert('s_info_new',$student,"s_id='$student_id'");
	}

	//do establish session
	//and check for the input fields obtained via $_POST
	if(isset($_POST['firstname']) && !empty($_POST['firstname'])){
	    header('location:success.html');
	}
	  
}
$page_title = "Resume Upload";
site_header();

?>

<div class="learn-more">
  <div class="form-container">
  	<h2 class="centered">Resume Upload</h2>
  	<center>
<?

print_mainform();

include ("$prepath/src/footer.php");
?>
