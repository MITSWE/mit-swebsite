<?php
session_start(); 
$prepath="../../members";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

// $username = protect_employer_page($_SERVER['SCRIPT_NAME']);

// function process_search($post_arr)
// {
// 	//if search is "All" or blank, default to "all" and clear the search array
// 	if(is_array($post_arr))
// 	{
// 		if(in_array("all",$post_arr))
// 			$array_ret = array();
// 		else
// 			$array_ret = $post_arr;
// 	}
// 	else {
// 		$array_ret = array();
// 	}
// 	return $array_ret;	
// }

function process_search($post_val) {
	return $post_val;
}


$page_title = "Resume Search";
site_header(); ?>
	<div style="height:13vh;"></div>
	<form style="margin-left:5vw;" name='myForm' method='POST' action='<?php echo $self;?>'>
	Search Criteria
	<div id='input_box'>
		<h4>Refine your search using the following optional choices.</h4>
		<table>
			<tr><td>Major: </td><td style="width:10vh;"><?php print_department_dropdown($search_department);?> </td></tr>
			<tr><td>Degree: </td><td><?php print_degree_dropdown($search_degree);?> </td></tr>
			<tr><td>Job Type: </td><td><?php print_jobtype_dropdown($search_jobtype);?> </td></tr>
			<tr><td>Graduation Year: </td><td><?php print_year_dropdown($search_year);?> </td></tr>
		</table>
	</div>

<?php	

	echo "<input type='submit' name='submit' value='Submit'>";
	echo "</form>";

if(isset($_POST['submit']))
{	
	$s_major = process_search($_POST['department']);
	$s_degree = process_search($_POST['degree']);
	$s_jobtype = process_search($_POST['jobtype']);
	$s_year = process_search($_POST['year']);
	$search = "";
	//$match = "";

/***********************************
**********Search Majors*************
************************************/
	if(!empty($s_major))
	{	
		$search .= "(s_info_new.department = \"".mysql_real_escape_string($s_major)."\")";	
	}
	else
		$_POST['s_major'] = array("all"); //select ALL by default

/***********************************
**********Search Degrees************
************************************/
	if(!empty($s_degree))
	{
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";

		$search .= "s_info_new.degree = \"".mysql_real_escape_string($s_degree)."\")";	
	}
	else
		$_POST['s_degree'] = array("all"); //select ALL by default

/***********************************
**********Search Years*************
************************************/
	if(!empty($s_year))
	{
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		
		$search .= "s_info_new.year = \"".mysql_real_escape_string($s_year)."\")";
	}		
	else
		$_POST['s_year'] = array("all"); //select ALL by default

/***********************************
**********Search Jobtypes***********
************************************/
	if(!empty($s_jobtype))
	{
		if(!empty($search))
			$search .= " AND (";
		else
			$search .= "(";
		
		$search .= "s_info_new.jobtype = \"".mysql_real_escape_string($s_jobtype)."\")";
	}
	else
		$_POST['s_jobtype'] = array("all"); //select ALL by default

/***********************************
**********Valid Resumes only********
************************************/	
	if(!empty($search))
		$search .= " AND (";
	else
		$search .= "(";

	$search .= "s_info_new.file_location is not null)";	

// Construct the query		
	$sql = "select distinct s_info_new.s_id from s_info_new";
	if(!empty($search))
		$sql .= " where ($search)";
	//	print_r(db_getArray($sql));

	$res = db_query($sql);

	?>



	<h2>-----------------------------</h2>
	<div id="search_res" style="margin-left: 5vw;">

	<h4 style="font-size: 30px">Search Results</h4>
	<table id='hor-minimalist-b'>
		<tbody>
	<?php
	if(db_numrows($res)>0)
	{
		while($row = mysql_fetch_array($res))
		{
			$user_id = $row['s_id'];
			$row1 = db_getRow("select * from s_info_new where s_id='$user_id'");
			$location = $row1['file_location'];
			
			$major = db_getFirstResult("select departments.name from s_info_new, departments where s_info_new.department = departments.abbreviation and s_info_new.s_id = '$user_id'");
			
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
	?> </div>
<? include ("$prepath/src/footer.php");	?>
	
	
	