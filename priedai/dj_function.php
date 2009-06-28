<?php
/*
|  IRDJ SHOUTcast v1.1
| By Martinj - www.martinj.co.uk
| Modificated by Paulius - mightmedia.lt
| Plugin for MightMedia
 */
$scip='srv.tr.lt';// serveris
$scport='8020';//serverio portas
$scpass='slaptazodis';//serverio slaptažodis
$klausytojai=true;//Rodyti klausytojus? true/false
$kokybe=true;//Rodyti transliavimo kokybe? true/false
$daina=true;//Rodyti dainos pavadinimą? true/false
$djus=true;//Rodyti kas eteryje? true/false
//true = taip
//false = ne
//Nieko toliau nekeisk
$pagei="";
$scfp = @fsockopen("$scip", $scport, &$errno, &$errstr, 3);
 if(!$scfp) {
  $scsuccs=1;
}else{$scsuccs=0;}
if($scsuccs!=1){
 fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
 while(!feof($scfp)) {
  $pagei .= fgets($scfp, 1000);
 }

 // xml elements
 $loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS", "MAXLISTENERS", "BITRATE","SERVERGENRE");
 $y=0;
 while(isset($loop[$y])){
  $pageed = ereg_replace(".*<$loop[$y]>", "", $pagei);
  $scphp = strtolower($loop[$y]);
  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
  //if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
  // $$scphp = urldecode($$scphp);

// uncomment the next line to see all variables
//echo'$'.$scphp.' = '.$$scphp.'<br>';
  $y++;
 }

 
 $pageed = ereg_replace(".*<SONGHISTORY>", "", $pagei);
 $pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
 $songatime = explode("<SONG>", $pageed);
 $r=1;
 while(isset($songatime[$r])){
  $t=$r-1;
  $playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
  $playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
  $song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
  $song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
  $song[$t] = urldecode($song[$t]);
  $dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $pagei);
  $dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
$r++;
 }
//end song info
fclose($scfp);
}

?>

