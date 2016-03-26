<?php

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
		$pakage = json_decode($message);
		$name = $pakage->{'name'};
		$message = $pakage->{'message'};
		$cmd = $pakage->{'cmd'};
		$result = '{"userid":"'.$user->id.'","name":"'.$name.'","message":"'.$message.'","cmd":"'.$cmd.'"}';
		foreach($this->users as $u){
			$this->send($u, $result);
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
}

$addr = 'ec2-52-37-132-185.us-west-2.compute.amazonaws.com/mapchat/wss';
$port = '9897';

$server = new Server($addr,$port);
$server->run();
