<?php
session_start(); 
$prepath="..";
include "$prepath/src/database.php";
include "$prepath/src/functions.php";
$self = $_SERVER['PHP_SELF'];

function random_year()
{
  $random= "";
  srand((double)microtime()*1000000);
  for($i=date("Y")-10;$i<date("Y")+10;$i++)
	$years[]=$i;
  
$char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $char_list .= "abcdefghijklmnopqrstuvwxyz";
  $char_list .= "1234567890";
  $strset=$char_list;
  // Add the special characters to $char_list if needed

  $random_year = $years[rand()%sizeof($years)];
  
  return $random_year;
} 


for($i=0;$i<500;$i++)
{
	$sql_arr=array();
	$sql_arr['firstname']=random_gen(10);
	$sql_arr['lastname']=random_gen(10);
	$sql_arr['username']=random_gen(10);
	$sql_arr['is_confirmed']=1;
	db_insert("s_users",$sql_arr);
	
	$id = mysql_insert_id();
	
	$sql_arr=array();
	$sql_arr['department'] = rand()%15;
	$sql_arr['s_users_id'] = $id;
	$sql_arr['degree']="phd";
	$sql_arr['year']=random_year();
	db_insert("s_users_degrees",$sql_arr);
}


?>	
