<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
</head>

<body>
	<style>
	*{
		font-family: sans-serif;
	}
	span{
		border:1px solid #000000;
	}
	input{
		font-size:20px;
		padding:5px;
	}
	.clear{
		clear:both;
	}
	.left{
		float:left;
		width:50%;
	}
	.right{
		float:right;
		width: 50%;
	}
	.button{
		padding: 10px 20px;
	}

	@media screen and (max-width:614px){
		.right,.left{
			width:100%;
			height:auto;
			border:none;
		}
		.right{
			border-top:1px solid #bebebe;
			margin-top:100px;
		}
	}
	</style>

	<div class="left">
		
		<input id="name" placeholder="NAME"/>
		<input id="message"placehlder="MESSAGE" onkeyup="sendmessage('realtime');"/>

		<br/><br/>

		<span class="button" onclick="connect();" id="connect">Connect</span>
		<span class="button" onclick="sendmessage();" id="send">Send</span>
		<span class="button" onclick="closesoc();" id="close">Close</span>

		<div id="realtime">
		</div>

	</div>

	<div class="right">
		<div id="history">
		</div>
	</div>

	<div class="clear"></div>

	<script>


		var socket = null;
		var url = "ws://localhost:9898";
		var h = document.getElementById("history");
		var r = document.getElementById("realtime");

		function connect(){
			if(socket){
				append(h,'p','Already Connected')
				return false;
			}
			socket = new WebSocket(url);
			if(!socket || socket == undefined){
				alert("fail 1");
				return false;
			}
			socket.onopen = function(){
				append(h,'p','connected');
			}
			socket.onerror = function(){
				append(h,'p','Error');
			}
			socket.onclose = function(){
				append(h,'p','Close');
			}
			socket.onmessage = function(e){
				var obj = JSON.parse(e.data);
				var output = obj.name+": "+obj.message;
				var target = document.getElementById(obj.userid);
				var cmd = obj.cmd;

				if(cmd == 'realtime'){
					if(target==undefined){
						append(r,'p',output,obj.userid);
					} else {
						target.innerHTML=output;
					}
				}

				if(cmd == "normal") append(h,'p',obj.name+": "+obj.message);

				if(cmd == "remove"){
					r.removeChild(target);
				}
			}
		}
		function closesoc(){
			if(!socket || socket == undefined){
				append(h,'p','No Connection to Close');
				return false;
			}
			socket.close();
			r.innerHTML = "";
		}
		function sendmessage(cmd){
			if(cmd == undefined) cmd="normal";
			if(!socket || socket == undefined){
				append(h,'p','Please Connect First');
				return false;
			}
			var mess = document.getElementById('message').value;
			var name = document.getElementById('name').value;
			var pakage = JSON.stringify({'name':name, 'message':mess ,'cmd':cmd});
			socket.send(pakage);
		}


		function append(obj,type,str,id){
			var para = document.createElement(type);
			var node = document.createTextNode(str);
			para.appendChild(node);
			if(id!=undefined && id!="") para.id = id;
			obj.appendChild(para);
		}

	</script>
</body>
</html>