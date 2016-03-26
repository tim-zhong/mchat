<?php
include_once "../connect.php";
require "Websockets/websockets.php";



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
		$type = $obj{'type'};

		switch($type){
			case 'register':
				$username = $obj{'username'};
				$roomname = $obj{'roomname'};
				$userid = $user->id;
				register($userid,$username,$roomname);
				break;
			default:
				$this->send($user, 'failed to register');
		}
	}

	protected function closed($user){
		unset($this->users[$user->id]);

		$cmd = 'remove';
		$result = '{"userid":"'.$user->id.'","name":"","message":"","cmd":"'.$cmd.'"}';
		foreach($this->users as $u){
			$this->send($u, $result);
		}
		echo "User $user->id closed connection ".PHP_EOL;
	}

	public function __destruct(){
		echo "Server destroyed ".PHP_EOL;
	}




	//Helpers
	protected function register($userid,$username,$roomname){
		$this->send($users[$userid],$username.'is registering...');
	}
}

$addr = 'ec2-52-37-132-185.us-west-2.compute.amazonaws.com';
$port = '9897';

$server = new Server($addr,$port);
$server->run();
