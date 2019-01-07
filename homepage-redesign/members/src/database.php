<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: database.php,v 1.6 2000/04/11 14:17:13 cvs Exp $
//
// /etc/local.inc includes the machine specific database connect info
/*
$sys_dbhost='sql.mit.edu';
$sys_dbuser='swe';
$sys_dbpasswd='zam52fin';
$sys_dbname='swe+resumedb';
*/


$dbh = mysql_connect('sql.mit.edu', 'swe', 'zam52fin')
		or die('Could not connect: ' . mysql_error() . '<br />');

mysql_select_db("swe+board") or die("No database selected.");

function db_query($qstring,$print=0) {
	global $sys_dbname;
	return @mysql($sys_dbname,$qstring);
}

function db_numrows($qhandle) {
	// return only if qhandle exists, otherwise 0
	if ($qhandle) {
		return @mysql_numrows($qhandle);
	} else {
		return 0;
	}
}

function db_result($qhandle,$row,$field) {
	return @mysql_result($qhandle,$row,$field);
}

function db_getRow($sql)
{
	$res = db_query($sql);
	return @mysql_fetch_array($res);	
}
function db_getFirstResult($sql)
{
	$res = db_query($sql);
	$row = mysql_fetch_array($res);	
	return $row[0];
}

function db_getArray($sql)
{
	$res = db_query($sql);
	$arr = array();
	while($row = mysql_fetch_array($res))
	{
		$arr[] = $row[0];
	}
	return $arr;
}

function db_numfields($lhandle) {
	return @mysql_numfields($lhandle);
}

function db_fieldname($lhandle,$fnumber) {
           return @mysql_fieldname($lhandle,$fnumber);
}

function db_affected_rows($qhandle) {
	return @mysql_affected_rows();
}
	
function db_fetch_array($qhandle) {
	return @mysql_fetch_array($qhandle);
}
function db_fetch_row($qhandle) {
	return @mysql_fetch_row($qhandle);
}
function db_fetch_assoc($qhandle) {
	return @mysql_fetch_assoc($qhandle);
}
	
function db_insert_id() {
	return @mysql_insert_id();
}

function db_insert($table, $fields)
{
	
		$sql = "insert into $table (";
		foreach($fields as $field=>$value)
		{
			if($value != "")
				$sql .= " $field,";
		}
		$sql = rtrim($sql,",");		// delete last comma
		$sql .= ") Values (";
		foreach($fields as $field=>$value)
		{
			if($value != "")
				$sql .= "'".addslashes($value)."',";
		}
		$sql = rtrim($sql,",").")";	
		
		db_query($sql);
//		echo $sql."<br>";
}

function db_update($table, $fields, $where)
{
		
	$sql = "update $table set ";
	foreach($fields as $field=>$value)
	{
		if($value != "")
			$sql .= " $field = '".mysql_real_escape_string($value)."',";
	}
	$sql = rtrim($sql,",");		// delete last comma
	$sql .= " where ".$where;
	if($where!="")
		db_query($sql);
//	echo $sql;
}

function db_delete_insert($table, $fields, $where)
{
	if($where != "")
	{
		$sql = "delete from $table where $where";
		db_query($sql);
	}
	db_insert($table,$fields);
}

function db_error() {
	return "\n\n<P><B>".@mysql_error()."</B><P>\n\n";
}

//connect to the db
//I usually call from pre.php
db_connect();

?>