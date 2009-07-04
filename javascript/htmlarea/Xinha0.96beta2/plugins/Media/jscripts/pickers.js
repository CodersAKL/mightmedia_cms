	var bgCol_pick = document.getElementById('bgCol_pick');
	var bgcolor = document.getElementById('bgcolor');
	var bgColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			bgcolor.value=color;
		}
	});
	bgCol_pick.onclick = function() { 
		bgColPicker.open('bottom', bgcolor ); 
	}
	var screencolor_pick = document.getElementById('screencolor_pick');
	var screencolor = document.getElementById('flv|mp3_screencolor');
	var screenColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			screencolor.value=color;
		}
	});
	screencolor_pick.onclick = function() { 
		screenColPicker.open('bottom', screencolor ); 
	}
	var lightcolor_pick = document.getElementById('lightcolor_pick');
	var lightcolor = document.getElementById('flv|mp3_lightcolor');
	var lightColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			lightcolor.value=color;
		}
	});
	lightcolor_pick.onclick = function() { 
		lightColPicker.open('bottom', lightcolor ); 
	}
	var frontcolor_pick = document.getElementById('frontcolor_pick');
	var frontcolor = document.getElementById('flv|mp3_frontcolor');
	var frontColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			frontcolor.value=color;
		}
	});
	frontcolor_pick.onclick = function() { 
		frontColPicker.open('bottom', frontcolor ); 
	}
	var backcolor_pick = document.getElementById('backcolor_pick');
	var backcolor = document.getElementById('flv|mp3_backcolor');
	var backColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			backcolor.value=color;
		}
	});
	backcolor_pick.onclick = function() { 
		backColPicker.open('bottom', backcolor ); 
	}