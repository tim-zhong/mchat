<?php
function joinroom($name,$roomname,$latitude,$longitude){
		global $db;
		$multiroom = 1;

		$roomnotexist = db_row_exists($db,'rooms','roomname',$roomname);


		if($roomnotexist){

			$query = "SELECT * FROM rooms where roomname='".$roomname."' LIMIT 1";
			$rs = sql_query($query,$db);
			$room = sql_fetch_array($rs);
			$room = $room[0];

			$roomid = $room['roomid'];
			$name = filter_var($name, FILTER_SANITIZE_STRING);
			$time = time();
			$ip = get_client_ip();

			$userexist = db_row_exists($db,'users','userip',$ip);
			if($multiroom || !$userexist){
				$nametaken = 0;
				$query = "select * from users where roomid = '".$roomid."'";
				$rs = sql_query($query,$db);
				while($user = sql_fetch_array($rs)){
					if($user['username']==$name){
						$nametaken = 1;
						break;
					}
				}

				if(!$nametaken){

					$query = "INSERT INTO users (username,userip,roomid,longitude,latitude,lastupdate) VALUES ('".$name."','".$ip."',$roomid,$longitude,$latitude,'".$time."')";
					$userid=sql_insert_id();

					if(sql_query($query,$db)){

						$query = "UPDATE rooms SET usercount = usercount + 1 WHERE roomid=$roomid";

						if(sql_query($query,$db)){

							setcookie('user',$userid);
							?>
							<a href="<?php echo 'room.php?room='.$roomname;?>"><div class="button button-green">Room Found</div></a>
							<?php

						}

					}
				} else {
					echo "Sorry, the name you entered has been taken"
				}
			} else {

				echo "Sorry you already have a room";

			}

		} else{
			echo "Room not found.";
		}
}
