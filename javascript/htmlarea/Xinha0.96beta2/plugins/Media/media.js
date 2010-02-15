Media._pluginInfo = {
	name:"Xinha Media Inserter",
	version:"1.0",
	developer:"John Jenkins",
	license:"htmlArea"
};
function Media(editor) {
    this.editor = editor;      
    var cfg = editor.config;
	var toolbar = cfg.toolbar;
	var self = this;
	cfg.registerButton({
		id       : "InsertMedia",
		tooltip  : this._lc("Insert/edit Media"),
		image    : editor.imgURL("media.gif", "Media"),
		textMode : false,
		action   : function(editor) {
						self.buttonPress(editor);
				   }
     });
	cfg.addToolbarElement(["InsertMedia"],"insertimage", 1);
};

Media.prototype._lc = function(string) {
    return Xinha._lc(string, 'Media');
}

var playlist;
var XMLHttpRequestObject = false; 
	if (window.XMLHttpRequest){
		XMLHttpRequestObject = new XMLHttpRequest();
	}else if (window.ActiveXObject){
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}

if(XMLHttpRequestObject){
	XMLHttpRequestObject.open("GET", _editor_url+"plugins/Media/"+"efm_info.php?"+randomNumber()); 
	XMLHttpRequestObject.onreadystatechange = function(){
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200){ 
			try{
				var text = XMLHttpRequestObject.responseText;
				playlist = text;
			}catch(e){}
		}
		if (XMLHttpRequestObject.readyState == 1) {
			try{
			}catch(e){}
		}
	} 
	XMLHttpRequestObject.send(null); 
}

Media.prototype.buttonPress = function(editor) {
	var _a, _b, inparam, manager;
	
	//see if we are in an existing media div
	_a = editor.getSelectedHTML();
	_b = editor._getSelection();
	a = editor._activeElement(_b);
	if(!(a != null && !/mediaItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia)/.test(a.className))){
		a = editor._getFirstAncestor(_b,"img");
	}	
	if(typeof(editor.config.ExtendedFileManager) != "undefined"){
		manager = "set";
	}else{
		manager = "unset";
		playlist = "null";
	}
	if(a!=null&&a.tagName.toLowerCase()=="img"){
		inparam={
			type:a.className,
			src:a.src,
			variables:a.title,
			width:a.width,
			height:a.height,
			filemanager:manager,
			homesrc:''+ _editor_url + 'plugins/Media/',
			"playlist":playlist 
		};
	}else{
		inparam={
			homesrc:''+ _editor_url + 'plugins/Media/',
			filemanager:manager,
			"playlist":playlist
		};
	}
	
	editor._popupDialog("plugin://Media/media.html", function(outparam) {
		if (!outparam) {	// user pressed Cancel
			return false;
		}
		else{ //insert new
			h = '<img src="' + _editor_url + 'plugins/Media/img/trans.gif" '+outparam+'';
			if(Xinha.is_ie){
				try{
					a.parentNode.removeChild(a);
				}catch(e){}
				editor.insertHTML(h);
			}else{
				editor.insertHTML(h);
			}
		}
	}, inparam);
};

Media.prototype.onGenerate=function(){
	var _6="Media-style";
	var _7=this.editor._doc.getElementById(_6);
	if(_7==null){
		_7=this.editor._doc.createElement("link");
		_7.id=_6;
		_7.rel="stylesheet";
		_7.href=_editor_url+"plugins/Media/content.css";
		this.editor._doc.getElementsByTagName("HEAD")[0].appendChild(_7);
	}
};

Media.prototype.outwardHtml = function(html){
	var startPos = -1, endPos, invalue, attribs, chunkBefore, chunkAfter, embedHTML, at, pl, cb, mt, ex, ci;
	while ((startPos = html.indexOf('<img', startPos+1)) != -1) {
		endPos = html.indexOf('/>', startPos);
		invalue = html.substring(startPos + 4, endPos);
		attribs = parseAttributes(invalue);
		
		if(/width/i.test(attribs['style'])){
			var split_style_val, next;
			var split_style = attribs['style'].split(":"); 
			for(var i=0;i<split_style.length;i++){
				if(/width/i.test(split_style[i])){
					next = i+1;
					split_style_val = split_style[next].split(";"); 
					attribs['width'] = split_style_val[0].replace(/px/g,'');
				}
				if(/height/i.test(split_style[i])){
					next = i+1;
					split_style_val = split_style[next].split(";");
					attribs['height'] = split_style_val[0].replace(/px/g,'');
				}
			}
		}
	try{
		attribs['height'] = attribs['height'].replace(/^\s+/,'');
		attribs['width'] = attribs['width'].replace(/^\s+/,'');
	}catch(e){}
		
		if (!/mediaItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|Flv)/.test(attribs['class']))
			continue;

		endPos += 2;
		// Parse attributes
		at = attribs['title'];
		if (at) {
			at = at.replace(/&(#39|apos);/g, "'");
			at = at.replace(/&#quot;/g, '"');
			
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
		}

		// Use object/embed
		//if (getParam('media_use_script', false)){
			switch (attribs['class']) {
				case 'mediaItemFlash':
					ci = 'd27cdb6e-ae6d-11cf-96b8-444553540000';
					cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
					mt = 'application/x-shockwave-flash';
					break;
				case 'mediaItemShockWave':
					ci = '166B1BCA-3F9C-11CF-8075-444553540000';
					cb = 'http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0';
					mt = 'application/x-director';
					break;
				case 'mediaItemWindowsMedia':
					ci = '6BF52A52-394A-11D3-B153-00C04F79FAA6';
					cb = 'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701';
					mt = 'application/x-mplayer2';
					break;
				case 'mediaItemQuickTime':
					ci = '02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';
					cb = 'http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0';
					mt = 'video/quicktime';
					break;
				case 'mediaItemRealMedia':
					ci = 'CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA';
					cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
					mt = 'audio/x-pn-realaudio-plugin';
					break;
				case 'mediaItemFlv':
					ci = 'd27cdb6e-ae6d-11cf-96b8-444553540000';
					cb = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0';
					mt = 'application/x-shockwave-flash';
					break;
			}
			embedHTML = getEmbed(ci, cb, mt, pl, attribs);
		/*} else {
			// Use script version
			switch (attribs['class']) {
				case 'mediaItemFlash':
					s = 'writeFlash';
					break;

				case 'mediaItemShockWave':
					s = 'writeShockWave';
					break;

				case 'mediaItemWindowsMedia':
					s = 'writeWindowsMedia';
					break;

				case 'mediaItemQuickTime':
					s = 'writeQuickTime';
					break;

				case 'mediaItemRealMedia':
					s = 'writeRealMedia';
					break;
			}

			if (attribs.width)
				at = at.replace(/width:[^0-9]?[0-9]+%?[^0-9]?/g, "width:'" + attribs.width + "'");
			if (attribs.height)
				at = at.replace(/height:[^0-9]?[0-9]+%?[^0-9]?/g, "height:'" + attribs.height + "'");
			// Force absolute URL
			pl.src = tinyMCE.convertURL(pl.src, null, true);
			at = at.replace(new RegExp("src:'[^']*'", "g"), "src:'" + pl.src + "'");
			embedHTML = '<script type="text/javascript">' + s + '({' + at + '});</script>';
		}*/
		// Insert embed/object chunk
		chunkBefore = html.substring(0, startPos);
		chunkAfter = html.substring(endPos);
		html = chunkBefore + embedHTML + chunkAfter;
	}
	
	return html;
};

function getEmbed(cls, cb, mt, p, at) {
	var h = '', n;

	p.width = at.width ? at.width : p.width;
	p.height = at.height ? at.height : p.height;

	h += '<object classid="clsid:' + cls + '" codebase="' + cb + '"';
	h += typeof(p.id) != "undefined" ? ' id="' + p.id + '"' : '';
	h += typeof(p.name) != "undefined" ? ' name="' + p.name + '"' : '';
	h += typeof(p.width) != "undefined" ? ' width="' + p.width + '"' : '';
	h += typeof(p.height) != "undefined" ? ' height="' + p.height + '"' : '';
	h += typeof(p.align) != "undefined" ? ' align="' + p.align + '"' : '';
	h += '>';

	for (n in p) {
		if (typeof(p[n]) != "undefined" && typeof(p[n]) != "function") {
			h += '<param name="' + n + '" value="' + p[n] + '" />';

			// Add extra url parameter if it's an absolute URL on WMP
			if (n == 'src' && p[n].indexOf('://') != -1 && mt == 'application/x-mplayer2')
				h += '<param name="url" value="' + p[n] + '" />';
		}
	}

	h += '<embed type="' + mt + '"';

	for (n in p) {
		if (typeof(p[n]) == "function")
			continue;

		// Skip url parameter for embed tag on WMP
		if (!(n == 'url' && mt == 'application/x-mplayer2'))
			h += ' ' + n + '="' + p[n] + '"';
	}

	h += '></embed></object>';

	return h;
}
 
 function parseAttributes(attribute_string) {
	var attributeName = "", endChr = '"';
	var attributeValue = "";
	var withInName;
	var withInValue;
	var attributes = new Array();
	var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');

	if (attribute_string == null || attribute_string.length < 2)
		return null;

	withInName = withInValue = false;

	for (var i=0; i<attribute_string.length; i++) {
		var chr = attribute_string.charAt(i);

		if ((chr == '"' || chr == "'") && !withInValue) {
			withInValue = true;
			endChr = chr;
		} else if (chr == endChr && withInValue) {
			withInValue = false;

			var pos = attributeName.lastIndexOf(' ');
			if (pos != -1)
				attributeName = attributeName.substring(pos+1);

			attributes[attributeName.toLowerCase()] = attributeValue.substring(1);

			attributeName = "";
			attributeValue = "";
		} else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
			withInName = true;

		if (chr == '=' && withInName)
			withInName = false;

		if (withInName)
			attributeName += chr;

		if (withInValue)
			attributeValue += chr;
	}

	return attributes;
}

Media.prototype.inwardHtml = function(html){
	var startPos = -1, endPos, invalue, attribs, chunkBefore, chunkAfter, img, startPosEmbed, endPosEmbed, ci, attribsob, count;
	
	while ((startPos = html.indexOf('<object', startPos+1)) != -1) {
		endPos = html.indexOf('</object>', startPos);
		invalueOb = html.substring(startPos + 7, endPos);
		attribsob = parseAttributes(invalueOb);
		endPos += 9;
	
		while ((startPosEmbed = invalueOb.indexOf('<embed', startPosEmbed+1)) != -1) {
			endPosEmbed = invalueOb.indexOf('</embed>', startPosEmbed);
			invalue = invalueOb.substring(startPosEmbed + 6, endPosEmbed);
			attribs = parseAttributes(invalue);
			endPosEmbed += 8;
			
			switch (attribs['type']) {
			case 'application/x-shockwave-flash':
				ci = 'mediaItemFlash';
				break;
			case 'application/x-director':
				ci = 'mediaItemShockWave';
				break;
			case 'application/x-mplayer2':
				ci = 'mediaItemWindowsMedia';
				break;
			case 'video/quicktime':
				ci = 'mediaItemQuickTime';
				break;
			case 'audio/x-pn-realaudio-plugin':
				ci = 'mediaItemRealMedia';
				break;
			}
			if(attribs['allowscriptaccess']){
				ci = 'mediaItemFlv';
			}
			img = makeIMG(ci, attribs);
		}
		chunkBefore = html.substring(0, startPos);
		chunkAfter = html.substring(endPos);
		html = chunkBefore + img + chunkAfter;
	}
	return html;
};

function makeIMG(ci, at) {
	
	var h = '', n;

	h += '<img src="' + _editor_url + 'plugins/Media/img/trans.gif" ';
	h += ' class="' + ci + '"';
	h += ' title="';
	for(n in at){
		if(typeof(at[n]) != "undefined" && typeof(at[n]) != "function" && n != 'type'){
			h += ""+ n +":'"+at[n]+"',";
		}
	}
	h += '"';
	h += typeof(at.id) != "undefined" ? ' id="' + at.id + '"' : '';
	h += typeof(at.name) != "undefined" ? ' name="' + at.name + '"' : '';
	h += typeof(at.width) != "undefined" ? ' width="' + at.width + '"' : '';
	h += typeof(at.height) != "undefined" ? ' height="' + at.height + '"' : '';
	h += typeof(at.align) != "undefined" ? ' align="' + at.align + '"' : '';
	h += typeof(at.style) != "undefined" ? ' style="' + at.style + '"' : '';
	h += '/>';

	return h;

}

function getParam(name, default_value, strip_whitespace, split_chr) {
	var i, outArray, value = (typeof(this.settings[name]) == "undefined") ? default_value : this.settings[name];

	// Fix bool values
	if (value == "true" || value == "false")
		return (value == "true");

	if (strip_whitespace)
		value = regexpReplace(value, "[ \t\r\n]", "");

	if (typeof(split_chr) != "undefined" && split_chr != null) {
		value = value.split(split_chr);
		outArray = [];

		for (i=0; i<value.length; i++) {
			if (value[i] && value[i] !== '')
				outArray[outArray.length] = value[i];
		}

		value = outArray;
	}

	return value;
}

function doScript(param, id)
{
	scr = '<script type="text/freezescript">\nvar fo = new SWFObject("' + param["f_url"] + '", "o' + id + '", "' + param["f_width"] + '", "' + param["f_height"] + '", "8", "' + param["f_bgcolor"] + '");\n';
	var fields = ["align", "quality", "play", "loop", "menu", "devfont", "wmode"];
	for (i=0;i<fields.length;i++) 
	scr+='  fo.addParam("' + fields[i] + '", "' + param['f_'+fields[i]] + '");\n';
	//additional parameters  
	var ar=param["f_addparams"].split("\n");
	for(i=0;i<ar.length;i++) {
	if(ar[i].indexOf("=")>0)
		scr+='  fo.addParam("' + ar[i].split("=")[0] + '", "' + ar[i].split("=")[1] + '");\n';
	}
	scr+='  fo.write("' + id + '")\n</script>\n';  
	return scr;  
}

function doDiv(param,id, scr)
{
	var div='<div class="FlashOverlay" id="' + id + '" style="height:' + param["f_height"] + ';width:' + param["f_width"] + '; border: 1px solid' + param["f_bgcolor"] + ';">\n';
	div+=param["f_alttext"]
	div+='<script type="text/freezescript" src="' + (param["f_scripturl"]) + '"></script>';
	div+= scr + '</div>\n';
	return div;
}
//Need to create 'guid', because incremental ids are not enough - we may have several
// different contents displayed on the same page
function randomNumber(){
	var idlen=16, id='';
	for(i=1;i<=idlen;i++)
	id+=Math.floor(Math.random() * 16.0).toString(16);
	return id;
}