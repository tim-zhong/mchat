<?php
include "connect.php";
include "lib/lib.php";

$cmd=$_GET['cmd'];

switch($cmd){
	case 'createroom': 
		$name = $_GET['name'];
		$latitude = $_GET['latitude'];
		$longitude = $_GET['longitude'];
		include 'icl/createroom.inc.php'; createroom($name,$latitude,$longitude);
		break;
	case 'joinroom': 
		$name = $_GET['name'];
		$roomname = $_GET['roomname'];
		$latitude = $_GET['latitude'];
		$longitude = $_GET['longitude'];
		include 'icl/joinroom.inc.php'; joinroom($name,$roomname,$latitude,$longitude);
		break;
	default: echo "Invalid command: ".$cmd;
}