<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

$username = protect_admin_page($_SERVER['SCRIPT_NAME']);
// look up user

$page_title = "Student Admin";

if(isset($_POST['delete']))
{
	$ids = $_POST['checked_values'];
	$ids = explode(",",$ids);
	foreach($ids as $id)
	{
		//delete user from all tables, and remove their resume file if it exists
		if($id != "")
		{
			$sql = "delete from s_users where s_users_id='$id'";	
			db_query($sql);

			$sql = "delete from s_users_degrees where s_users_id='$id'";	
			db_query($sql);

			$sql = "delete from s_users_jobtypes where s_users_id='$id'";	
			db_query($sql);

			$sql = "delete from s_users_industries where s_users_id='$id'";	
			db_query($sql);
			
			$user = get_username($id);
			$location = resume_uploaded($user);
			if(is_file("$prepath/$location"))
				unlink("$prepath/$location");
			$sql = "delete from s_resumes where s_users_id = '$id'";
			db_query($sql);				
		}
	}
	$_SESSION['feedback'] = "User(s) deleted";
}

site_header();
?>
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/scripts.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/src/validate.js" /></script> 
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/prototype.js" /></script> <!-- include standard prototype library -->
<script type = "text/javascript" src = "<?php echo $prepath;?>/ajaxcore/AjaxCore.js" /></script><!-- include AjaxCore library --> 

<?
if($_SESSION['logged'] && $username != "")
{
/***************************************************
*************Setup AJAX and POST Handlers***********
/***************************************************/	
	include_once("ajaxHandler.class.php");
	$ajax = new AjaxHandler();
	echo $ajax->getJSCode();	
	
	if(!isset($_POST['view_per_page']))
		$view_per_page = 25;
	else
		$view_per_page = $_POST['view_per_page'];
	
	if(!isset($_GET['sort']))
		$order='lastname';	
	else
	{
		$sort = $_GET['sort'];
		if($sort=='username')
			$order = "username";	
		if($sort=='year')
			$order = "year desc, lastname";
		if($sort=='name')
			$order = "lastname";
	}
		
	echo "<a name='title'><h4>$page_title</h4></a>";
	echo "<b>Display:</b> ";
	echo "<a href='$self'>All students</a> | ";
	echo "<a href='$self?view=noresume'>Students with no resumes</a><br>";
	
	if(isset($_GET['view'])=='noresume')
		echo "<h5>Only showing students without resumes uploaded</h5>";

/***************************************************
****************Setup Pagnation*********************
/***************************************************/	
	if(!isset($_GET['page'])||$_GET['page']=='0' || $_GET['page']=="")
		$page = 1;
	else
		$page=$_GET['page'];
			
	$start_record=($page-1)*$view_per_page;
	$end_record = $start_record+$view_per_page;
	$sql = "select count(firstname) from s_users where is_confirmed='1'";
	$num_students=db_getFirstResult($sql);
	$num_pages = ceil($num_students/$view_per_page);
	
	if($page=='0')
		$prev_page='';
	else
		$prev_page = $page-1;

	if($page==$num_pages)
		$next_page='';
	else
		$next_page = $page+1;
		
	if(isset($_GET['sort']))
		$server_query="&sort=".$_GET['sort'];
	if(isset($_GET['view'])=="noresume")
		$server_query.="&view=".$_GET['view'];

		
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

/***************************************************
****************Do Query, print table***************
/***************************************************/		

	if(isset($_GET['view']))
	{
		if($_GET['view']=='noresume')
		{
			//get list of user_ids with no resumes
			$sql = "select firstname,lastname,username, s_users.s_users_id, max(year) as year from s_users left join (s_users_degrees) ". 
			"on (s_users.s_users_id = s_users_degrees.s_users_id) where is_confirmed='1' ".
			"AND s_users.s_users_id IN(select s_users.s_users_id from s_users left join (s_resumes) on (s_users.s_users_id = s_resumes.s_users_id) where file_location is null or file_location = '') ".
			"group by username order by $order limit $start_record, $view_per_page";
		}
	}
	else
	{			
		$sql = "select firstname,lastname,username, s_users.s_users_id, max(year) as year from s_users left join (s_users_degrees) ". 
				"on (s_users.s_users_id = s_users_degrees.s_users_id) where is_confirmed='1' ".
				"group by username order by $order limit $start_record, $view_per_page";
	}
	$res=db_query($sql);

	?>
	<form name='myForm' method='POST' action=''>
	<table id='hor-minimalist-b'>
		<thead><tr>
			<th width='120' align='left'><A HREF='<?php echo "$self?sort=name#title" ?>'> Last, First Name</th>
			<th width='75' align='left'><A HREF='<?php echo "$self?sort=username#title";?>'>Username</a></th>
			<th width='75' align='left'><A HREF='<?php echo "$self?sort=year#title"?>'>Graduating<a></th>
			<th width='100' align='left'>Resume Status</th>
			<th>Selection</th>
		</tr></thead>
		<tbody>
	<?
	if($end_record > $num_students)
		$end_record = $num_students;
	$start_record++;
	echo "Display records $start_record to $end_record ($num_students total)";
	$count=0;
	while($row=mysql_fetch_array($res))
	{
		echo "<tr><td><a href='$prepath/students/account.php?user=".$row['username']."'>{$row['lastname']}, {$row['firstname']}</a></td>";
		echo "<td>{$row['username']}</td>";
		echo "<td>{$row['year']}</td>";
		$student_id = $row['s_users_id'];
		if(resume_uploaded($row['username']))
			echo "<td>Uploaded</td>";
		else
			echo "<td>Missing </td>";
//		echo "<td><input type='checkbox' name='$student_id' id='$student_id' value='$student_id' onClick=\"".$ajax->bindInline("GetEmails",array("a"=>"$student_id"),"")."\"></td>";
		echo "<td><input type='checkbox' name='$student_id' id='$student_id' value='$student_id' onClick='getCheckBoxes()'></td>";		
		echo "</tr>";
		$count++;
	}
	echo "</tbody></table>";
	echo "<br><b>Actions for selected users:<br></b>";
	$message = "Are you sure you want to delete the selected users? This action cannot be undone";
	echo "<input type='submit' value='Delete users' name='delete' onclick=\"return confirm_entry('".$message."')\" >";
	$message = "Click OK will send an e-mail to the selected users to reset their password. Do you want to continue?";
	echo "<input type='button' value='Get E-mails' name='get_email' onClick=\"".$ajax->bindInline('GetEmails',array("a"=>'checked_values'),'checked_values')."\">";
	echo "<input type='hidden' name='checked_values' id='checked_values'>";
	echo "</form>";
	echo "<span class='message'>Click \"Get Emails\" to get selected students' e-mails. <br>They can then be copy/pasted into an e-mail client to send them a message:</span><br>";
	echo "<div class='admin_emails' id='emails' name='emails'>Selected users' e-mails</div>";
}
include ("$prepath/src/footer.php");	
?>	
