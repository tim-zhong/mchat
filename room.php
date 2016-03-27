<?php 
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

?>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>MapChat Room</title>
	<meta name="description" content="The HTML5">
</head>

<body>
	<style>
		body{
			margin:0;
		}		
		#map_wrapper {
			position: absolute;
		    height: 100%;
		    width:100%;
		}

		#map_canvas {
		    width: 100%;
		    height: 100%;
		}
		#room-info{
			position: absolute;
			top:0;
			right: 0;
			background: rgba(0,0,0,0.5);
			padding: 10px 20px;
			font-size: 22px;
			font-family: sans-serif;
			color:#ffffff;
			letter-spacing: 1px;
		}
	</style>

	<div id="map_wrapper">
    	<div id="map_canvas" class="mapping"></div>
	</div>
	<table id="room-info">
		<tr><td>Room Code</td><td>: </td><td><?php echo $_GET['room']; ?></td></tr>
		<tr><td>People</td><td>: </td><td id="people-number">: </td></tr>
	</table>

	<script src="jquery.js"></script>
	<script src="http://maps.googleapis.com/maps/api/js"></script>
	<script src="wss/wss.js"></script>
	<script>
	var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    

	function addmarker(name, lat, lng){

	    	var position = new google.maps.LatLng(lat, lng);

	    	bounds.extend(position);

	    	marker = new google.maps.Marker({
	            position: position,
	            map: map,
	            title: name,
	            animation: google.maps.Animation.DROP
	        });

	        map.fitBounds(bounds);

	        var label = '<div class="info_content">' +
	        '<h3>'+name+'</h3>'+'</div>'
	        google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
	                infoWindow.setContent(label);
	                infoWindow.open(map, marker);
	            }
	        })(marker, i));
	}

// ======================= websockets ================= //

	var socket = null;
	var url = "ws://ec2-52-37-132-185.us-west-2.compute.amazonaws.com:9797";
	var username = getCookie('user');
	var roomname = '<?php echo $_GET['room']; ?>';
	connect(socket,url,username,roomname);
	

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
	    }
	    return "";
	}
// ==================================================== //

	var markers = <?php echo findusers();?>;
	document.getElementById('people-number').innerHTML = markers.length;
	for( i = 0; i < markers.length; i++ ) {
		addmarker(markers[i][0],markers[i][1],markers[i][2]);
	}
	//addmarker('Palace of Westminster, London', 51.499633,-0.124755);
	</script>
</body>
</html>
