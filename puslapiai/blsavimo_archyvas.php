<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/
//NEBAIKTA
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}

$limit = 10;
$viso = kiek("balsavimas");
$text = '';

//Atvaizduojam pranesimus su puslapiavimu - LIMITAS nurodytas virsuje
if ($viso > 0) {
	$sql2 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "balsavimas` ORDER BY `laikas` DESC LIMIT $p, $limit", 2000);

	//Puslapiavimas
	if ($viso > $limit) {
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
	}

		foreach ($sql2 as $sql) {
		$ipasai = explode(";", $sql['ips']);
	$nariai = explode(";", $sql['nariai']);
	$ats = array();
	$atsa = array();
	$ats[1] = explode(";", $sql['pirmas']);
	$ats[2] = explode(";", $sql['antras']);
	$ats[3] = explode(";", $sql['trecias']);
	$ats[4] = explode(";", $sql['ketvirtas']);
	$ats[5] = explode(";", $sql['penktas']);

	$viso = ((int)$ats[1][1] + (int)$ats[2][1] + (int)$ats[3][1] + (int)$ats[4][1] + (int)$ats[5][1]);


		for ($i = 1; $i <= 5; $i++) {
	if (!empty($ats[$i][0])) {
				$atsa[$i] = "<br />" . $ats[$i][0] . " [" . $ats[$i][1] . "] <br />";
				$img = round((int)(100 / $viso * $ats[$i][1]));
                $atsa[$i] .= '
         <div style="width:'.$img.'%;background:url(images/balsavimas/center.png) top left repeat-x; height:10px">
         
			<div style="float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px"></div>
			<div style="float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px"></div>

		</div>
';
			} else {
				$atsa[$i] = '';
			}
		

		$rezultatai = '<blockquote><div align="left"><center><b>' . (isset($sql['klausimas']) ? $sql['klausimas'] : "N/A") . '</b></center>' . $atsa[1] . $atsa[2] . $atsa[3] . $atsa[4] . $atsa[5] . '</div>';
	
}
		}
		lentele($lang['poll']['archive'], $rezultatai);

		//Puslapiavimas
		if ($viso > $limit) {
			lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
		}
	
} else {
	lentele($lang['poll']['archive'], $lang['poll']['no']);
}

unset($extra, $text);

//PABAIGA - atvaizdavimo


?>