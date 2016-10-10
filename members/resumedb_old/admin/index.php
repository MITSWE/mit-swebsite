<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_admin_page($_SERVER['SCRIPT_NAME']);
// look up user

$page_title = "Admin Page";
site_header();

?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 

<?
if($_SESSION['logged'] && $username != "")
{
	?>
	<ul>
		<li><A HREF='student_admin.php'>Manage students</a>
		<li><A HREF='employer_admin.php'>Manage Employers</a>
	<ul>
	<?php
}

include ("$prepath/src/footer.php");	
?>	
