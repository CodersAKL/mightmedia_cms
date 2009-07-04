function Init() {
	__dlg_translate("InsertMedia");
	__dlg_init();
	
	var pl = "", f, at;
	var type = "flash";
	f = document.forms[0];
	var param = window.dialogArguments;
	if(param['filemanager'] == 'set'){
		document.getElementById('srcbrowsercontainer').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('src');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
		document.getElementById('logobrowsercontainer').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('flv|mp3_logo');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
		document.getElementById('imagebrowsercontainer').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('flv|mp3_image');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
	}
	
	if(typeof(param['playlist']) != "undefined" && param['playlist'] != 'null'){
		var elSel = document.getElementById('media_type');
		var elOptNew = document.createElement('option');
		elOptNew.text = "Playlist";
		elOptNew.value = "playlist";
		try {
			elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
		}
		catch(ex) {
			elSel.add(elOptNew); // IE only
		}
		changedPlaylistView(document.getElementById('playlist_options_view').value);
		
		document.getElementById("playlist_xml").src = "../playlist.php?"+param['playlist'];	
		
		document.getElementById('playlistfilebrowser').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('xml_media:content');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
		document.getElementById('playlistthumbbrowser').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('xml_media:thumbnail');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
		document.getElementById('playimagebrowsercontainer').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('playlist_image');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
		document.getElementById('playlogobrowsercontainer').innerHTML = "<a href=\"javascript:;\" onclick=\"selectFile('playlist_logo');\" title=\"Select File\" class=\"folder\"><img style=\"border:none;\" src=\"../img/folder_small.gif\" alt=\"Select File\" width=\"16\" height=\"13\"></a>";
	
	var playlist_screencolor_pick = document.getElementById('playlist_screencolor_pick');
	var playlist_screencolor = document.getElementById('playlist_screencolor');
	var playlist_screenColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			playlist_screencolor.value=color;
		}
	});
	playlist_screencolor_pick.onclick = function() { 
		playlist_screenColPicker.open('bottom', playlist_screencolor ); 
	}
	var playlist_lightcolor_pick = document.getElementById('playlist_lightcolor_pick');
	var playlist_lightcolor = document.getElementById('playlist_lightcolor');
	var playlist_lightColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			playlist_lightcolor.value=color;
		}
	});
	playlist_lightcolor_pick.onclick = function() { 
		playlist_lightColPicker.open('bottom', playlist_lightcolor ); 
	}
	var playlist_frontcolor_pick = document.getElementById('playlist_frontcolor_pick');
	var playlist_frontcolor = document.getElementById('playlist_frontcolor');
	var playlist_frontColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			playlist_frontcolor.value=color;
		}
	});
	playlist_frontcolor_pick.onclick = function() { 
		playlist_frontColPicker.open('bottom', playlist_frontcolor ); 
	}
	var playlist_backcolor_pick = document.getElementById('playlist_backcolor_pick');
	var playlist_backcolor = document.getElementById('playlist_backcolor');
	var playlist_backColPicker = new Xinha.colorPicker({
		cellsize:'5px',callback:function(color){
			playlist_backcolor.value=color;
		}
	});
	playlist_backcolor_pick.onclick = function() { 
		playlist_backColPicker.open('bottom', playlist_backcolor ); 
	}
	
	if (param['variables']) {
		at = param['variables'];
		at = at.replace(/&(#39|apos);/g, "'");
		at = at.replace(/&#quot;/g, '"');
		at = at.replace(/`/g, "'");

		var strlen = at.length - 1;
			var at_test = at.substr(strlen,1);
			if(at_test == ','){
				at = at.substr(0,strlen);
		}
		try {
			pl = eval('x={' + at + '};');
		} catch (ex) {
			pl = {};
		}
		switch (param['type']) {
			case 'mediaItemFlash':
				type = 'flash';
				break;
			case 'mediaItemShockWave':
				type = 'shockwave';
				break;
			case 'mediaItemWindowsMedia':
				type = 'wmp';
				break;
			case 'mediaItemQuickTime':
				type = 'qt';
				break;
			case 'mediaItemRealMedia':
				type = 'rmp';
				break;
			case 'mediaItemFlv':
				type = 'flv|mp3';
				break;
			case 'mediaItemPlaylist':
				type = 'playlist';
				break;
		}
		switch (type) {
			case "flash":
				setBool(pl, 'flash', 'play');
				setBool(pl, 'flash', 'loop');
				setBool(pl, 'flash', 'menu');
				setBool(pl, 'flash', 'swliveconnect');
				setStr(pl, 'flash', 'quality');
				setStr(pl, 'flash', 'scale');
				setStr(pl, 'flash', 'salign');
				setStr(pl, 'flash', 'wmode');
				setStr(pl, 'flash', 'base');
				setStr(pl, 'flash', 'flashvars');
			break;
			case "qt":
				setBool(pl, 'qt', 'loop');
				setBool(pl, 'qt', 'autoplay');
				setBool(pl, 'qt', 'cache');
				setBool(pl, 'qt', 'controller');
				setBool(pl, 'qt', 'correction');
				setBool(pl, 'qt', 'enablejavascript');
				setBool(pl, 'qt', 'kioskmode');
				setBool(pl, 'qt', 'autohref');
				setBool(pl, 'qt', 'playeveryframe');
				setBool(pl, 'qt', 'tarsetcache');
				setStr(pl, 'qt', 'scale');
				setStr(pl, 'qt', 'starttime');
				setStr(pl, 'qt', 'endtime');
				setStr(pl, 'qt', 'tarset');
				setStr(pl, 'qt', 'qtsrcchokespeed');
				setStr(pl, 'qt', 'volume');
				setStr(pl, 'qt', 'qtsrc');
			break;
			case "shockwave":
				setBool(pl, 'shockwave', 'sound');
				setBool(pl, 'shockwave', 'progress');
				setBool(pl, 'shockwave', 'autostart');
				setBool(pl, 'shockwave', 'swliveconnect');
				setStr(pl, 'shockwave', 'swvolume');
				setStr(pl, 'shockwave', 'swstretchstyle');
				setStr(pl, 'shockwave', 'swstretchhalign');
				setStr(pl, 'shockwave', 'swstretchvalign');
			break;
			case "wmp":
				setBool(pl, 'wmp', 'autostart');
				setBool(pl, 'wmp', 'enabled');
				setBool(pl, 'wmp', 'enablecontextmenu');
				setBool(pl, 'wmp', 'fullscreen');
				setBool(pl, 'wmp', 'invokeurls');
				setBool(pl, 'wmp', 'mute');
				setBool(pl, 'wmp', 'stretchtofit');
				setBool(pl, 'wmp', 'windowlessvideo');
				setStr(pl, 'wmp', 'balance');
				setStr(pl, 'wmp', 'baseurl');
				setStr(pl, 'wmp', 'captioningid');
				setStr(pl, 'wmp', 'currentmarker');
				setStr(pl, 'wmp', 'currentposition');
				setStr(pl, 'wmp', 'defaultframe');
				setStr(pl, 'wmp', 'playcount');
				setStr(pl, 'wmp', 'rate');
				setStr(pl, 'wmp', 'uimode');
				setStr(pl, 'wmp', 'volume');
			break;
			case "rmp":
				setBool(pl, 'rmp', 'autostart');
				setBool(pl, 'rmp', 'loop');
				setBool(pl, 'rmp', 'autogotourl');
				setBool(pl, 'rmp', 'center');
				setBool(pl, 'rmp', 'imagestatus');
				setBool(pl, 'rmp', 'maintainaspect');
				setBool(pl, 'rmp', 'nojava');
				setBool(pl, 'rmp', 'prefetch');
				setBool(pl, 'rmp', 'shuffle');
				setStr(pl, 'rmp', 'console');
				setStr(pl, 'rmp', 'controls');
				setStr(pl, 'rmp', 'numloop');
				setStr(pl, 'rmp', 'scriptcallbacks');
			break;
			case "flv|mp3":
				setStrFlashV(pl, 'flv|mp3', 'screencolor');
				setStrFlashV(pl, 'flv|mp3', 'lightcolor');
				setStrFlashV(pl, 'flv|mp3', 'frontcolor');
				setStrFlashV(pl, 'flv|mp3', 'backcolor');
				setStrFlashV(pl, 'flv|mp3', 'autostart');
				setStrFlashV(pl, 'flv|mp3', 'repeat');
				setStrFlashV(pl, 'flv|mp3', 'bufferlength');
				setStrFlashV(pl, 'flv|mp3', 'shuffle');
				setStrFlashV(pl, 'flv|mp3', 'showicons');
				setStrFlashV(pl, 'flv|mp3', 'shownavigation');
				setStrFlashV(pl, 'flv|mp3', 'showstop');
				setStrFlashV(pl, 'flv|mp3', 'showdigits');
				setStrFlashV(pl, 'flv|mp3', 'usefullscreen');
				setStrFlashV(pl, 'flv|mp3', 'showdownload');
				setStrFlashV(pl, 'flv|mp3', 'image');
				setStrFlashV(pl, 'flv|mp3', 'logo');
			break;
			case "playlist":
				setStrFlashV(pl, 'playlist', 'screencolor');
				setStrFlashV(pl, 'playlist', 'lightcolor');
				setStrFlashV(pl, 'playlist', 'frontcolor');
				setStrFlashV(pl, 'playlist', 'backcolor');
				setStrFlashV(pl, 'playlist', 'autostart');
				setStrFlashV(pl, 'playlist', 'repeat');
				setStrFlashV(pl, 'playlist', 'bufferlength');
				setStrFlashV(pl, 'playlist', 'shuffle');
				setStrFlashV(pl, 'playlist', 'showicons');
				setStrFlashV(pl, 'playlist', 'shownavigation');
				setStrFlashV(pl, 'playlist', 'showstop');
				setStrFlashV(pl, 'playlist', 'showdigits');
				setStrFlashV(pl, 'playlist', 'usefullscreen');
				setStrFlashV(pl, 'playlist', 'showdownload');
				setStrFlashV(pl, 'playlist', 'image');
				setStrFlashV(pl, 'playlist', 'logo');
				setStrFlashV(pl, 'playlist', 'autoscroll');
				setStrFlashV(pl, 'playlist', 'thumbsinplaylist');
				if(pl['flashvars'].match('displaywidth')){
					document.getElementById('playlist_size').value = Number(pl['width']) - Number(setStrFlashV(pl, 'playlist', 'displaywidth'));
					document.getElementById('playlist_position').value = 'right';
				}else{
					document.getElementById('playlist_size').value = Number(pl['height']) - Number(setStrFlashV(pl, 'playlist', 'displayheight'));
					document.getElementById('playlist_position').value = 'bottom';
				}
				var playlistsize = document.getElementById('playlist_size').value;
				var playlistpos = document.getElementById('playlist_position').value;
			break;
		}
		if(type == 'flv|mp3' || type == 'playlist'){
			setStrFlashV(pl,null, 'file');
			document.getElementById('src').value = document.getElementById('file').value;
		}else{
			setStr(pl, null, 'src');
		}
		setStr(pl, null, 'id');
		setStr(pl, null, 'name');
		setStr(pl, null, 'vspace');
		setStr(pl, null, 'hspace');
		setStr(pl, null, 'bgcolor');
		setStr(pl, null, 'align');
		setStr(pl, null, 'width');
		setStr(pl, null, 'height');
		
		if ((val = param['width']) != "")
			pl.width = f.width.value = val;
		if ((val = param['height']) != "")
			pl.height = f.height.value = val;
		
		document.getElementById('media_type').value = type;
		changedType(type);
		generatePreview();
		}else{
			changedType(document.getElementById('media_type').value);
			generatePreview();
		}
	}
}

function changedPlaylistView(t) {
	var d = document;
	d.getElementById('Player').style.display = 'none';
	d.getElementById('Playlist').style.display = 'none';
	d.getElementById(t).style.display = 'block';
}

function split_url(){
	var url, a, b, x, pos = '';
	url = window.dialogArguments['playlist'];
	a = url.split('&');
	for(x =0;x<(a.length);x++){
		b = a[x].split('=');
		if(b[0] == 'newdir'){
			if(b[1] == '1'){
				pos+='/media_playlists/'; 
			}
			else{
				pos+='/'; 	
			}
		}else if(b[0] == 'url'){
			pos+=b[1]; 
		}
	}
	return pos;
}

String.prototype.strip = function( exp ){ return this.replace(exp?exp:/\s/g,""); };

function uploadXml(add){
	d = document;
	var name =  d.getElementById("playlist_name").value;
	var content = d.getElementById("xml_media:content").value;
	var thumb = d.getElementById("xml_media:thumbnail").value;
	var Link = d.getElementById("xml_link").value;
	var title = d.getElementById("xml_media:title").value;
	var url = "../playlist.php?"+window.dialogArguments['playlist'];
	name = name.strip();
	if(add == 'add'){	
		content = content.strip();
		title = title.strip();
		if(name == ''){
			alert("Please fill in a playlist name");
			return ;
		}else if(content == ''){
			alert("Please fill in a file url");
			return ;
		}else if(title == ''){
			alert("Please fill in a item title");
			return ;
		}
		d.getElementById("playlist_xml").src = url+'&content='+content+'&name='+name+'&thumb='+thumb+'&link='+Link+'&title='+title+'&rand='+randomNumber();
		setTimeout("loadXml();",1000);
	}else if(add == 'view'){
		if(name.length > 1){
			d.getElementById("playlist_xml").src = url+'&name='+name;
		}
	}else if(add == 'edit'){
		if(name.length > 1){
			if(d.getElementById("playlist_xml").src.match('edit')){
				d.getElementById("playlist_xml").src = url+'&name='+name+'&rand='+randomNumber();
			}else{
				var base_url = window.opener._editor_url;
				var skin = window.opener._editor_skin;
				d.getElementById("playlist_xml").src = url+'&name='+name+'&edit=edit&skin='+skin+'&base='+base_url+'&rand='+randomNumber();
			}
		}
	}
}

function loadXml(){
	var name =  d.getElementById("playlist_name").value;
	name = name.strip();
	var file = split_url() + name + '.xml';
	document.getElementById('src').value = file;
	switchType(file);
	generatePreview();	
}

function selectFile(id) {
	var url = "../../ExtendedFileManager/manager.php?mode=link&media=true";
	document.getElementById("playlist_xml").src = '';
	var param={
		url:window.opener._editor_url,
		skin:window.opener._editor_skin,
		returnid:id,
		returndoc:document
	}
	Dialog(url,function(returnval){
		if (!returnval) { // user must have pressed Cancel
			return false;
		} else{
			if(id == 'src'){switchType(returnval);}
			generatePreview();
			return true;
		}
	},param);
}

function setBool(pl, p, n) {
	if (typeof(pl[n]) == "undefined")
		return;
	document.forms[0].elements[p + "_" + n].checked = pl[n];
}

function setStr(pl, p, n) {
	var f = document.forms[0], e = f.elements[(p != null ? p + "_" : '') + n];

	if (typeof(pl[n]) == "undefined")
		return;

	if (e.type == "text")
		e.value = pl[n];
	else
		selectByValue(f, (p != null ? p + "_" : '') + n, pl[n]);
}

function setStrFlashV(pl, p, n){
	var f = document.forms[0], a, b, m;
	if(n == 'file'){
		var d = document.getElementById(n);
	}else{
		try{var d = document.getElementById(p + "_" + n);}catch(e){}
	}
	m = n;
	for(n in pl){
		if(n == 'flashvars'){
			a = pl[n].split('&');
			for(var x=0;x<a.length;x++){
				b = a[x].split('=');
				if(b[0] == m){
					try{
						if (d.type == "text")
							d.value = b[1];
						else
							selectByValue(f, (p != null ? p + "_" : '') + m, b[1]);
					}catch(e){
						return(b[1]);
					}
				}
			}
		}
	}
}

function getBool(p, n, d, tv, fv) {
	var v = document.forms[0].elements[p + "_" + n].checked;

	tv = typeof(tv) == 'undefined' ? 'true' : "'" + jsEncode(tv) + "'";
	fv = typeof(fv) == 'undefined' ? 'false' : "'" + jsEncode(fv) + "'";

	return (v == d) ? '' : n + (v ? ':' + tv + ',' : ':' + fv + ',');
}

function getStr(p, n, d) {
	var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
	var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;
	return ((n == d || v == '') ? '' : n + ":'" + jsEncode(v) + "',");
}

function FlashVars(p, n, d){
	var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
	if(n.match('color')){
		e.value = e.value.replace('#','0x');
	}
	var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;
	return ((n == d || v == '') ? '' : n + "=" + jsEncode(v) + "&");
}
	
function getInt(p, n, d) {
	var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
	var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;

	return ((n == d || v == '') ? '' : n + ":" + v.replace(/[^0-9]+/g, '') + ",");
}

function onCancel() {
  __dlg_close( null );
  return false;
}

function insertMedia() {
	var fe, f = document.forms[0], h;

	if (!AutoValidator.validate(f)) {
		alert("Invalid data entered. Please Adjust and try again.");
		return false;
	}
	f.width.value = f.width.value == "" ? 100 : f.width.value;
	f.height.value = f.height.value == "" ? 100 : f.height.value;
	
		switch (f.media_type.options[f.media_type.selectedIndex].value) {
			case "flash":
				h = ' class="mediaItemFlash"';
				break;
	
			case "shockwave":
				h = ' class="mediaItemShockWave"';
				break;
	
			case "qt":
				h = ' class="mediaItemQuickTime"';
				break;
	
			case "wmp":
				h = ' class="mediaItemWindowsMedia"';
				break;
	
			case "rmp":
				h = ' class="mediaItemRealMedia"';
				break;
			
			case "flv|mp3":
				h = ' class="mediaItemFlv"';
				break;
				
			case "playlist":
				h = ' class="mediaItemPlaylist"';
				break;
		}
	
		h += ' title="' + serializeParameters() + '"';
		h += ' width="' + f.width.value + '"';
		h += ' height="' + f.height.value + '"';
		h += ' align="' + f.align.options[f.align.selectedIndex].value + '"';
	
		h += ' />';
		
	__dlg_close(h);
}

function getType(v){
	var fo, i, c, el, x, f = document.forms[0];
	fo = ("flash=swf;shockwave=dcr;qt=mov,qt,mpg,mp4,mpeg,wav;shockwave=dcr;wmp=avi,wmv,wm,asf,asx,wmx,wvx;rmp=rm,ra,ram;flv|mp3=mp3,flv;playlist=xml").split(';');
	// YouTube
	if (v.indexOf('http://www.youtube.com/watch?v=') == 0 || v.indexOf('http://youtube.com/watch?v=') == 0) {
		f.width.value = '425';
		f.height.value = '350';

		v = v.replace('http://youtube.com/watch?v=', '');
		v = v.replace('http://www.youtube.com/watch?v=', '');

		f.src.value = 'http://www.youtube.com/v/' + v;
		return 'flash';
	}
	// Google video
	if (v.indexOf('http://video.google.com/videoplay?docid=') == 0) {
		f.width.value = '425';
		f.height.value = '326';
		f.src.value = 'http://video.google.com/googleplayer.swf?docId=' + v.substring('http://video.google.com/videoplay?docid='.length) + '&hl=en';
		return 'flash';
	}
	
	if (v.indexOf('http://video.google.co.uk/videoplay?docid=') == 0) {
		f.width.value = '425';
		f.height.value = '326';
		f.src.value = 'http://video.google.com/googleplayer.swf?docId=' + v.substring(('http://video.google.com/videoplay?docid='.length)+2) + '&hl=en-GB';
		return 'flash';
	}

	for (i=0; i<fo.length; i++) {
		c = fo[i].split('=');
		el = c[1].split(',');
		for (x=0; x<el.length; x++)
		if (v.indexOf('.' + el[x]) != -1)
			return c[0];
	}

	return null;
}

function switchType(v) {
	var t = getType(v), d = document, f = d.forms[0];
	if (!t)
		return;

	selectByValue(d.forms[0], 'media_type', t);
	changedType(t);

	// Update qtsrc also
	if (t == 'qt' && f.src.value.toLowerCase().indexOf('rtsp://') != -1) {
		alert('Streamed rtsp resources should be added to the QT Src field under the advanced tab.\nYou should also add a non streamed version to the Src field..');

		if (f.qt_qtsrc.value == '')
			f.qt_qtsrc.value = f.src.value;
	}
}

function selectByValue(form_obj, field_name, value, add_custom, ignore_case) {
	if (!form_obj || !form_obj.elements[field_name])
		return;

	var sel = form_obj.elements[field_name];

	var found = false;
	for (var i=0; i<sel.options.length; i++) {
		var option = sel.options[i];

		if (option.value == value || (ignore_case && option.value.toLowerCase() == value.toLowerCase())) {
			option.selected = true;
			found = true;
		} else
			option.selected = false;
	}

	if (!found && add_custom && value != '') {
		var option = new Option(value, value);
		option.selected = true;
		sel.options[sel.options.length] = option;
		sel.selectedIndex = sel.options.length - 1;
	}

	return found;
}

function changedType(t) {
	var d = document,v,splitfile,getfile;
	d.getElementById('flash_options').style.display = 'none';
	d.getElementById('qt_options').style.display = 'none';
	d.getElementById('shockwave_options').style.display = 'none';
	d.getElementById('wmp_options').style.display = 'none';
	d.getElementById('rmp_options').style.display = 'none';
	d.getElementById('flv|mp3_options').style.display = 'none';
	d.getElementById('playlist_options').style.display = 'none';
	d.getElementById(t + '_options').style.display = 'block';
	d.getElementById('file').value = d.getElementById('src').value;
	if (t == 'playlist'){
		splitfile = d.getElementById('src').value.split('/');
		getfile = Number(splitfile.length) - 1; 
		//alert(splitfile[getfile]);
		document.getElementById('playlist_name').value = splitfile[getfile].replace('.xml','');
		uploadXml('view');
	}
}

function serializeParameters() {
	var d = document, f = d.forms[0], s = '', param = window.dialogArguments;

	switch (f.media_type.options[f.media_type.selectedIndex].value) {
		case "flash":
			s += getBool('flash', 'play', true);
			s += getBool('flash', 'loop', true);
			s += getBool('flash', 'menu', true);
			s += getBool('flash', 'swliveconnect', false);
			s += getStr('flash', 'quality');
			s += getStr('flash', 'scale');
			s += getStr('flash', 'salign');
			s += getStr('flash', 'wmode');
			s += getStr('flash', 'base');
			s += getStr('flash', 'flashvars');
		break;

		case "qt":
			s += getBool('qt', 'loop', false);
			s += getBool('qt', 'autoplay', true);
			s += getBool('qt', 'cache', false);
			s += getBool('qt', 'controller', true);
			s += getBool('qt', 'correction', false, 'none', 'full');
			s += getBool('qt', 'enablejavascript', false);
			s += getBool('qt', 'kioskmode', false);
			s += getBool('qt', 'autohref', false);
			s += getBool('qt', 'playeveryframe', false);
			s += getBool('qt', 'targetcache', false);
			s += getStr('qt', 'scale');
			s += getStr('qt', 'starttime');
			s += getStr('qt', 'endtime');
			s += getStr('qt', 'target');
			s += getStr('qt', 'qtsrcchokespeed');
			s += getStr('qt', 'volume');
			s += getStr('qt', 'qtsrc');
		break;

		case "shockwave":
			s += getBool('shockwave', 'sound');
			s += getBool('shockwave', 'progress');
			s += getBool('shockwave', 'autostart');
			s += getBool('shockwave', 'swliveconnect');
			s += getStr('shockwave', 'swvolume');
			s += getStr('shockwave', 'swstretchstyle');
			s += getStr('shockwave', 'swstretchhalign');
			s += getStr('shockwave', 'swstretchvalign');
		break;

		case "wmp":
			s += getBool('wmp', 'autostart', true);
			s += getBool('wmp', 'enabled', false);
			s += getBool('wmp', 'enablecontextmenu', true);
			s += getBool('wmp', 'fullscreen', false);
			s += getBool('wmp', 'invokeurls', true);
			s += getBool('wmp', 'mute', false);
			s += getBool('wmp', 'stretchtofit', false);
			s += getBool('wmp', 'windowlessvideo', false);
			s += getStr('wmp', 'balance');
			s += getStr('wmp', 'baseurl');
			s += getStr('wmp', 'captioningid');
			s += getStr('wmp', 'currentmarker');
			s += getStr('wmp', 'currentposition');
			s += getStr('wmp', 'defaultframe');
			s += getStr('wmp', 'playcount');
			s += getStr('wmp', 'rate');
			s += getStr('wmp', 'uimode');
			s += getStr('wmp', 'volume');
		break;

		case "rmp":
			s += getBool('rmp', 'autostart', false);
			s += getBool('rmp', 'loop', false);
			s += getBool('rmp', 'autogotourl', true);
			s += getBool('rmp', 'center', false);
			s += getBool('rmp', 'imagestatus', true);
			s += getBool('rmp', 'maintainaspect', false);
			s += getBool('rmp', 'nojava', false);
			s += getBool('rmp', 'prefetch', false);
			s += getBool('rmp', 'shuffle', false);
			s += getStr('rmp', 'console');
			s += getStr('rmp', 'controls');
			s += getStr('rmp', 'numloop');
			s += getStr('rmp', 'scriptcallbacks');
		break;
		
		case "flv|mp3":
		break;
		
		case "playlist":
		break;

	}
	s += getStr(null, 'id');
	s += getStr(null, 'name');
	
	if(f.media_type.options[f.media_type.selectedIndex].value == "flv|mp3"){
		s += 'src:\''+param['homesrc']+'mediaplayer.swf\','; 
		s += 'allowscriptaccess:\'always\',';
		s += 'allowfullscreen:\'true\',';
		s += 'flashvars:\'';	
		s += FlashVars(null, 'file');
		s += FlashVars('flv|mp3', 'screencolor');
		s += FlashVars('flv|mp3', 'lightcolor');
		s += FlashVars('flv|mp3', 'frontcolor');
		s += FlashVars('flv|mp3', 'backcolor');
		s += FlashVars('flv|mp3', 'autostart');
		s += FlashVars('flv|mp3', 'repeat');
		s += FlashVars('flv|mp3', 'bufferlength');
		s += FlashVars('flv|mp3', 'shuffle');
		s += FlashVars('flv|mp3', 'showicons');
		s += FlashVars('flv|mp3', 'shownavigation');
		s += FlashVars('flv|mp3', 'showstop');
		s += FlashVars('flv|mp3', 'showdigits');
		s += FlashVars('flv|mp3', 'usefullscreen');
		s += FlashVars('flv|mp3', 'showdownload');
		s += FlashVars('flv|mp3', 'image');
		s += FlashVars('flv|mp3', 'logo');
		s += '\',';
	}else if(f.media_type.options[f.media_type.selectedIndex].value == "playlist"){
		s += 'src:\''+param['homesrc']+'mediaplayer.swf\','; 
		s += 'allowscriptaccess:\'always\',';
		s += 'allowfullscreen:\'true\',';
		s += 'flashvars:\'';	
		s += FlashVars(null, 'file');
		s += FlashVars('playlist', 'screencolor');
		s += FlashVars('playlist', 'lightcolor');
		s += FlashVars('playlist', 'frontcolor');
		s += FlashVars('playlist', 'backcolor');
		s += FlashVars('playlist', 'autostart');
		s += FlashVars('playlist', 'repeat');
		s += FlashVars('playlist', 'bufferlength');
		s += FlashVars('playlist', 'shuffle');
		s += FlashVars('playlist', 'showicons');
		s += FlashVars('playlist', 'shownavigation');
		s += FlashVars('playlist', 'showstop');
		s += FlashVars('playlist', 'showdigits');
		s += FlashVars('playlist', 'usefullscreen');
		s += FlashVars('playlist', 'showdownload');
		s += FlashVars('playlist', 'image');
		s += FlashVars('playlist', 'logo');
		s += FlashVars('playlist', 'autoscroll');
		s += FlashVars('playlist', 'thumbsinplaylist');
		var playlistsize = document.getElementById('playlist_size').value;
		var playlistpos = document.getElementById('playlist_position').value;
		if(playlistpos == 'right'){
			s += 'displaywidth=';
			var pos = 'width';
		}else{
			s += 'displayheight=';
			var pos = 'height';
		}
		var size = document.getElementById(pos).value;
		if(size == ''){size = 100};
		playlistsize = Number(size) - Number(playlistsize);
		if(playlistsize < 0){playlistsize = 0};
		s += playlistsize;
		s += '&\',';	
	}else{
		s += getStr(null, 'src');
	}
	s += getStr(null, 'align');
	s += getStr(null, 'bgcolor');
	s += getInt(null, 'vspace');
	s += getInt(null, 'hspace');
	s += getStr(null, 'width');
	s += getStr(null, 'height');

	s = s.length > 0 ? s.substring(0, s.length - 1) : s;

	return s;
}

function jsEncode(s) {
	s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
	s = s.replace(new RegExp('"', 'g'), '\\"');
	s = s.replace(new RegExp("'", 'g'), "\\'");
	return s;
}

function generatePreview(c) {
	var f = document.forms[0], p = document.getElementById('prev'), h = '', cls, pl, n, type, codebase, wp, hp, nw, nh;
	try{
		p.innerHTML = '<!-- x --->';
	}catch(e){
		 p = parent.window.document.getElementById('prev');
	}
	try{
		nw = parseInt(f.width.value);
		nh = parseInt(f.height.value);
	}catch(e){
		nw = 120;
	}

	try{
		if (f.width.value != "" && f.height.value != "") {
			if (f.constrain.checked) {
				if (c == 'width' && oldWidth != 0) {
					wp = nw / oldWidth;
					nh = Math.round(wp * nh);
					f.height.value = nh;
				} else if (c == 'height' && oldHeight != 0) {
					hp = nh / oldHeight;
					nw = Math.round(hp * nw);
					f.width.value = nw;
				}
			}
		}
		if (f.width.value != "")
			oldWidth = nw;
	
		if (f.height.value != "")
			oldHeight = nh;
	
	// After constrain
	pl = serializeParameters();
	type = f.media_type.options[f.media_type.selectedIndex].value;
	}catch(e){
		type = getType(c);
		if(type != null){
			if(type == 'flv|mp3' || type == 'playlist'){
				if(type == 'playlist'){var ex = '&displaywidth=100&autoscroll=true';}
				else{var ex = '';}
				pl = {
					src: "../Media/mediaplayer.swf",
					width: 130,
					height: 110,
					flashvars: "file=" + c + ex
				};
			}else{
				pl = {
					src: c,
					width: 130,
					height: 110
				};
			}
		}else{
			try{
				var img = parent.window.document.getElementById('f_preview');
				img.src = "img/1x1_transparent.gif";
				img.removeAttribute('width');
				img.removeAttribute('height');
				img.setAttribute('border','');
			}
			catch(e){
				p.innerHTML =  '<img src="img/1x1_transparent.gif" alt="" id="f_preview" />';
			}
			return;
		}
	}
	switch (type) {
		case "flash":
			cls = 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000';
			codebase = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
			type = 'application/x-shockwave-flash';
			break;

		case "shockwave":
			cls = 'clsid:166B1BCA-3F9C-11CF-8075-444553540000';
			codebase = 'http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0';
			type = 'application/x-director';
			break;

		case "qt":
			cls = 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';
			codebase = 'http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0';
			type = 'video/quicktime';
			break;

		case "wmp":
			cls = 'clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6';
			codebase = 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701';
			type = 'application/x-mplayer2';
			break;

		case "rmp":
			cls = 'clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA';
			codebase = 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701';
			type = 'audio/x-pn-realaudio-plugin';
			break;
			
		case "flv|mp3":
			cls = 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000';
			codebase = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
			type = 'application/x-shockwave-flash';	
		
		case "playlist":
			cls = 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000';
			codebase = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
			type = 'application/x-shockwave-flash';	
	}

	if (pl == '') {
		p.innerHTML = '';
		return;
	}
	try{
		pl = eval('x={' + pl + '};');
	}catch(e){
	}

	if (!pl.src) {
		p.innerHTML = '';
		return;
	}

	pl.width = !pl.width ? 100 : pl.width;
	pl.height = !pl.height ? 100 : pl.height;
	pl.id = !pl.id ? 'obj' : pl.id;
	pl.name = !pl.name ? 'eobj' : pl.name;
	pl.align = !pl.align ? '' : pl.align;

	h += '<object classid="clsid:' + cls + '" codebase="' + codebase + '" width="' + pl.width + '" height="' + pl.height + '" id="' + pl.id + '" name="' + pl.name + '" align="' + pl.align + '">';

	for (n in pl) {
		h += '<param name="' + n + '" value="' + pl[n] + '">';

		// Add extra url parameter if it's an absolute URL
		if (n == 'src' && pl[n].indexOf('://') != -1)
			h += '<param name="url" value="' + pl[n] + '" />';
	}

	h += '<embed type="' + type + '" ';

	for (n in pl)
		h += n + '="' + pl[n] + '" ';

	h += '></embed></object>';

	p.innerHTML = "<!-- x --->" + h;
}

function _utf8_encode(string) {
	string = string.replace(/\r\n/g,"\n");
	var utftext = "";

	for (var n = 0; n < string.length; n++) {

		var c = string.charCodeAt(n);

		if (c < 128) {
			utftext += String.fromCharCode(c);
		}
		else if((c > 127) && (c < 2048)) {
			utftext += String.fromCharCode((c >> 6) | 192);
			utftext += String.fromCharCode((c & 63) | 128);
		}
		else {
			utftext += String.fromCharCode((c >> 12) | 224);
			utftext += String.fromCharCode(((c >> 6) & 63) | 128);
			utftext += String.fromCharCode((c & 63) | 128);
		}
	}

	return utftext;
}
function _utf8_decode(utftext) {
	var string = "";
	var i = 0;
	var c = c1 = c2 = 0;

	while ( i < utftext.length ) {
		c = utftext.charCodeAt(i);

		if (c < 128) {
			string += String.fromCharCode(c);
			i++;
		}
		else if((c > 191) && (c < 224)) {
			c2 = utftext.charCodeAt(i+1);
			string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
			i += 2;
		}
		else {
			c2 = utftext.charCodeAt(i+1);
			c3 = utftext.charCodeAt(i+2);
			string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
			i += 3;
		}

	}

	return string;
}
		
function encode(string){
	return escape(_utf8_encode(string));
}

function decode(string){
	return _utf8_decode(unescape(string));
}
function randomNumber(){
	var idlen=4, id='';
	for(i=1;i<=idlen;i++)
	id+=Math.floor(Math.random() * 16.0).toString(4);
	return id;
}