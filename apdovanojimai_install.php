<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 204 $
 * @$Date: 2009-06-28 19:02:38 +0300 (Sk, 28 Bir 2009) $
 **/
ob_start();
session_start();
include_once (dirname(__file__) . "/priedai/conf.php");
include_once (dirname(__file__) . "/priedai/prisijungimas.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php header_info(); ?>
	</head>
	<body>
<?PHP
$text='Sveiki, jūs paleidote apdivanojimų modulio įdiegimo failą sekite tolesnius nurodymus, jei norite teisingai įdiegti šį modulį.<br />
<b>Modulio autorius:</b> Paulius D.<br />
<b>Modulio versija:</b> 1.0<br />
<b>Modulio kaina:</b> 0.00 <br />

<hr></hr><br /><br />';
if(!isset($url['s'])){
$text.='<h1>Versijos tikrinimas</h1><br />';
if(versija()<1.29){
$text.='<b><font color="red">Jūsų naudojama MM TVS versija('.versija().') yra per sena šiam moduliui, jei norite jį naudoti, jums reikalinga ne žemesnė už 1.29 versija. Naujausią MightMedia TVS versiją galite parsisiųsti iš oficialaus tinklalapio <a href="http://mightmedia.lt">www.mightmedia.lt</a></font></b>';
}else{
$text.='Jūsų versija tinkama šiam moduliui.<br /><a href="?s,2">Toliau >></a>';
}
}elseif($url['s']==2){
$klaida=false;
$text.='<h1>Failų tikrinimas</h1>';
if(file_exists('puslapiai/apdovanojimai.php')){
msg("Rasta","Failas <b>puslapiai/apdovanojimai.php</b> rastas");
}else{
$klaida=true;
klaida("Klaida", "Trūksta modulio failo <b>puslapiai/apdovanojimai.php</b>");
}
if(file_exists('puslapiai/dievai/apdovanojimai.php')){
msg("Rasta","Failas <b>puslapiai/dievai/apdovanojimai.php</b> rastas");
}else{
$klaida=true;
klaida("Klaida", "Trūksta modulio failo <b>puslapiai/dievai/apdovanojimai.php</b>");
}
if($klaida==false){
$text.='Visi reikalingi failai rasti.<br /><a href="?s,3">Toliau >></a>';
}else{
$text.='Ištaisykite klaidas ir bandykite dar kartą(perkraukite šį puslapį).';
}
}elseif($url['s']==3){
$text.='<h1>Failo puslapiai/view_user.php modifikavimas</h1><br />';
$text.=<<<HTML
Atidarykite failą puslapiai/view_user.php, jame suraskitetokią eilutę (~21 eilutė):<br />
<textarea cols=40 rows=8>\$sql = mysql_query1("SELECT *, INET_NTOA(ip) AS ip FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`='" . \$url['m'] . "' LIMIT 1");</textarea><br /> Po ja įklijuokite šį kodą:<br />
<textarea cols=40 rows=8>	//apdovanojimai
	\$ap = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "apdovanojimai` WHERE `uid`='" . \$url['m'] . "'");
	if(sizeof(\$ap) > 0){
	foreach(\$ap as \$apdo){
	\$apdovanojimai[]="<img src=\"{\$apdo['img']}\" alt=\"\" title=\"{\$apdo['nuopelnas']}\" />";
	}
	\$apdo_content='	<tr class="th">
				<th class="th" height="14" colspan="3" >Apdovanojimai</th>
			</tr>
      <tr class="tr">
        <td class="td" colspan="3"> 
          ' . implode(", ", \$apdovanojimai) . '
        </td>
      </tr>';}
      else{\$apdo_content='';}
	//apdovanojimai</textarea><br />
	Tada suraskite tokią kodo atkarpą:<br />
	<textarea cols=40 rows=8>			<tr class="tr2">
				<td class="td" rowspan="1" height="87" valign="top" width="140"><small>
					<b>' . \$lang['forum']['topic'] . ':</b> ' . \$sql['forum_temos'] . '<br />
					<b>' . \$lang['forum']['messages'] . ':</b>	' . \$sql['forum_atsakyta'] . '<br /></small>
        </td>
				<td class="td" colspan="2" height="18" width="280">' . bbcode(\$sql['parasas']) . '</td>
			</tr>	</textarea><br /> Ir po ja parašykitete šį kodą:<br />
			<textarea cols=40 rows=8>'.\$apdo_content.'</textarea><br />
			<b>Jei tikrai viską atlikote</b><br /><a href="?s,4">Toliau >></a>
			
HTML;
}elseif($url['s']==4){
$text.='<h1>Duomenų lentelės kūrimas</h1><br />';
$r=mysql_query("CREATE TABLE `".LENTELES_PRIESAGA."apdovanojimai` (
  `id` int(150) NOT NULL auto_increment,
  `uid` int(150) NOT NULL,
  `nick` varchar(255) character set utf8 collate utf8_lithuanian_ci NOT NULL,
  `img` varchar(255) NOT NULL,
  `nuopelnas` varchar(255) character set utf8 collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
if($r){
msg("Atlikta","Duomenų bazės lentelė sukurta,ištrinkite failą <b>apdovanojimai_install.php</b> ir sistemos puslapių administravime įjunkite puslapį <b>apdovanojimai.php</b>.");
}else{
klaida("Klaida","Nepavyko sukurti lentelės.".mysql_error());
}
}
lentele('Apdovanojimų modulio diegimas',$text);
?>
				
	</body>
</html>
<?php ob_end_flush(); ?>