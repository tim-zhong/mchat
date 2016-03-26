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
		$this->send($this->users[$userid],$username.' is registering...');
		$this->users[$userid]->roomname = $roomname;

		//Query the coordinates of the user
		//$query = "select latitude,longitude from users where userid = $userid"
	}
}

$addr = 'ec2-52-37-132-185.us-west-2.compute.amazonaws.com';
$port = '9897';

$server = new Server($addr,$port);
$server->run();
