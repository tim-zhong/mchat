function togglehistory(){
	var h = document.getElementById('history');
	var s = document.getElementById('history_switch_icon');
	if(h.className == 'history_opened'){
		h.className = 'history_closed';
		s.className = 'i_arrow_right sprite';
	}
	else{
		h.className = 'history_opened';
		s.className = 'i_arrow_left sprite';
	}
}