<?php
/*
| IRDJ SHOUTcast v1.1
| By Martinj - www.martinj.co.uk
| Modified by Paulius - mightmedia.lt
| Plugin for MightMedia
 */
ini_set('allow_call_time_pass_reference',true);

include "priedai/dj_function.php";
$text="";

if ($scsuccs<>"1" && $streamstatus=="1")
 {

if (!ISSET($song['0']))
    $text .= $song['1']." ";
else
    $text .= $servertitle;
if ($djus==true)
	$text .= "<br />Eteryje: $servergenre";
if ($klausytojai==true && $currentlisteners > 0)
	$text .= "<br />Klauso: $currentlisteners";

if ($kokybe==true)
	$text .= "<br />Kokybė: $bitrate kbps";

$text .="<br />";

if ($daina==true)
	$text .= "Daina: <span title='$song[0]'>".trimlink($song[0],25)."</span>";

 }

else
	{
	//  $work=mysql_query("UPDATE nowplaying SET live='0'");
	$text .= "Radijas neveikia.";
	}
	
//$ns->tablerender($irdj_name,$text);
 
?>