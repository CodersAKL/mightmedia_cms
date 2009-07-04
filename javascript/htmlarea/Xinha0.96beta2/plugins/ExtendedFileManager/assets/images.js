/* This compressed file is part of Xinha. For uncompressed sources, forum, and bug reports, go to xinha.org */
/* The URL of the most recent version of this file is http://svn.xinha.webfactional.com/trunk/plugins/ExtendedFileManager/assets/images.js */
function i18n(_1){
return Xinha._lc(_1,"ExtendedFileManager");
}
function changeDir(_2){
showMessage("Loading");
var _3=window.top.document.getElementById("manager_mode").value;
var _4=window.top.document.getElementById("viewtype");
var _5=_4.options[_4.selectedIndex].value;
location.href=_backend_url+"__function=images&mode="+_3+"&dir="+_2+"&viewtype="+_5;
document.cookie="EFMStartDir"+_3+"="+_2;
}
function newFolder(_6,_7){
var _8=window.top.document.getElementById("manager_mode").value;
var _9=window.top.document.getElementById("viewtype");
var _a=_9.options[_9.selectedIndex].value;
location.href=_backend_url+"__function=images&mode="+_8+"&dir="+_6+"&newDir="+_7+"&viewtype="+_a;
}
function renameFile(_b){
var _c=_b.replace(/.*%2F/,"").replace(/\..*$/,"");
var _d=function(_e){
if(_e==""||_e==null||_e==_c){
alert(i18n("Cancelled rename."));
return false;
}
var _f=window.top.document.getElementById("manager_mode").value;
var _10=window.top.document.getElementById("dirPath");
var dir=_10.options[_10.selectedIndex].value;
_10=window.top.document.getElementById("viewtype");
var _12=_10.options[_10.selectedIndex].value;
location.href=_backend_url+"__function=images&mode="+_f+"&dir="+dir+"&rename="+_b+"&renameTo="+_e+"&viewtype="+_12;
};
if(Xinha.ie_version>6){
popupPrompt(i18n("Please enter new name for this file..."),_c,_d,i18n("Rename"));
}else{
var _13=prompt(i18n("Please enter new name for this file..."),_c);
_d(_13);
}
}
function renameDir(_14){
function rename(_15){
if(_15==""||_15==null||_15==_14){
alert(i18n("Cancelled rename."));
return false;
}
var _16=window.top.document.getElementById("manager_mode").value;
var _17=window.top.document.getElementById("dirPath");
var dir=_17.options[_17.selectedIndex].value;
_17=window.top.document.getElementById("viewtype");
var _19=_17.options[_17.selectedIndex].value;
location.href=_backend_url+"__function=images&mode="+_16+"&dir="+dir+"&rename="+_14+"&renameTo="+_15+"&viewtype="+_19;
}
if(Xinha.ie_version>6){
popupPrompt(i18n("Please enter new name for this folder..."),_14,rename,i18n("Rename"));
}else{
var _1a=prompt(i18n("Please enter new name for this folder..."),_14);
rename(_1a);
}
}
function copyFile(_1b,_1c){
var _1d=window.top.document.getElementById("dirPath");
var dir=_1d.options[_1d.selectedIndex].value;
window.top.pasteButton({"dir":dir,"file":_1b,"action":_1c+"File"});
}
function copyDir(_1f,_20){
var _21=window.top.document.getElementById("dirPath");
var dir=_21.options[_21.selectedIndex].value;
window.top.pasteButton({"dir":dir,"file":_1f,"action":_20+"Dir"});
}
function paste(_23){
var _24=window.top.document.getElementById("manager_mode").value;
var _25=window.top.document.getElementById("dirPath");
var dir=_25.options[_25.selectedIndex].value;
_25=window.top.document.getElementById("viewtype");
var _27=_25.options[_25.selectedIndex].value;
location.href=_backend_url+"__function=images&mode="+_24+"&dir="+dir+"&paste="+_23.action+"&srcdir="+_23.dir+"&file="+_23.file+"&viewtype="+_27;
}
function updateDir(_28){
var _29=window.top.document.getElementById("manager_mode").value;
document.cookie="EFMStartDir"+_29+"="+_28;
var _2a=window.top.document.getElementById("dirPath");
if(_2a){
for(var i=0;i<_2a.length;i++){
var _2c=_2a.options[i].text;
if(_2c==_28){
_2a.selectedIndex=i;
showMessage("Loading");
break;
}
}
}
}
function emptyProperties(){
toggleImageProperties(false);
var _2d=window.top.document;
_2d.getElementById("f_url").value="";
_2d.getElementById("f_alt").value="";
_2d.getElementById("f_title").value="";
_2d.getElementById("f_width").value="";
_2d.getElementById("f_margin").value="";
_2d.getElementById("f_height").value="";
_2d.getElementById("f_padding").value="";
_2d.getElementById("f_border").value="";
_2d.getElementById("f_borderColor").value="";
_2d.getElementById("f_backgroundColor").value="";
}
function toggleImageProperties(val){
var _2f=window.top.document;
if(val==true){
_2f.getElementById("f_width").value="";
_2f.getElementById("f_margin").value="";
_2f.getElementById("f_height").value="";
_2f.getElementById("f_padding").value="";
_2f.getElementById("f_border").value="";
_2f.getElementById("f_borderColor").value="";
_2f.getElementById("f_backgroundColor").value="";
}
_2f.getElementById("f_width").disabled=val;
_2f.getElementById("f_margin").disabled=val;
_2f.getElementById("f_height").disabled=val;
_2f.getElementById("f_padding").disabled=val;
_2f.getElementById("f_align").disabled=val;
_2f.getElementById("f_border").disabled=val;
_2f.getElementById("f_borderColor").value="";
_2f.getElementById("f_backgroundColor").value="";
_2f.getElementById("constrain_prop").disabled=val;
}
function selectImage(_30,alt,_32,_33){
var _34=window.top.document;
if(_34.getElementById("manager_mode").value=="image"){
var obj=_34.getElementById("f_url");
obj.value=_30;
obj=_34.getElementById("f_alt");
obj.value=alt;
obj=_34.getElementById("f_title");
obj.value=alt;
if(_32==0&&_33==0){
toggleImageProperties(true);
}else{
toggleImageProperties(false);
var obj=_34.getElementById("f_width");
obj.value=_32;
var obj=_34.getElementById("f_height");
obj.value=_33;
var obj=_34.getElementById("orginal_width");
obj.value=_32;
var obj=_34.getElementById("orginal_height");
obj.value=_33;
}
}else{
if(_34.getElementById("manager_mode").value=="link"){
try{
var obj=_34.getElementById("f_href");
obj.value=_30;
var obj=_34.getElementById("f_title");
obj.value=alt;
}catch(e){
}
}
}
update_selected();
return false;
}
var _current_selected=null;
function update_selected(){
var _36=window.top.document;
if(_current_selected){
_current_selected.className=_current_selected.className.replace(/(^| )active( |$)/,"$1$2");
_current_selected=null;
}
try{
var _37=_36.getElementById("f_url").value;
}catch(e){
try{
var _37=_36.getElementById("f_href").value;
}catch(e){
}
}
var _38=_36.getElementById("dirPath");
var _39=_38.options[_38.selectedIndex].text;
var dRe=new RegExp("^("+_39.replace(/([\/\^$*+?.()|{}[\]])/g,"\\$1")+")([^/]*)$");
if(dRe.test(_37)){
var _3b=document.getElementById("holder_"+asc2hex(RegExp.$2));
if(_3b){
_current_selected=_3b;
_3b.className+=" active";
}
}
showPreview(_37);
}
function asc2hex(str){
var _3d="";
for(var i=0;i<str.length;i++){
var hex=(str.charCodeAt(i)).toString(16);
if(hex.length==1){
hex="0"+hex;
}
_3d+=hex;
}
return _3d;
}
function showMessage(_40){
var _41=window.top.document;
var _42=_41.getElementById("message");
var _43=_41.getElementById("messages");
if(_42&&_43){
if(_42.firstChild){
_42.removeChild(_42.firstChild);
}
_42.appendChild(_41.createTextNode(i18n(_40)));
_43.style.display="block";
}
}
function updateDiskMesg(_44){
var _45=window.top.document;
var _46=_45.getElementById("diskmesg");
if(_46){
if(_46.firstChild){
_46.removeChild(_46.firstChild);
}
_46.appendChild(_45.createTextNode(_44));
}
}
function addEvent(obj,_48,fn){
if(obj.addEventListener){
obj.addEventListener(_48,fn,true);
return true;
}else{
if(obj.attachEvent){
var r=obj.attachEvent("on"+_48,fn);
return r;
}else{
return false;
}
}
}
function confirmDeleteFile(_4b){
if(confirm(i18n("Delete file \"$file="+_4b+"$\"?"))){
return true;
}
return false;
}
function confirmDeleteDir(dir,_4d){
if(confirm(i18n("Delete folder \"$dir="+dir+"$\"?"))){
return true;
}
return false;
}
function showPreview(_4e){
	try{
		try{
			var img = document.getElementById("f_preview");
			var test, border_t, borderColor_t, margin_t, padding_t, background_t, align_t; 
						
			if(document.getElementById("f_border")){
				border_t = document.getElementById("f_border").value;
				border_t = border_t.replace(new RegExp("[a-zA-Z]",'g'),'');
				border_t = (Number(border_t) / 2).toFixed(0);
				if(border_t < 1){border_t = 1;}
				if(document.getElementById("f_border").value == ''){
					border_t = 0;
				}else{
					test = border_t.length - 2;			
					if(border_t.substr(test,2) != 'px'){
						border_t = border_t + 'px';
					}
				}
			}
			if(document.getElementById("f_margin")){
				margin_t = document.getElementById("f_margin").value;
				margin_t = margin_t.replace(new RegExp("[a-zA-Z]",'g'),'');
				margin_t = (Number(margin_t) / 2).toFixed(0);
				if(margin_t < 1){margin_t = 1;}
				if(document.getElementById("f_margin").value == ''){
					margin_t = 0;
				}else{
					test = margin_t.length - 2;			
					if(margin_t.substr(test,2) != 'px'){
						margin_t = margin_t + 'px';
					}
				}
			}
			if(document.getElementById("f_padding")){
				padding_t = document.getElementById("f_padding").value;
				padding_t = padding_t.replace(new RegExp("[a-zA-Z]",'g'),'');
				padding_t = (Number(padding_t) / 2).toFixed(0);
				if(padding_t < 1){padding_t = 1;}
				if(document.getElementById("f_padding").value == ''){
					padding_t = 0;
				}else{
					test = padding_t.length - 2;			
					if(padding_t.substr(test,2) != 'px'){
						padding_t = padding_t + 'px';
					}
				}
			}
			if(document.getElementById("f_borderColor")){
				borderColor_t = document.getElementById("f_borderColor").value
			}
			if(document.getElementById("f_backgroundColor")){
				background_t = document.getElementById("f_backgroundColor").value
			}
			if(document.getElementById("f_align")){
				align_t = document.getElementById("f_align").value;
			}

			img.style.border = 'solid ' + border_t +' '+ borderColor_t +'';
			img.style.margin = margin_t;
			img.style.backgroundColor = background_t;
			img.style.padding = padding_t;
			img.align = align_t;
		}catch(e){
			var img = window.parent.document.getElementById("f_preview"); 
			img.src=_4e?window.parent._backend_url+"__function=thumbs&img="+_4e:window.parent.opener._editor_url+"plugins/ExtendedFileManager/img/1x1_transparent.gif";
		}
		}catch(e){
	}
}
try{
	addEvent(window,"load",init);
}catch(e){
}
