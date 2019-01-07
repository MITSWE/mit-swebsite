<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_student_page($_SERVER['SCRIPT_NAME']);
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
function parse_company_info($info,$table)
{
	$res = db_query("select * from $table");
	$arr = array();
	while($row=mysql_fetch_array($res))
	{	
		$name=$row['name'];
		$abbrev = $row['abbreviation'];
		$arr[$abbrev]=$name;
	}	
	if(in_array("all",$info))
		$output = array("All");
	else if(empty($info))
		$output = array("Not Specified");
	else
	{
		foreach($info as $item)
		{
			$output[] = $arr[$item];	
		}
	}
	return $output;
}

if(isset($_GET['id']))
{
	//get all company info
	//Company name
	$id = $_GET['id'];
	$row = db_getRow("select * from e_companies where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	$company_name = $row['name'];
	$company_description = $row['description'];
	
	$degrees = db_getArray("select abbrev from e_companies_degrees where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	$departments = db_getArray("select abbrev from e_companies_depts where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	$industries = db_getArray("select abbrev from e_companies_industries where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	$jobtypes = db_getArray("select abbrev from e_companies_jobtypes where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	$citizenship = db_getArray("select abbrev from e_companies_citizenship where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	
	$degrees = parse_company_info($degrees,"degrees");
	$departments = parse_company_info($departments,"departments");
	$industries= parse_company_info($industries,"industries");
	$jobtypes = parse_company_info($jobtypes,"jobtypes");
	$citizenship = parse_company_info($citizenship,"citizenship");
	
	$page_title = "Company Profile";
	site_header();
	echo "<h4>$company_name</h4>";
	echo $company_description ."<br>";
	
	echo "<table id='hor-minimalist-b'>";
	echo "<tr><td>Industries:</td><td>";
	foreach($industries as $industry)
		echo $industry."<br>";
	echo "</td></tr>";

	echo "<tr><td>Majors Hiring:</td><td>";
	foreach($departments as $department)
		echo $department."<br>";
	echo "</td></tr>";

	echo "<tr><td>Degrees Hiring:</td><td>";
	foreach($degrees as $degree)
		echo $degree."<br>";
	echo "</td></tr>";

	echo "<tr><td>Jobtypes:</td><td>";
	foreach($jobtypes as $jobtype)
		echo $jobtype."<br>";
	echo "</td></tr>";

	echo "<tr><td>Citizenship:</td><td>";
	foreach($citizenship as $item)
		echo $item."<br>";
	echo "</td></tr>";
	echo "</table>";
	
	echo "<br><h4>Contact Information</h4>";
	$res=db_query("select * from e_users where e_companies_id=\"".mysql_real_escape_string($id)."\"");
	while($row=mysql_fetch_array($res))
	{
		$name = $row['firstname']." ".$row['lastname'];
		$email = $row['username'];
		$phone = $row['phone'];	
		echo "<table>";
		echo "<tr><td>Name:</td><td>$name</td></tr>";
		echo "<tr><td>Email:</td><td>$email</td></tr>";
		echo "<tr><td>Phone:</td><td>$phone</td></tr>";
		echo "</table><hr>";
	}
	include ("$prepath/src/footer.php");	
	exit;
	
}



$page_title = "Company Search";
site_header();

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
		$tables[]= "e_companies_depts";
		$search .= "(";
		foreach($s_majors as $major)
		{
			$search .= "e_companies_depts.abbrev = \"".mysql_real_escape_string($major)."\" OR ";	
		}
		$search = rtrim($search," OR ") .")";
		
		if(!strstr($match,"e_companies_degrees"))
			$match .= "e_companies.e_companies_id = e_companies_depts.e_companies_id AND ";
	}
	else
		$_POST['s_majors'] = array("all"); //select ALL by default

/***********************************
**********Search Degrees************
************************************/
	if(!empty($s_degrees))
	{
		$tables[]="e_companies_degrees";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_degrees as $degree)
		{
			$search .= "e_companies_degrees.abbrev = \"".mysql_real_escape_string($degree)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"e_companies_degrees"))
			$match .= "e_companies.e_companies_id = e_companies_degrees.e_companies_id AND ";

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
			$search .= "e_companies_citizenship.abbrev = \"".mysql_real_escape_string($citizenship)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"e_companies"))
			$match .= "e_companies.e_companies_id = e_companies_citizenship.e_companies_id AND ";	//redundant, but for consistency
	}
	else
		$_POST['s_citizenship'] = array("all"); //select ALL by default


/***********************************
**********Search Jobtypes***********
************************************/
	if(!empty($s_jobtypes))
	{
		$tables[]="e_companies_jobtypes";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_jobtypes as $jobtype)
		{
			$search .= "e_companies_jobtypes.abbrev = \"".mysql_real_escape_string($jobtype)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"e_companies_jobtypes"))
			$match .= "e_companies.e_companies_id = e_companies_jobtypes.e_companies_id AND ";
	}
	else
		$_POST['s_jobtypes'] = array("all"); //select ALL by default

/***************************************
**********Search Industries*************
****************************************/	
	if(!empty($s_industries))
	{
		$tables[]="e_companies_industries";
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		foreach($s_industries as $industry)
		{
			$search .= "e_companies_industries.abbrev = \"".mysql_real_escape_string($industry)."\" OR ";	
		}
		$search = rtrim($search," OR ") . ")";
		if(!strstr($match,"e_companies_industries"))
			$match .= "e_companies.e_companies_id = e_companies_industries.e_companies_id AND ";
	}
	else
		$_POST['s_industries'] = array("all"); //select ALL by default

// Construct the query		
	$sql = "select distinct e_companies.e_companies_id from e_companies";
	$tables = array_unique($tables); //remove duplicate tables
	if(!empty($tables))
	{
		foreach($tables as $table)
			$sql .= ", $table";	
	}
	$match = rtrim($match, " AND ");
	
	if(!empty($search) && !empty($match))
	$sql .= " where ($search) AND ($match) order by e_companies.name";
//	echo $sql."<br><br>";
//	print_r(db_getArray($sql));
	$res = db_query($sql);

	?>
	<h4>Search Results</h4>
	<table id='hor-minimalist-b'>
		<thead><tr>
			<th width='100' align='left'>Company</th>
		</tr></thead>
		<tbody>
	<?php
	if(db_numrows($res)>0)
	{
		while($row = mysql_fetch_array($res))
		{
			$user_id = $row['e_companies_id'];
			$row1 = db_getRow("select * from e_companies where e_companies_id='$user_id'");
			
			echo "<tr>";
			echo "<td><a href='$self?id=$user_id' target='_blank'>".$row1['name']."</a></td>";
			echo "</tr>";
		}
	}
	else
		echo "<tr><td>No Match Found</td></tr>";

	echo "</table><br>";
	
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
