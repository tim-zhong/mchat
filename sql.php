<?php
function sql_connect($host,$database,$user,$pass){
	$db=mysqli_connect($host,$user,$pass,$database);
	return $db;
}
function sql_query($query,$db){
	$rs=mysqli_query($db,$query);
	if (!$rs) echo "sql_error: ".$query;
	return $rs;
}
function sql_fetch_array($rs){
	return mysqli_fetch_array($rs);
}
function sql_insert_id($db){
	return mysqli_insert_id($db);
}

function sql_affected_rows($db){
	return mysqli_affected_rows($db);
}
