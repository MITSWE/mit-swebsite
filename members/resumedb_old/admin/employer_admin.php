<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_admin_page($_SERVER['SCRIPT_NAME']);
// look up user

$page_title = "Employer Admin";
if(isset($_POST['delete']))
{
	$ids = $_POST['checked_values'];
	$ids = explode(",",$ids);
	foreach($ids as $id)
	{
		//delete user from all tables, and remove their resume file if it exists
		$tables=array("e_users","e_companies","e_companies_degrees","e_companies_depts","e_companies_industries","e_companies_jobtypes");
		foreach($tables as $table)
		{
			$sql = "delete from $table where e_companies_id = '$id'";	
			db_query($sql);
		}
	}
}



site_header();
?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/validate.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 
<SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>

<?

if($_SESSION['logged'] && $username != "")
{
	//fetch all employer info, and print, Setup pagnation as well
	if(!isset($_GET['sort']))
		$order='name';	
	else
	{
		$sort = $_GET['sort'];
		if($sort=='name')
			$order = "name";
		if($sort=='expiration')
			$order = "expiration desc";

	}
echo "<A HREF='employer_add.php'>[Add Company]</a><br>";		
/***************************************************
****************Setup Pagnation*********************
/***************************************************/	
	if(!isset($_POST['view_per_page']))
		$view_per_page = 25;
	else
		$view_per_page = $_POST['view_per_page'];


	if(!isset($_GET['page'])||$_GET['page']=='0' || $_GET['page']=="")
		$page = 1;
	else
		$page=$_GET['page'];
			
	$start_record=($page-1)*$view_per_page;
	$end_record = $start_record+$view_per_page;
	$sql = "select count(name) from e_companies";
	$num_companies=db_getFirstResult($sql);
	$num_pages = ceil($num_companies/$view_per_page);
	
	if($page=='0')
		$prev_page='';
	else
		$prev_page = $page-1;

	if($page==$num_pages)
		$next_page='';
	else
		$next_page = $page+1;
		
		
	$pages = "<A HREF='$self?page=$prev_page$server_query#title'>&lt;&lt; Prev</a> | ";
	for($i=1;$i<=$num_pages;$i++)		//Generate pagnation links
	{
		$query="page=$i".$server_query;
		if($page == $i)
			$pages .= "<A HREF='$self?$query#title'><font color='green'>$i</font></a> ";	
		else
			$pages .= "<A HREF='$self?$query#title'>$i</a> ";	
	}
	$pages .= " | <A HREF='$self?page=$next_page$server_query#title'>&gt;&gt; Next</a>";
	echo $pages;	
}

/***************************************************
****************Do Query, print table***************
/***************************************************/		
$sql = "select name, expiration, paid_amount,e_companies_id from e_companies order by $order limit $start_record, $view_per_page";
$res = db_query($sql);
echo "<form name='myForm' method='POST' action='$self'>";
?>

	<table id='hor-minimalist-b'>
		<thead><tr>
			<th width='100' align='left'><A class='title' HREF='<?php echo "$self?sort=name$server_query#title" ?>'> Company Name</th>
			<th width='150' align='left'><A class='title' HREF='<?php echo "$self?sort=expiration$server_query#title";?>'>Access Expiration</a></th>
			<th width='100' align='left'>Status</th>
			<th width='50' align='left'>Select</th>
		</tr></thead>
		<tbody>
	<?php

while($row = mysql_fetch_array($res))
{
	$company_id = $row['e_companies_id'];
	echo "<tr>";
	echo "<td><a href='employer_add.php?id=".$row['e_companies_id']."'>".stripslashes($row['name'])."</a></td>";
	echo "<td>".$row['expiration']."</td>";
	echo "<td></td>";
	echo "<td><input type='checkbox' name='$company_id' id='$company_id' value='$company_id' onClick='getCheckBoxes()'></td>";
	echo "</tr>";
}
echo "</table>";
$message = "Are you sure you want to delete the selected companies? This action cannot be undone";
echo "<br><input type='submit' value='Delete companies' name='delete' onclick=\"return confirm_entry('".$message."')\" >";
echo "<input type='hidden' name='checked_values' id='checked_values'>";
echo "</form>";

 include ("$prepath/src/footer.php");	
?>	