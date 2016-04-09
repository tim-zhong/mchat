var debug = 1;

function err(m){
	console.log('WebSockets Error: ' + m);
}

function msg(m){
	if(debug) console.log('Message: ' + m);
}


//socket is a defin
function connect(socket,url,username,roomname){
	socket = new WebSocket(url);
	if(socket == undefined){
		err('parameter socket is not defined');
		return false;
	}
	if(url == "" || url == undefined){
		err('parameter url is invalid');
		return false;
	}
	if(!socket || socket == undefined){
		err('failed to create socket');
		return false;
	}


	socket.onopen = function(){
		msg('Open successfully');
		register(socket,username,roomname);
	}
	socket.onerror = function(){
		msg('Error occurs');
	}
	socket.onclose = function(){
		msg('Seockt Closed');
	}
	socket.onmessage = function(e){
		msg(e.data);
		processasobj(e.data)
	}
	return socket;
}

function register(socket,username,roomname){
	if(!socket || socket == undefined){
		err('Fail to Register, No Available Socket');
		return false;
	}
	var obj = JSON.stringify({'type':"register",'username':username,'roomname':roomname.toLowerCase()});
	socket.send(obj);
}

function processasobj(s){
	var obj = JSON.parse(s);
	if(obj.cmd == 'addmarker'){
		console.log('userid: '+obj.userid);
		console.log('username: '+obj.username);
		console.log('lat: '+obj.lat);
		console.log('lng: '+obj.lng);
		var username = obj.username;
		var lat	= obj.lat;
		var lng = obj.lng;
		addmarker(username,lat,lng);
	}else if(obj.cmd == "removemarker"){
		var username = obj.username;
		//remove marker from view;
		markersarray[username].setMap(null);
		
		//remove marker from array
		delete markersarray[username];

		//Update user count
		document.getElementById('user-count').innerHTML = Object.size(markersarray);
	}else if(obj.cmd == "message"){
		console.log(document.getElementById('cw_history_'+obj.from));
		var w = document.getElementById('cw_history_'+obj.from);
		if(w){
			w.innerHTML = obj.message;
		} else{
			createinfowindow(obj.from, obj.message)
		}
		
		//Append message to history
		var history = document.getElementById('history_content');
		
		var message = document.createElement('div');
		message.className = "history_message";
		
		var messagename = document.createElement('span');
		messagename.className = "history_message_name";
		messagename.innerHTML = obj.from+": ";

		var messagebody = document.createElement('span');
		messagebody.className = "history_message_body";
		messagebody.innerHTML = obj.message;

		message.appendChild(messagename);
		message.appendChild(messagebody);
		history.appendChild(message);

	}
}

function createinfowindow(name,message){
	var marker = markersarray[name];
	var infow = new google.maps.InfoWindow({
		content: '<div class="chat_window" id="cw_'+name+'">' +
	        '<h3>'+name+'</h3>'+'<div class="cw_history" id="cw_history_'+name+'">'+message+'</div></div>'
	});
	infow.open(map,marker);
}

function sendmessage(){
	var message = document.getElementById('mmessage');
	if(message.value == "" ){
		message.style.border="1px solid #660000";
		return false;
	}else{
		if(!socket || socket == undefined){
			err('Fail to Register, No Available Socket');
			return false;
		}
		var obj = JSON.stringify({'type':"message",'username':username,'roomname':roomname,'message':message.value});
		socket.send(obj);
	}
	message.value="";//clear content
}
