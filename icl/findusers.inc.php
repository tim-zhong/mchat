<?
include "connect.php";


function findusers(){
	$roomname = $_GET['room'];
	
	global $db;

	$query = "select * from users where roomid = (select roomid from rooms where roomname = '".$roomname."' limit 1)";
	$rs = sql_query($query,$db);

	$jsonstr = "[";
	while($user = sql_fetch_array($rs)){
		

		$username = $user['username'];
		$lat = $user['latitude'];
		$lng = $user['longitude'];

		$jsonstr .= "[";
		$jsonstr .= "'".$username."',$lat,$lng";
		$jsonstr .= "],";
	}
	$jsonstr .= "]";
	return $jsonstr;

}