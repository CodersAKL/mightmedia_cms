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
var so = new SWFObject("flashmp3player/flashmp3player.swf", "player", "180", "247", "9"); // Location of swf file. You can change player width and height here (using pixels or percents).
so.addParam("quality", "high");
so.addVariable("content_path","mp3"); // Location of a folder with mp3 files (relative to php script).
so.addVariable("color_path","flashmp3player/default.xml"); // Location of xml file with color settings.
so.addVariable("script_path","flashmp3player/flashmp3player.php"); // Location of php script.
so.write("player");
</script>
<font style="font-size:5px;";>
Powered by <a href="http://www.flashmp3player.org">Flash MP3 Player</a>
</font>
';
if($_SESSION['level']==1){
$text.="<a href=\"uploader.php\" target=\"popup\" onclick=\"window.open('uploader.php', '', 'width=460,height=360');return false;\">Administravimas</a>";
}
?>