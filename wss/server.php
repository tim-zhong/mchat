<?php
include "connect.php";
require "Websockets/websockets.php";


global $db;
class Server extends WebSocketServer{

	private $_connecting = "connecting to server...";
	private $_welcome = 'Hello, welcome to echo server!';
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
		unset($this->users[$user->id]);
	}

	public function __destruct(){
		echo "Server destroyed ".PHP_EOL;
	}




	//Helpers
	protected function register($userid,$username,$roomname){
		global $db;
		$this->send($this->users[$userid],$username.' is registering...');
		$this->users[$userid]->roomname = $roomname;

		$this->send($this->users[$userid],"aaa");
		//Query the coordinates of the user
		$query = "select latitude,longitude from users where roomid = (select roomid from rooms where roomname = '".$roomname."' limit 1) limit 1";
		$rs = sql_query($query, $db);
		$user = sql_fetch_array($rs);
		$lat = $user['latitude'];
		$lng = $user['longitude'];

		foreach($this->users as $u){
			//skip itself
			//if($u->id == $userid) continue;
			$cmd = 'addmarker';
			$result = '{"userid":"'.$user->id.'","name":"'.$username.'","lat":"'.$lat.'","lng":"'.$lng.'","cmd":"'.$cmd.'"}';
			$arr = array(
				"userid"=>$user->id,
				"name"=>$username,
				"lat"=>$lat,
				"lng"=>$lng,
				"cmd"=>$cmd
			);
			$result1 = self::createobjstr($arr);
			$this->send($u, $result);
			$this->send($u, $result1);
		}

	}
	protected function createobjstr($keys,$values){
		$result = "{";
		foreach($arr as $key=>$value){
			$result.='"'.$key.'":"'.$value.'",';
		}
		$result .= "}";
		return $result;
	}
}

$addr = 'ec2-52-37-132-185.us-west-2.compute.amazonaws.com';
$port = '9797';

$server = new Server($addr,$port);
$server->run();
