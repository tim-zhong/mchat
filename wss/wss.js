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
}

function register(socket,username,roomname){
	if(!socket || socket == undefined){
		err('Fail to Register, No Available Socket');
		return false;
	}
	var obj = JSON.stringify({'type':"register",'username':username,'roomname':roomname});
	socket.send(obj);
}

function processasobj(s){
	var obj = JSON.parse(JSON.stringify(s));
	console.log(obj.cmd);
	if(obj.cmd == 'addmarker'){
		console.log('userid: '+obj.userid);
		console.log('username: '+obj.name);
		console.log('lat: '+obj.lat);
		console.log('lng: '+obj.lng);
		var username = obj.username;
		var lat	= obj.lat;
		var lng = obj.lng;
		addmarker(username,lat,lng);
	}
}