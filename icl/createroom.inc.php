<?php

function createroom($name,$latitude,$longitude){
	global $db;

	$rand=randstr(5);
	while(db_row_exists($db,'rooms','roomname',$rand)) $rand=randstr(5);


	$query = "INSERT INTO rooms (roomname,usercount) VALUES ('".$rand."',1)";
	if(sql_query($query,$db)){

		$roomid=sql_insert_id($db);
		$ip = get_client_ip();
		$name=filter_var($name, FILTER_SANITIZE_STRING);
		$time=time();

		$query = "INSERT INTO users (username,userip,roomid,longitude,latitude,lastupdate) VALUES ('".$name."','".$ip."',$roomid,$longitude,$latitude,'".$time."')";

	if(sql_query($query,$db)){
		setCookie('u',$userid);
		?>
		<a href="room.php?room=<?php echo $rand;?>"><div class="button button-green">Room Created</div></a>
		<?php
	}


	} else {

		echo "Failed to create a room.";

	}
}