<?php
session_start(); 
$prepath="../../members";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_employer_page($_SERVER['SCRIPT_NAME']);
function process_search($post_arr)
{
	//if search is "All" or blank, default to "all" and clear the search array
	if(is_array($post_arr))
	{
		if(in_array("all",$post_arr))
			$array_ret = array();
		else
			$array_ret = $post_arr;
	}
	else
		$array_ret = array();
	return $array_ret;	
}

if(isset($_POST['submit']))
{
}
$page_title = "Resume Search";
site_header();

if(isset($_POST['submit']))
{
	$s_majors = process_search($_POST['s_majors']);
	$s_degrees = process_search($_POST['s_degrees']);
	$s_jobtypes = process_search($_POST['s_jobtypes']);
	$s_years = process_search($_POST['s_years']);
	$tables = array();
	$search = "";
	$match = "";

/***********************************
**********Search Majors*************
************************************/
	if(!empty($s_majors))
	{	
		$tables[]= "s_info_new";
		$search .= "(";
		foreach($s_majors as $major)
		{
			$search .= "s_info_new.department = \"".mysql_real_escape_string($major)."\" OR ";	
		}
		$search = rtrim($search," OR ") .")";
		if(!strstr($match,"s_info_new.degree"))
			$match .= "s_info_new.degree = degrees.abbreviation AND ";

	}
	else
		$_POST['s_majors'] = array("all"); //select ALL by default

/***********************************
**********Search Degrees************
************************************/
	if(!empty($s_degrees))
	{
		$tables[]="s_info_new";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_degrees as $degree)
		{
			$search .= "s_info_new.degree = \"".mysql_real_escape_string($degree)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_info_new"))
			$match .= "s_info_new.degree = degrees.abbreviation AND ";

	}
	else
		$_POST['s_degrees'] = array("all"); //select ALL by default

/***********************************
**********Search Years*************
************************************/
	if(!empty($s_years))
	{
		$tables[]="s_info_new";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_years as $year)
		{
			$search .= "s_info_new.year = \"".mysql_real_escape_string($year)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_info_new"))
			$match .= "s_info_new.s_id = s_users_degrees.s_users_id AND ";

	}		
	else
		$_POST['s_years'] = array("all"); //select ALL by default

/***********************************
**********Search Jobtypes***********
************************************/
	if(!empty($s_jobtypes))
	{
		$tables[]="s_users_jobtypes";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_jobtypes as $jobtype)
		{
			$search .= "s_users_jobtypes.jobtype = \"".mysql_real_escape_string($jobtype)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_users_jobtypes"))
			$match .= "s_users.s_users_id = s_users_jobtypes.s_users_id AND ";
	}
	else
		$_POST['s_jobtypes'] = array("all"); //select ALL by default

/***********************************
**********Valid Resumes only********
************************************/	
		$tables[]="s_info_new";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";

		$search .= "s_info_new.file_location is not null)";
		if(!strstr($match,"s_resumes"))
			$match .= "s_users.s_users_id = s_resumes.s_users_id AND ";		

// Construct the query		
	$sql = "select distinct s_info_new.s_id from s_info_new";
	$tables = array_unique($tables); //remove duplicate tables
	if(!empty($tables))
	{
		foreach($tables as $table)
			$sql .= ", $table";	
	}
	$match = rtrim($match, " AND ");
	
	if(!empty($search) && !empty($match))
	$sql .= " where ($search) AND ($match)";
//	echo $sql."<br><br>";
//	print_r(db_getArray($sql));
	$res = db_query($sql);

	?>
	<h4>Search Results</h4>
	<table id='hor-minimalist-b'>
		<tbody style="margin-left: 50px;">
	<?php
	if(db_numrows($res)>0)
	{
		while($row = mysql_fetch_array($res))
		{
			$user_id = $row['s_id'];
			$row1 = db_getRow("select * from s_info_new where s_id='$user_id'");
			$location = $row1['file_location'];
			
			$majors = db_getFirstResult("select group_concat(departments.name SEPARATOR ', ') from s_info_new, departments where s_info_new.department = departments.abbreviation and s_info_new.s_id = '$user_id'");
			
			echo "<table style=\"font-size:10pt;\"><tr>";
			echo "<td colspan='2'><strong><font color='black' size=\"4\">".$row1['firstname']." ".$row1['lastname']."</font></strong></td>";
			echo "<tr><td valign='top' width='100'><font color='black'><b>Email:</b></font></td><td><a href=\"mailto:".$row1['email']."\">".$row1['email']."</a></td></tr>";
			echo "<tr><td valign='top'><font color='black'><b>Major:</b></font></td><td>$major</td></tr>";
			echo "<tr><td>Resume:</td><td><A HREF='$prepath/$location' target='_blank'>Download</a> </td></tr>";
			echo "</table><hr>";
			
		}
	}
	else
		echo "<tr><td>No Match Found</td></tr>";

	echo "</tbody></table><br>";
	
}
	?>
	<form name='myForm' method='POST' action='<?php echo $self;?>'>
	<h4>Search Criteria</h4>
	<div id='input_box'>
		Shift or Apple + Click to select multiple entries
		<table>
			<tr><td>Majors: </td><td><?php print_department_dropdown($_POST['s_majors']);?> </td></tr>
			<tr><td>Degrees: </td><td><?php print_degree_dropdown($_POST['s_degrees']);?> </td></tr>
			<tr><td>Job Types: </td><td><?php print_jobtype_dropdown($_POST['s_jobtypes']);?> </td></tr>
			<tr><td>Graduation Years: </td><td><?php print_year_dropdown($_POST['s_years']);?> </td></tr>
		</table>
	</div>
	
	<?php	

	echo "<input type='submit' name='submit' value='Submit'>";
	echo "</form>";
	
	include ("$prepath/src/footer.php");	

?>	
