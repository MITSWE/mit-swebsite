<?php
session_start(); 
$prepath="..";
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

//Check for Expiration Date
$sql = "select expiration from e_companies, e_users where e_companies.e_companies_id = e_users.e_companies_id and e_users.username = '$username'";
$expiration = db_getFirstResult($sql);

if(date("Y-m-d") > $expiration)
{
	echo "Thank you for your interest in the MIT Society of Women Engineers Resume database. Our records show that your access has expired on ". date("M d, Y", strtotime($expiration)).".";
	echo "To register for continued access, please <A href='mailto: swe-exec@mit.edu?subject=Access to Resume Database'>contact us</a>.";
	include ("$prepath/src/footer.php");	
	exit;
}

if(isset($_POST['submit']))
{
	$s_majors = process_search($_POST['s_majors']);
	$s_degrees = process_search($_POST['s_degrees']);
	$s_jobtypes = process_search($_POST['s_jobtypes']);
	$s_years = process_search($_POST['s_years']);
	$s_industries = process_search($_POST['s_industries']);
	$s_citizenship = process_search($_POST['s_citizenship']);
	$tables = array();
	$search = "";
	$match = "";
/***********************************
**********Search Majors*************
************************************/
	if(!empty($s_majors))
	{	
		$tables[]= "s_users_degrees";
		$search .= "(";
		foreach($s_majors as $major)
		{
			$search .= "s_users_degrees.department = \"".mysql_real_escape_string($major)."\" OR ";	
		}
		$search = rtrim($search," OR ") .")";
		
		if(!strstr($match,"s_users_degrees"))
			$match .= "s_users.s_users_id = s_users_degrees.s_users_id AND ";
	}
	else
		$_POST['s_majors'] = array("all"); //select ALL by default

/***********************************
**********Search Degrees************
************************************/
	if(!empty($s_degrees))
	{
		$tables[]="s_users_degrees";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_degrees as $degree)
		{
			$search .= "s_users_degrees.degree = \"".mysql_real_escape_string($degree)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_users_degrees"))
			$match .= "s_users.s_users_id = s_users_degrees.s_users_id AND ";

	}
	else
		$_POST['s_degrees'] = array("all"); //select ALL by default

/***********************************
**********Search Citizenship********
************************************/
	if(!empty($s_citizenship))
	{
		//$tables[]="s_users";	//no need to include this, already included in base table
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_citizenship as $citizenship)
		{
			$search .= "s_users.citizenship = \"".mysql_real_escape_string($citizenship)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_users"))
			$match .= "s_users.s_users_id = s_users.s_users_id AND ";	//redundant, but for consistency
	}
	else
		$_POST['s_citizenship'] = array("all"); //select ALL by default

/***********************************
**********Search Years*************
************************************/
	if(!empty($s_years))
	{
		$tables[]="s_users_degrees";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_years as $year)
		{
			$search .= "s_users_degrees.year = \"".mysql_real_escape_string($year)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_users_degrees"))
			$match .= "s_users.s_users_id = s_users_degrees.s_users_id AND ";

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
**********Search Industries*************
************************************/	
	if(!empty($s_industries))
	{
		$tables[]="s_users_industries";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_industries as $industry)
		{
			$search .= "s_users_industries.industry = \"".mysql_real_escape_string($industry)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"s_users_industries"))
			$match .= "s_users.s_users_id = s_users_industries.s_users_id AND ";
	}
	else
		$_POST['s_industries'] = array("all"); //select ALL by default

/***********************************
**********Valid Resumes only********
************************************/	
		$tables[]="s_resumes";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";

		$search .= "s_resumes.file_location is not null)";
		if(!strstr($match,"s_resumes"))
			$match .= "s_users.s_users_id = s_resumes.s_users_id AND ";		

// Construct the query		
	$sql = "select distinct s_users.s_users_id from s_users";
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
			$user_id = $row['s_users_id'];
			$row1 = db_getRow("select * from s_users where s_users_id='$user_id'");
			$user = $row1['username'];
			
			$interests = db_getFirstResult("select group_concat(name SEPARATOR ', ') from s_users, s_users_industries,industries where s_users.s_users_id = s_users_industries.s_users_id and s_users_industries.industry = industries.abbreviation and s_users.s_users_id = '$user_id'");
			$majors = db_getFirstResult("select group_concat(departments.name SEPARATOR ', ') from s_users, s_users_degrees, departments where s_users.s_users_id = s_users_degrees.s_users_id and s_users_degrees.department = departments.abbreviation and s_users.s_users_id = '$user_id'");
			
			echo "<table style=\"font-size:10pt;\"><tr>";
			echo "<td colspan='2'><strong><font color='black' size=\"4\">".$row1['firstname']." ".$row1['lastname']."</font></strong></td>";
			echo "<tr><td valign='top' width='100'><font color='black'><b>Email:</b></font></td><td><a href=\"mailto:".$row1['username']."@mit.edu\">".$row1['username']."@mit.edu</a></td></tr>";
			echo "<tr><td valign='top'><font color='black'><b>Industries:</b></font></td><td>$interests</td></tr>";
			echo "<tr><td valign='top'><font color='black'><b>Major(s):</b></font></td><td>$majors</td></tr>";
			if($location = resume_uploaded($user))
			{
				echo "<tr><td>Resume:</td><td><A HREF='$server_root/resumedb$location' target='_blank'>Download</a> </td></tr>";
			}
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
		<table><tr>
			<td>Majors: </td><td><?php print_majors_selection($_POST['s_majors']);?> </td></tr>
			<tr><td>Degrees: </td><td><?php print_degrees_selection($_POST['s_degrees']);?> </td></tr>
			<tr><td>Job Types: </td><td><?php print_jobtype_selection($_POST['s_jobtypes']);?> </td>
			<tr><td>Graduating: </td><td><?php print_years_selection($_POST['s_years']);?> </td>
			<tr><td>Industries: </td><td><?php print_industries_selection($_POST['s_industries'],5);?> </td>
			<tr><td>Citizenship: </td><td><?php print_citizenship_selection($_POST['s_citizenship']);?> </td>
			</tr>
		</table>
	</div>
	
	<?php	

	echo "<input type='submit' name='submit' value='Submit'>";
	echo "</form>";
	
	include ("$prepath/src/footer.php");	

?>	
