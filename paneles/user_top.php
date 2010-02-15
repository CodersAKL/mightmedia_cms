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

$q = mysql_query1("SELECT id, nick, reg_data, login_data, gim_data,  taskai, levelis FROM `" . LENTELES_PRIESAGA . "users` WHERE `taskai` > 0 ORDER BY `taskai` DESC LIMIT 10");
$i = '';
$text = ' ';
if (sizeof($q) > 0) {
	foreach ($q as $row) {
		$i++;
		if ($i == 1) {
			$img = "<img src=\"images/icons/trophy.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
		} elseif ($i == 2) {
			$img = "<img src=\"images/icons/trophy_silver.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
		} elseif ($i == 3) {
			$img = "<img src=\"images/icons/trophy_bronze.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
		} else {
			$img = "<img src=\"images/icons/brightness_small_low.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
		}
		$text .= "<div class=\"sarasas\"><br/><b>" . $i . "</b> $img " . user($row['nick'], $row['id'], $row['levelis']) . "</div>";

		/*title=\"header=[".$row['nick']."] body=[<br/>
		* <p>Nick: <b>".$row['nick']."</b><br/>
		* Taškai: <b>".$row['taskai']."</b><br/>
		* Amžius: <b>".amzius($row['gim_data'])."</b>m.<br/>
		* Užsiregistravo: <b>".$row['reg_data']."</b><br/>
		* Lankėsi: <b>".kada($row['login_data'])."</b></p>
		* ] fade=[on]\">".$row['nick']."
		* </a> (".$row['taskai'].")</div>\n";*/
	}
} else {
	$text = ' ';
	$row_p['show'] = 'N';
}
unset($img, $q, $row);

?>
