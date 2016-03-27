<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>distance +</title>
	<meta name="description" content="The HTML5">
</head>

<body>
	<style>
	html{
		background:url(images/ping.png) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
	body{
		background:url(images/ping.png) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
		margin: 0;
		overflow: hidden;
	}
	*{
		font-family: sans-serif;
	}
	a{
		text-decoration: none;
	}
	.left{
		float:left;
		width:60%;
	}
	#main{
		width:100%;
		height:100%;
		position:absolute;
	}
	#form-container-join,#form-container-create a{
		text-decoration: none;
		color:#ffffff;
	}
	#form-container-join,#form-container-create{
		width:70%;
		margin:0 auto;
		top:70%;
		position: relative;
		-webkit-transform:translateY(-70%);
		-moz-transform:translateY(-70%);
		-o-transform:translateY(-70%);
		transform:translateY(-70%);
		text-align: center;
		-webkit-transition:top 250ms;
		-moz-transition:top 250ms;
		-o-transition:top 250ms;
		transition:top 250ms;
	}
	#form-container-create{
		top:80%;
	}
	#form-container-join input,#form-container-create input{
		padding:18px 20px;
		width:80%;
		font-size:24px;
		color:#ffffff;
		background:none;
		border:3px solid #ffffff;
		-webkit-border-radius:30px;
		-ms-border-radius:30px;
		-o-border-radius:30px;
		border-radius:30px;
		font-family: 100;
		text-align: center;
		outline: 0;
		-webkit-transition:background 250ms;
		-moz-transition:background 250ms;
		-o-transition:background 250ms;
		transition:background 250ms;

	}
	#form-container-join input:focus,#form-container-create input:focus{
		background: rgba(0,0,0,0.4);
	}
	.button{
		cursor: pointer;
		padding:20px 0px;
		color:#ffffff;
		text-transform: uppercase;
		letter-spacing: 1px;
		margin:10px auto;
		width:250px;
		font-size:20px;
		-webkit-border-radius:30px;
		-ms-border-radius:30px;
		-o-border-radius:30px;
		position: relative;
		-webkit-transition:all 250ms;
		-moz-transition:all 250ms;
		-o-transition:all 250ms;
		transition:all 250ms;
		left:0;
		top:0;
		max-width: 100%;
	}
	.button-red{
		background: #b56969;
		-webkit-box-shadow: 3px 3px 0px #823636;
		-moz-box-shadow: 3px 3px 0px #823636;
		box-shadow: 3px 3px 0px #823636;
	}
	.button-green{
		background: #64BC4B;
		-webkit-box-shadow: 3px 3px 0px #319a19;
		-moz-box-shadow: 3px 3px 0px #319a19;
		box-shadow: 3px 3px 0px #319a19;
	}
	.button:hover{
		top:3px;
		left: 3px;
	}
	.button-green:hover{
		background: #64BC4B;
		-webkit-box-shadow: 1px 1px 0px #319a19;
		-moz-box-shadow: 1px 1px 0px #319a19;
		box-shadow: 1px 1px 0px #319a19;
	}
	.button-red:hover{
		-webkit-box-shadow: 1px 1px 0px #823636;
		-moz-box-shadow: 1px 1px 0px #823636;
		box-shadow: 1px 1px 0px #823636;
	}
	#input-group-create{
		top:200px;
	}
	#input-group-join{
		position: relative;
		-webkit-transition:all 250ms;
		-moz-transition:all 250ms;
		-o-transition:all 250ms;
		transition:all 250ms;
	}
	#switch-to-create,#switch-to-join{
		-webkit-transition:bottom 250ms;
		-moz-transition:bottom 250ms;
		-o-transition:bottom 250ms;
		transition:bottom 250ms;
		color:#ffffff;
		cursor:pointer;
		position: absolute;
		right: 0;
		bottom: -30px;
		z-index:2;
		right:10%;
	}
	#switch-to-join{
		bottom: 120px;
		cursor:pointer;
	}
	#form-container-join #switch-to-join{
		bottom: 30px;
		opacity: 0;
		-webkit-transition:all 250ms;
		-moz-transition:all 250ms;
		-o-transition:all 250ms;
		transition:all 250ms;
	}
	#form-container-join #input-group-create .button{
		left:-300px;
		opacity: 0;
	}
	#form-container-join #input-group-join{
		top:-70px;
	}
	#form-container-create #input-group-join{
		top: 0px;opacity: 0;
	}
	#success,#error{
		border:1px solid red;
		background: #e6cf8b;
		padding: 20px 40px;
		position:absolute;
		top:20px;
		width:100%;
	}
	#notice{
		text-transform: uppercase;
		font-size: 30px;
		text-align: center;
		position: absolute;
		width:100%;
		bottom:10px;
		color:#ffffff;
	}
	#title{
		width:90%;
		margin: 0 5%;
		position: absolute;
		top:20%;
	}
	</style>
	<div id="main">
		<div class="left">
		</div>
		<div class="clear"></div>
		<div id="form-container-join">
			<div id="input-group-create">
				<input id="name" name="name" placeholder="NAME"/>
				<div class="button button-red" onclick="getLocation('create');">build a Room</div>
				<a id="switch-to-join">Already had Have a Room?</a>
			</div>
			
			<div id="input-group-join" class="opened">
				<input id="roomname" placeholder="ROOM CODE"/><br/>
				<div class="button button-red" onclick="getLocation('join');">Join a Room</div>
				<a id="switch-to-create">Don't Have a Room?</a>
			</div>
		</div>
		<div id="notice"></div>
	</div>
</body>

<script src="nano.js"></script>
<script>
	document.getElementById('switch-to-create').onclick=function(){
		document.getElementById('form-container-join').id="form-container-create";
	}
	document.getElementById('switch-to-join').onclick=function(){
		document.getElementById('form-container-create').id="form-container-join";
	}


	function createroom(position){
		var name = gid('name');
		var latitude = position.coords.latitude;
	    var longitude = position.coords.longitude;
		ajxpgn('notice','services.php?latitude='+latitude+'&longitude='+longitude+'&cmd=createroom&name='+name.value);
	}
	function joinroom(position){
		var name = gid('name');
		var roomname = gid('roomname');
		var latitude = position.coords.latitude;
	    var longitude = position.coords.longitude;
		ajxpgn('notice','services.php?latitude='+latitude+'&longitude='+longitude+'&cmd=joinroom&roomname='+roomname.value+'&name='+name.value);
	}
	function getLocation(cmd) {
    if (navigator.geolocation) {
    	var options = {
		  enableHighAccuracy: true,
		  timeout: 5000,
		  maximumAge: 0
		};
		if(cmd=='create') navigator.geolocation.getCurrentPosition(createroom,fail,options);
		else if(cmd=="join") navigator.geolocation.getCurrentPosition(joinroom,fail,options);
		else{console.log('Invalid Command.');}
    } else { 
        gid('notice').innerHTML = "Geolocation is not supported by this browser.";
    }
    
    function fail(){
		gid('notice').innerHTML="Failed";
	}
}
</script>
</html>