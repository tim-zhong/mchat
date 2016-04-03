<?php
include "connect.php";
require "Websockets/websockets.php";


global $db;
class Server extends WebSocketServer{

	private $_connecting = "connecting to server...";
	private $_welcome = 'Hello, welcome to echo server!!';
	protected $users = array();

	protected function connected($user){
		$this->users[$user->id]=$user;
		$this->send($user,$this->_welcome);
	}

	protected function process($user,$message){
		$obj = json_decode($message);
		$cmd = $obj->{'type'};

		switch($cmd){
			case 'register':
				$username = $obj->{'username'};
				$roomname = $obj->{'roomname'};
				$userid = $user->id;
				self::register($userid,$username,$roomname);
				break;
			default:
				$this->send($user, 'failed to register');
		}
	}

	protected function closed($user){
		global $db;
		unset($this->users[$user->id]);
		//Delete User From database;
		$query = "DELETE from users where username = '".$user->username."' and roomid = (SELECT roomid from rooms where roomname = '".$user->roomname."' limit 1) limit 1";
		sql_query($query, $db);

		//Decrement User count in that room;
		$query = "UPDATE rooms SET usercount = usercount - 1 WHERE roomname='".$user->roomname."'";
		sql_query($query, $db);		
	}

	public function __destruct(){
		echo "Server destroyed ".PHP_EOL;
	}




	//Helpers
	protected function register($userid,$username,$roomname){
		global $db;
		$this->send($this->users[$userid],$username.' is registering...');
		$this->users[$userid]->roomname = $roomname;
		$this->users[$userid]->username = $username;

		//Query the coordinates of the user
		$query = "select latitude,longitude from users where roomid = (select roomid from rooms where roomname = '".$roomname."') and username = '".$username."' limit 1";
		$this->send($this->users[$userid],$query);
		$rs = sql_query($query, $db);

		////Check for User existence
		if(sql_affected_rows($db)){
			$user = sql_fetch_array($rs);
			$lat = $user['latitude'];
			$lng = $user['longitude'];

			foreach($this->users as $u){
				//skip itself
				if($u->id == $userid || $u->roomname != $roomname) continue;
				$cmd = 'addmarker';
				$arr = array(
					"userid"=>$u->id,
					"username"=>$username,
					"lat"=>$lat,
					"lng"=>$lng,
					"cmd"=>$cmd
				);
				$result = self::createobjstr($arr);
				$this->send($u, $result);
			}
		}
	}
	protected function createobjstr($arr){
		$result = "{";
		foreach($arr as $key=>$value){
			$result.='"'.$key.'":"'.$value.'",';
		}
		$result=rtrim($result, ",");
		$result .= "}";
		return $result;
	}
}

$addr = 'ec2-52-37-132-185.us-west-2.compute.amazonaws.com';
$port = '9897';

$server = new Server($addr,$port);
$server->run();
