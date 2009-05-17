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
	if (sizeof($sql2) > 0) {
		foreach ($sql2 as $row) {
			$sql3 = mysql_query1("SELECT `id`,`nick`,`levelis` FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $row['autorius'] . "' LIMIT 1") or die(klaida($lang['system']['error'], mysql_error()));
			//$sql3 = mysql_fetch_assoc($sql3);

			$ats1 = explode(";", $row['pirmas']);
			$ats2 = explode(";", $row['antras']);
			$ats3 = explode(";", $row['trecias']);
			$ats4 = explode(";", $row['ketvirtas']);
			$ats5 = explode(";", $row['penktas']);

			$kiok = ($ats1[1] + $ats2[1] + $ats3[1] + $ats4[1] + $ats5[1]);
			if ($viso != 0) {
				if (!empty($ats1[0])) {
					$atsa1 = $ats1[0] . " [" . $ats1[1] . "] <br><hr align='left' width='" . @(100 / $kiok * $ats1[1]) . "'></hr><br/>";
				} else {
					$atsa1 = '';
				}
				if (!empty($ats2[0])) {
					$atsa2 = $ats2[0] . " [" . $ats2[1] . "] <br><hr align='left' width='" . @(100 / $kiok * $ats2[1]) . "'></hr><br/>";
				} else {
					$atsa2 = '';
				}
				if (!empty($ats3[0])) {
					$atsa3 = $ats3[0] . " [" . $ats3[1] . "] <br><hr align='left' width='" . @(100 / $kiok * $ats3[1]) . "'></hr><br/>";
				} else {
					$atsa3 = '';
				}
				if (!empty($ats4[0])) {
					$atsa4 = $ats4[0] . " [" . $ats4[1] . "] <br><hr align='left' width='" . @(100 / $kiok * $ats4[1]) . "'></hr><br/>";
				} else {
					$atsa4 = '';
				}
				if (!empty($ats5[0])) {
					$atsa5 = $ats5[0] . " [" . $ats5[1] . "] <br><hr align='left' width='" . @(100 / $kiok * $ats5[1]) . "'></hr><br/>";
				} else {
					$atsa5 = '';
				}

				$rezultatai = '<div class="sarasas"><b>' . $row['klausimas'] . '</b><br>' . $atsa1 . $atsa2 . $atsa3 . $atsa4 . $atsa5 . '<br>' . $lang['poll']['votes'] . ': ' . $kiok . '<br>' . $lang['poll']['author'] . ': ' . user($sql3['nick'], $sql3['id'], $sql3['levelis']) . '</div>';
			} else {
				$rezultatai = '';
			}
			$text .= $rezultatai;

		}
		lentele($lang['poll']['archive'], $text);

		//Puslapiavimas
		if ($viso > $limit) {
			lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
		}
	}
} else {
	lentele($lang['poll']['archive'], $lang['poll']['no']);
}

unset($extra, $text);

//PABAIGA - atvaizdavimo


?>