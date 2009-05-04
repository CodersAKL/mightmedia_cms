/*function $(e){if(typeof e=='string')e=document.getElementById(e);return e};
function collect(a,f){var n=[];for(var i=0;i<a.length;i++){var v=f(a[i]);if(v!=null)n.push(v)}return n};

ajax={};
ajax.x=function(){try{return new ActiveXObject('Msxml2.XMLHTTP')}catch(e){try{return new ActiveXObject('Microsoft.XMLHTTP')}catch(e){return new XMLHttpRequest()}}};
ajax.serialize=function(f){var g=function(n){return f.getElementsByTagName(n)};var nv=function(e){if(e.name)return encodeURIComponent(e.name)+'='+encodeURIComponent(e.value);else return ''};var i=collect(g('input'),function(i){if((i.type!='radio'&&i.type!='checkbox')||i.checked)return nv(i)});var s=collect(g('select'),nv);var t=collect(g('textarea'),nv);return i.concat(s).concat(t).join('&');};
ajax.send=function(u,f,m,a){var x=ajax.x();x.open(m,u,true);x.onreadystatechange=function(){if(x.readyState==4)f(x.responseText)};if(m=='POST')x.setRequestHeader('Content-type','application/x-www-form-urlencoded');x.send(a)};
ajax.get=function(url,func){ajax.send(url,func,'GET')};
ajax.gets=function(url){var x=ajax.x();x.open('GET',url,false);x.send(null);return x.responseText};
ajax.post=function(url,func,args){ajax.send(url,func,'POST',args)};
ajax.update=function(url,elm){var e=$(elm);var f=function(r){e.innerHTML=r};ajax.get(url,f)};
ajax.submit=function(url,elm,frm){var e=$(elm);var f=function(r){e.innerHTML=r};ajax.post(url,f,ajax.serialize(frm))};



function display(src){
	html='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Photo</title></head><body style="margin:0;" onLoad="resize('+img.height+','+img.height+')"><div><img src="'+src+'" /></div><script>function resize(w,h){if(parseInt(navigator.appVersion)>3){if(navigator.appName=="Netscape"){popup.outerWidth=w;popup.outerHeight=h;}else{popup.resizeTo(w,h);}}}</script></body>';
	var img = new Image();
	img.src = src; 
	var leftPos = (screen.availWidth - img.width) / 2;
	var topPos = (screen.availHeight - img.height) / 2;
	popup=window.open('','image','toolbar=0,location=0,directories=0,menuBar=0,scrollbars=0,resizable=1,width=' + img.width + ',height=' + img.height + ',left=' + leftPos + ',top=' + topPos);
	popup.document.open();
	popup.document.write(html);
	popup.document.body.focus();
	popup.document.close();

};

//POPUPS
function popImage(imageURL,imageTitle){
	PositionX = 10;
	PositionY = 10;
	defaultWidth  = 1;
	defaultHeight = 1;

	//kinda important
	var AutoClose = true;
	
  var imgWin = window.open('','_blank','scrollbars=no,resizable=1,width='+defaultWidth+',height='+defaultHeight+',left='+PositionX+',top='+PositionY);
  if( !imgWin ) { return true; } //popup blockers should not cause errors
  imgWin.document.write('<html><head><title>'+imageTitle+'<\/title><script type="text\/javascript">\n'+
    'function resizeWinTo() {\n'+
    'if( !document.images.length ) { document.images[0] = document.layers[0].images[0]; }'+
    'var oH = document.images[0].height, oW = document.images[0].width;\n'+
    'if( !oH || window.doneAlready ) { return; }\n'+ //in case images are disabled
    'window.doneAlready = true;\n'+ //for Safari and Opera
    'var x = window; x.resizeTo( oW + 200, oH + 200 );\n'+
    'var myW = 0, myH = 0, d = x.document.documentElement, b = x.document.body;\n'+
    'if( x.innerWidth ) { myW = x.innerWidth; myH = x.innerHeight; }\n'+
    'else if( d && d.clientWidth ) { myW = d.clientWidth; myH = d.clientHeight; }\n'+
    'else if( b && b.clientWidth ) { myW = b.clientWidth; myH = b.clientHeight; }\n'+
    'if( window.opera && !document.childNodes ) { myW += 16; }\n'+
    'x.resizeTo( oW = oW + ( ( oW + 200 ) - myW ), oH = oH + ( (oH + 200 ) - myH ) );\n'+
    'var scW = screen.availWidth ? screen.availWidth : screen.width;\n'+
    'var scH = screen.availHeight ? screen.availHeight : screen.height;\n'+
    'if( !window.opera ) { x.moveTo(Math.round((scW-oW)/2),Math.round((scH-oH)/2)); }\n'+
    '}\n'+
    '<\/script>'+
    '<\/head><body onload="resizeWinTo();"'+(AutoClose?' onblur="self.close();"':'')+'>'+
    (document.layers?('<layer left="0" top="0">'):('<div style="position:absolute;left:0px;top:0px;display:table;">'))+
    '<img src="'+imageURL+'" alt="Loading image ..." title="" onload="resizeWinTo();">'+
    (document.layers?'<\/layer>':'<\/div>')+'<\/body><\/html>');
  imgWin.document.close();
  if( imgWin.focus ) { imgWin.focus(); }
  return false;
}
*/
/* BBCODE */
function mail(user, domain){window.location = 'mailto:'+user+'@'+domain;}
function flip( rid ) {
	document.getElementById(rid).style.display = document.getElementById(rid).style.display == 'none' ? 'block' : 'none'
}

function addText(elname, wrap1, wrap2) {
	if (document.selection) { // for IE 
		var str = document.selection.createRange().text;
		document.forms[elname].elements[elname].focus();
		var sel = document.selection.createRange();
		sel.text = wrap1 + str + wrap2;
		return;
	} else if ((typeof document.forms[elname].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var txtarea = document.forms[elname].elements[elname];
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		var oldScrollTop = txtarea.scrollTop;
		//if (selEnd == 1 || selEnd == 2)
		//selEnd = selLength;
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		txtarea.value = s1 + wrap1 + s2 + wrap2 + s3;
		txtarea.selectionStart = s1.length;
		txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
		txtarea.scrollTop = oldScrollTop;
		txtarea.focus();
		return;
	} else {
		insertText(elname, wrap1 + wrap2);
	}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function rodyk(id){
	if (document.getElementById){
		obj = document.getElementById(id);
		if (obj.style.display == "none"){
			obj.style.display = ""; createCookie(id,0,1);
		} else {
			obj.style.display = "none"; createCookie(id,1,1);
		}
	}
} 

function checkSearch() {
  if (document.getElementById && document.getElementById('search').value=="") {
    alert("Įrašyk paieškos frazę.");
    document.getElementById('search').focus();
    return false;
  } else {
    return true;
  }
}

function favorit() {
  if (document.all && navigator.userAgent.indexOf("Opera")==-1) {
	  document.write("<a href=\"javascript:window.external.addfavorite('http://'+document.domain,'HTMLSource: HTML Tutorials');\" class=\"nav\">Įtraukti į adresyną!</a><br />");
  } else {
	  document.write("Spausk Ctrl+D!");
  }
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function startCount() {
	var countfrom=parseInt(document.getElementById('sekundes').innerHTML); // Nuo kokio skaičiaus pradėti skaičiuoti
	var countnow=document.getElementById('sekundes').innerHTML=countfrom+1;
	countAction(countnow)
}

function countAction(countnow) {
	if (countnow!=0) {
		countnow-=1
		document.getElementById('sekundes').innerHTML=countnow;
	} else {
		window.location.href = unescape(window.location);
		return
	}
	setTimeout("countAction("+countnow+")",1000)
}
