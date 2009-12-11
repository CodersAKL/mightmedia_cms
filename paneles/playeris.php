<?php
$text='


<!-- Location of javascript. -->
<script language="javascript" type="text/javascript" src="flashmp3player/swfobject.js" ></script>


<!-- Div that contains player. -->
<div id="player">
<h1>Nerastas flash grotuvas!</h1>
<p>Parsisiuskite flash grotuvą. <a href="http://www.macromedia.com/go/getflashplayer" >Jį rasite čia</a>.</p>
</div>

<!-- Script that embeds player. -->
<script language="javascript" type="text/javascript">
var so = new SWFObject("flashmp3player/flashmp3player.swf", "player", "100%", "247", "9"); // Location of swf file. You can change player width and height here (using pixels or percents).
so.addParam("quality", "high");
so.addVariable("content_path","../siuntiniai/media"); // Location of a folder with mp3 files (relative to php script).
so.addVariable("color_path","flashmp3player/default.xml"); // Location of xml file with color settings.
so.addVariable("script_path","flashmp3player/flashmp3player.php"); // Location of php script.
so.addParam("wmode", "opaque");// transparent wmode="transparent"
so.write("player");
</script>
';
if($_SESSION['level']==1){
$text.="<a href=\"uploader.php\" target=\"popup\" onclick=\"window.open('uploader.php', '', 'width=460,height=360,scrollbars=1');return false;\">Administravimas</a>";
}
?>